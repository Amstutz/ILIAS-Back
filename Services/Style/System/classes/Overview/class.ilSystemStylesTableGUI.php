<?php
include_once("./Services/Style/System/classes/class.ilSystemStyleSettings.php");
/* Copyright (c) 1998-2012 ILIAS open source, Extended GPL, see docs/LICENSE */

include_once("./Services/Table/classes/class.ilTable2GUI.php");

/**
 * TableGUI class for system styles
 *
 * @author Alex Killing <alex.killing@gmx.de>
 * @version $Id$
 *
 * @ingroup ServicesStyle
 */
class ilSystemStylesTableGUI extends ilTable2GUI
{
	/**
	 * @var ilCtrl
	 */
	protected $ctrl;

	/**
	 * @var ilLanguage
	 */
	protected $lng;

	/**
	 * @var bool
	 */
	protected $with_actions = false;

	/**
	 * @var bool
	 */
	protected $management_enabled = false;

	/**
	 * ilSystemStylesTableGUI constructor.
	 * @param int $a_parent_obj
	 * @param string $a_parent_cmd
	 * @param string $read_only
	 * @param $test
	 */
	function __construct($a_parent_obj, $a_parent_cmd)
	{
		global $lng, $rbacsystem;

		global $DIC;

		$this->ctrl = $DIC->ctrl();
		$this->lng = $DIC->language();

		parent::__construct($a_parent_obj, $a_parent_cmd);
		$this->getStyles();
//		$this->setTitle($lng->txt(""));

		$this->setLimit(9999);
		$this->addColumn($this->lng->txt(""));
		$this->addColumn($this->lng->txt("title"));
		$this->addColumn($this->lng->txt("default"));
		$this->addColumn($this->lng->txt("active"));
		$this->addColumn($this->lng->txt("users"));
		$this->addColumn($this->lng->txt("sty_substyle_of"));
		$this->setRowTemplate("tpl.sys_styles_row.html", "Services/Style/System");

	}

	public function addActions($management_enabled){
		$this->setWithActions(true);
		$this->setManagementEnabled($management_enabled);

		$this->addColumn($this->lng->txt("actions"));
		$this->setFormAction($this->ctrl->getFormAction($this->getParentObject()));
		$this->addCommandButton("saveStyleSettings", $this->lng->txt("save"));
		$this->setRowTemplate("tpl.sys_styles_row_with_actions.html", "Services/Style/System");

		if($management_enabled){
			$this->addMultiCommand("deleteStyles",$this->lng->txt("delete"));
		}
	}

	/**
	 *
	 */
	function getStyles()
	{
		// get all user assigned styles
		$all_user_styles = ilObjUser::_getAllUserAssignedStyles();
		
		// output "other" row for all users, that are not assigned to
		// any existing style
		$users_missing_styles = 0;
		foreach($all_user_styles as $skin_style_id)
		{
			$style_arr = explode(":", $skin_style_id);
			if (!ilStyleDefinition::styleExists($style_arr[1]))
			{

				$users_missing_styles += ilObjUser::_getNumberOfUsersForStyle($style_arr[0], $style_arr[1]);
			}
		}
		$all_styles = ilStyleDefinition::getAllSkinStyles();
		if ($users_missing_styles > 0)
		{
			$all_styles["other"] =
				array (
					"title" => $this->lng->txt("other"),
					"id" => "other",
					"template_id" => "",
					"skin_id" => "other",
					"style_id" => "",
					"template_name" => "",
					"style_name" => "",
					"users" => $users_missing_styles
					);
		}

		$this->setData($all_styles);
	}
	
	
	/**
	 * Fill table row
	 */
	protected function fillRow($a_set)
	{
		global $DIC;

		$this->tpl->setVariable("TITLE", $a_set["title"]);
		$this->tpl->setVariable("TITLE", $a_set["title"]);
		$is_substyle = $a_set["substyle_of"] != "";

		if(!$is_substyle) {
			$this->tpl->setVariable("USERS", $a_set["users"]);
		}else{
			$this->tpl->setVariable("USERS", "-");
		}

		if($a_set["id"] != "other"){
			$this->tpl->setCurrentBlock("default_input");

			if(!$is_substyle) {
				$this->tpl->setVariable("DEFAULT_ID", $a_set["id"]);
				if (ilSystemStyleSettings::getCurrentDefaultSkin() == $a_set["skin_id"] &&
						ilSystemStyleSettings::getCurrentDefaultStyle() == $a_set["style_id"]
				) {
					$this->tpl->setVariable("CHECKED_DEFAULT", ' checked="checked" ');
				} else {
					$this->tpl->setVariable("CHECKED_DEFAULT", '');
				}
				$this->tpl->parseCurrentBlock();
			}

			$this->tpl->setCurrentBlock("active_input");
			$this->tpl->setVariable("ACTIVE_ID", $a_set["id"]);

			if($is_substyle){
				$this->tpl->setVariable("DISABLED_ACTIVE", 'disabled');

				if (ilSystemStyleSettings::_lookupActivatedStyle($a_set["skin_id"], $a_set["substyle_of"])){
					$this->tpl->setVariable("CHECKED_ACTIVE", ' checked="checked" ');
				}else{
					$this->tpl->setVariable("CHECKED_ACTIVE", '');
				}
			}else{
				if (ilSystemStyleSettings::_lookupActivatedStyle($a_set["skin_id"], $a_set["style_id"])){
					$this->tpl->setVariable("CHECKED_ACTIVE", ' checked="checked" ');
				}else{
					$this->tpl->setVariable("CHECKED_ACTIVE", '');
				}
			}

			$this->tpl->parseCurrentBlock();

		}

		if($is_substyle){
			$this->tpl->setCurrentBlock("substyle");
			$this->tpl->setVariable("SUB_STYLE_OF", $a_set["substyle_of"]);

			$assignments = ilSystemStyleSettings::getSubStyleCategoryAssignments(
					$a_set["skin_id"],
					$a_set["substyle_of"],
					$a_set["style_id"]
			);

			$categories = [];

			foreach($assignments as $assignment) {
				$categories[] = ilObject::_lookupTitle(ilObject::_lookupObjId($assignment["ref_id"]));
			}

			$listing = $DIC->ui()->factory()->listing()->unordered($categories);
			$this->tpl->setVariable("CATEGORIES",$DIC->ui()->renderer()->render($listing) );
			$this->tpl->parseCurrentBlock();
		}

		if($this->isWithActions()){
			if($a_set["skin_id"]!="default" && $a_set["skin_id"]!="other"){
				$this->ctrl->setParameterByClass('ilSystemStyleSettingsGUI','skin_id',$a_set["skin_id"]);
				$this->ctrl->setParameterByClass('ilSystemStyleSettingsGUI','style_id',$a_set["style_id"]);

				$this->ctrl->setParameterByClass('ilSystemStyleOverviewGUI','skin_id',$a_set["skin_id"]);
				$this->ctrl->setParameterByClass('ilSystemStyleOverviewGUI','style_id',$a_set["style_id"]);

				if($this->isManagementEnabled()){
					$this->tpl->setCurrentBlock("multi_actions");
					$this->tpl->setVariable("MULTI_ACTIONS_ID", $a_set["id"]);
					$this->tpl->parseCurrentBlock();

					$this->tpl->setCurrentBlock("actions");
					$this->tpl->setVariable("ACTION_TARGET", $this->ctrl->getLinkTargetByClass('ilSystemStyleSettingsGUI'));
					$this->tpl->setVariable("TXT_ACTION", $this->lng->txt('edit'));
					$this->tpl->parseCurrentBlock();

					$this->tpl->setCurrentBlock("actions");
					$this->tpl->setVariable("ACTION_TARGET", $this->ctrl->getLinkTargetByClass('ilSystemStyleOverviewGUI','deleteStyle'));
					$this->tpl->setVariable("TXT_ACTION", $this->lng->txt('delete'));
					$this->tpl->parseCurrentBlock();
				}
				if(!$is_substyle){
					$this->tpl->setCurrentBlock("actions");
					$this->tpl->setVariable("ACTION_TARGET", $this->ctrl->getLinkTargetByClass('ilSystemStyleOverviewGUI','export'));
					$this->tpl->setVariable("TXT_ACTION", $this->lng->txt('export'));
					$this->tpl->parseCurrentBlock();
				}

			}
		}
	}

	/**
	 * @return boolean
	 */
	public function isWithActions()
	{
		return $this->with_actions;
	}

	/**
	 * @param boolean $with_actions
	 */
	public function setWithActions($with_actions)
	{
		$this->with_actions = $with_actions;
	}

	/**
	 * @return boolean
	 */
	public function isManagementEnabled()
	{
		return $this->management_enabled;
	}

	/**
	 * @param boolean $management_enabled
	 */
	public function setManagementEnabled($management_enabled)
	{
		$this->management_enabled = $management_enabled;
	}


}
?>
