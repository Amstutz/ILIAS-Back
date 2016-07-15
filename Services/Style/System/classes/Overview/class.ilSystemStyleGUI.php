<?php
include_once("class.ilKSDocumentationEntryGUI.php");
include_once("class.ilKitchenSinkLessGUI.php");
include_once("class.ilKitchenSinkIconsGUI.php");
/**
 * Class ilSystemStyleGUI
 *
 * @author Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * $Id$
 *
 */
class ilSystemStyleGUI
{
	/**
	 * @var ILIAS\DI\Container
	 */
	protected $DIC;

	/**
	 * @var ilCtrl
	 */
	protected $ctrl;

	/**
	 * @var ilTabsGUI
	 */
	protected $tabs;


	/**
	 * @var ilLanguage
	 */
	public $lng;

	/**
	 * @var ilTemplate
	 */
	public $tpl;

	/**
	 * ilSystemStyleGUI constructor.
	 */
	function __construct()
	{
		global $DIC;

		$this->dic = $DIC;
		$this->ctrl = $DIC->ctrl();
		$this->lng = $DIC->language();
		$this->tabs = $DIC->tabs();
		$this->tpl = $DIC["tpl"];

	}



	/**
	 * Execute command
	 */
	function executeCommand()
	{
		$cmd = $this->ctrl->getCmd("edit");

		switch ($cmd)
		{
			case "addSystemStyle":
				$this->$cmd();
				break;
			default:
				$this->tabs->activateTab('less');
				$this->less();
				break;
		}
	}

	/**
	* create
	*/
	function addSystemStyle()
	{
		global $ilHelp;

		$forms = array();


		$ilHelp->setScreenIdComponent("sty");
		$ilHelp->setDefaultScreenId(ilHelpGUI::ID_PART_SCREEN, "system_style_create");

		// --- create
		
		include_once "Services/Form/classes/class.ilPropertyFormGUI.php";
		$form = new ilPropertyFormGUI();
		$form->setFormAction($this->ctrl->getFormAction($this));
		$form->setTitle($this->lng->txt("sty_create_new_system_style"));
		
		// title
		$ti = new ilTextInputGUI($this->lng->txt("title"), "style_title");
		$ti->setMaxLength(128);
		$ti->setSize(40);
		$ti->setRequired(true);
		$form->addItem($ti);

		// description
		$ta = new ilTextAreaInputGUI($this->lng->txt("description"), "style_description");
		$ta->setRows(2);
		$form->addItem($ta);

		$form->addCommandButton("saveNewSystemStyle", $this->lng->txt("save"));
		$form->addCommandButton("cancel", $this->lng->txt("cancel"));
		
		$forms[] = $form;
		
		
		// --- import
		
		include_once "Services/Form/classes/class.ilPropertyFormGUI.php";
		$form = new ilPropertyFormGUI();
		$form->setFormAction($this->ctrl->getFormAction($this));
		$form->setTitle($this->lng->txt("sty_import_system_style"));
		
		// title
		$ti = new ilFileInputGUI($this->lng->txt("import_file"), "importfile");
		$ti->setRequired(true);
		$form->addItem($ti);
		
		$form->addCommandButton("importStyle", $this->lng->txt("import"));
		$form->addCommandButton("cancel", $this->lng->txt("cancel"));
		
		$forms[] = $form;
		
		
		// --- clone
		
		include_once "Services/Form/classes/class.ilPropertyFormGUI.php";
		$form = new ilPropertyFormGUI();
		$form->setFormAction($this->ctrl->getFormAction($this));
		$form->setTitle($this->lng->txt("sty_copy_other_system_style"));
		
		// source
		$ti = new ilSelectInputGUI($this->lng->txt("sty_source"), "source_style");
		$ti->setRequired(true);
		//$ti->setOptions();
		$form->addItem($ti);
		
		$form->addCommandButton("copyStyle", $this->lng->txt("copy"));
		$form->addCommandButton("cancel", $this->lng->txt("cancel"));
		
		$forms[] = $form;
		
		
		$this->tpl->setContent($this->getCreationFormsHTML($forms));
	}

	/**
	 * Get HTML for creation forms (accordion)
	 *
	 * @param array $a_forms
	 */
	final protected function getCreationFormsHTML(array $a_forms)
	{
		global $tpl;

		// #13168- sanity check
		foreach($a_forms as $id => $form)
		{
			if(!$form instanceof ilPropertyFormGUI)
			{
				unset($a_forms[$id]);
			}
		}

		// no accordion if there is just one form
		if(sizeof($a_forms) == 1)
		{
			$form_type = key($a_forms);
			$a_forms = array_shift($a_forms);

			// see bug #0016217
			if(method_exists($this, "getCreationFormTitle"))
			{
				$form_title = $this->getCreationFormTitle($form_type);
				if ($form_title != "")
				{
					$a_forms->setTitle($form_title);
				}
			}
			return $a_forms->getHTML();
		}
		else
		{
			include_once("./Services/Accordion/classes/class.ilAccordionGUI.php");

			$acc = new ilAccordionGUI();
			$acc->setBehaviour(ilAccordionGUI::FIRST_OPEN);
			$cnt = 1;
			foreach ($a_forms as $form_type => $cf)
			{
				$htpl = new ilTemplate("tpl.creation_acc_head.html", true, true, "Services/Object");

				// using custom form titles (used for repository plugins)
				$form_title = "";
				if(method_exists($this, "getCreationFormTitle"))
				{
					$form_title = $this->getCreationFormTitle($form_type);
				}
				if(!$form_title)
				{
					$form_title = $cf->getTitle();
				}

				// move title from form to accordion
				$htpl->setVariable("TITLE", $this->lng->txt("option")." ".$cnt.": ".
						$form_title);
				$cf->setTitle(null);
				$cf->setTitleIcon(null);
				$cf->setTableWidth("100%");

				$acc->addItem($htpl->get(), $cf->getHTML());

				$cnt++;
			}

			return "<div class='ilCreationFormSection'>".$acc->getHTML()."</div>";
		}
	}

	/**
	 * Check write
	 *
	 * @param
	 * @return
	 */
	public function checkWrite()
	{
		global $rbacsystem;

		return ($rbacsystem->checkAccess("write", (int) $_GET["ref_id"])
		 || $rbacsystem->checkAccess("sty_write_content", (int) $_GET["ref_id"]));
	}


	protected function setTabs() {
		$this->tabs->clearTargets('entries', 'Entries', $this->ctrl->getLinkTarget($this, 'entries'));

		$this->tabs->addTab('entries', 'Entries', $this->ctrl->getLinkTarget($this, 'entries'));
		$this->tabs->addTab('less', 'Less', $this->ctrl->getLinkTarget($this, 'less'));
		$this->tabs->addTab('icons', 'Icons', $this->ctrl->getLinkTarget($this, 'icons'));
	}


	protected function less(){

		$this->setTabs();
		$this->tabs->activateTab('less');
		$less = new ilKitchenSinkLessGUI($this,new KitchenSinkSkin());
		$this->tpl->setContent($less->renderLess());
	}

	protected function updateLess(){
		$less = new ilKitchenSinkLessGUI($this,new KitchenSinkSkin());
		$this->tpl->setContent($less->updateLess());
	}

	protected function resetLess(){
		$less = new ilKitchenSinkLessGUI($this,new KitchenSinkSkin());
		$less->resetLess();
	}

	protected function icons(){
		$icons = new ilKitchenSinkIconsGUI($this,new KitchenSinkSkin(), $this->getUiFactory());
		$this->tpl->setContent($icons->renderIcons());
	}

	protected function updateIcons(){
		$icons = new ilKitchenSinkIconsGUI($this,new KitchenSinkSkin(), $this->getUiFactory());
		$this->tpl->setContent($icons->updateIcons());
	}

	protected function resetIcons(){
		$icons = new ilKitchenSinkIconsGUI($this,new KitchenSinkSkin(), $this->getUiFactory());
		$this->tpl->setContent($icons->resetIcons());
	}


}
?>
