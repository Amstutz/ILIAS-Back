<?php
/**
 * For the purpose of streamlining the grading and learning-process status definition
 * outside of tests, SCORM courses e.t.c. the IndividualAssessment is used.
 * It caries a LPStatus, which is set Individually.
 *
 * @author Denis Klöpfer <denis.kloepfer@concepts-and-training.de>
 */


require_once 'Services/Object/classes/class.ilObject.php';
require_once 'Modules/IndividualAssessment/classes/Settings/class.ilIndividualAssessmentSettings.php';
require_once 'Modules/IndividualAssessment/classes/Settings/class.ilIndividualAssessmentSettingsStorageDB.php';
require_once 'Modules/IndividualAssessment/classes/Members/class.ilIndividualAssessmentMembersStorageDB.php';
require_once 'Modules/IndividualAssessment/classes/AccessControl/class.ilIndividualAssessmentAccessHandler.php';
class ilObjIndividualAssessment extends ilObject {

	protected $lp_active = null;

	public function __construct($a_id = 0, $a_call_by_reference = true) {
		global $DIC;
		$this->type = 'iass';
		parent::__construct($a_id, $a_call_by_reference);
		$this->settings_storage = new ilIndividualAssessmentSettingsStorageDB($DIC['ilDB']);
		$this->members_storage =  new ilIndividualAssessmentMembersStorageDB($DIC['ilDB']);
		$this->access_handler = new ilIndividualAssessmentAccessHandler(
				 $DIC['ilAccess']
				,$DIC['rbacadmin']
				,$DIC['rbacreview']
				,$DIC['ilUser']);

	}

	/**
	 * @inheritdoc
	 */
	public function create() {
		parent::create();
		$this->settings = new ilIndividualAssessmentSettings($this);
		$this->settings_storage->createSettings($this->settings);
	}

	/**
	 * @inheritdoc
	 */
	public function read() {
		parent::read();
		global $DIC;
		$settings_storage = new ilIndividualAssessmentSettingsStorageDB($DIC['ilDB']);
		$this->settings = $settings_storage->loadSettings($this);
		$this->info_settings = $settings_storage->loadInfoSettings($this);
	}

	/**
	 * @inheritdoc
	 */
	public function getSettings() {
		if(!$this->settings) {
			$this->settings = $this->settings_storage->loadSettings($this);
		}
		return $this->settings;
	}

	public function getInfoSettings() {
		if(!$this->info_settings) {
			$this->info_settings = $this->settings_storage->loadInfoSettings($this);
		}
		return $this->info_settings;
	}

	/**
	 * Get the members object associated with this.
	 *
	 * @return	ilIndividualAssessmentMembers
	 */
	public function loadMembers() {
		return $this->members_storage->loadMembers($this);
	}

	/**
	 * Update the members object associated with this.
	 *
	 * @param	ilIndividualAssessmentMembers	$members
	 */
	public function updateMembers(ilIndividualAssessmentMembers $members) {
		$members->updateStorageAndRBAC($this->members_storage, $this->access_handler);
	}

	/**
	 * @inheritdoc
	 */
	public function delete() {
		$this->settings_storage->deleteSettings($this);
		$this->members_storage->deleteMembers($this);
		parent::delete();
	}

	/**
	 * @inheritdoc
	 */
	public function update() {
		parent::update();
		$this->settings_storage->updateSettings($this->settings);
	}

	public function updateInfo() {
		$this->settings_storage->updateInfoSettings($this->info_settings);
	}

	/**
	 * Get the member storage object used by this.
	 *
	 * @return ilIndividualAssessmentMembersStorage
	 */
	public function membersStorage() {
		return $this->members_storage;
	}

	/**
	 * @inheritdoc
	 */
	public function initDefaultRoles() {
		$this->access_handler->initDefaultRolesForObject($this);
	}

	/**
	 * Get the access handler of this.
	 *
	 * @return	IndividualAssessmentAccessHandler
	 */
	public function accessHandler() {
		return $this->access_handler;
	}

	/**
	 * @inheritdoc
	 */
	public function cloneObject($a_target_id,$a_copy_id = 0) {
		$new_obj = parent::cloneObject($a_target_id,$a_copy_id);
		$settings = $this->getSettings();
		$info_settings = $this->getInfoSettings();
		$new_settings = new ilIndividualAssessmentSettings($new_obj, 
			$settings->content()
			,$settings->recordTemplate());
		$new_obj->settings = $new_settings;

		$new_info_settings = new ilIndividualAssessmentInfoSettings($new_obj,
			$info_settings->contact()
			,$info_settings->responsibility()
			,$info_settings->phone()
			,$info_settings->mails()
			,$info_settings->consultationHours());
		$new_obj->settings = $new_settings;
		$new_obj->info_settings = $new_info_settings;
		$new_obj->settings_storage->updateSettings($new_settings);
		$new_obj->settings_storage->updateInfoSettings($new_info_settings);
		return $new_obj;
	}

	/**
	 * Check wether the LP is activated for current object.
	 *
	 * @return bool
	 */
	public function isActiveLP() {
		if($this->lp_active === null) {
			require_once 'Modules/IndividualAssessment/classes/LearningProgress/class.ilIndividualAssessmentLPInterface.php';
			$this->lp_active = ilIndividualAssessmentLPInterface::isActiveLP($this->getId());
		}
		return $this->lp_active;
	}

	/**
	 * Bubbles up the tree.
	 * Starts from object with id $id.
	 * Ends at root or when a given $type of object is found.
	 *
	 * @global array $DIC
	 * @param int $id start at this id
	 * @param string[] $types search for these strings
	 *
	 * @return int the obj_id or 0 if root is reached
	 */
	public function getParentContainerIdByType($id, array $types) {
		global $DIC;

		$tree = $DIC['tree'];
		$node = $tree->getParentNodeData($id);

		while($node['type'] !== "root") {
			if(in_array($node['type'], $types)) {
				return $node['ref_id'];
			}
			$node = $tree->getParentNodeData($node['ref_id']);
		}
		return 0;
	}
}