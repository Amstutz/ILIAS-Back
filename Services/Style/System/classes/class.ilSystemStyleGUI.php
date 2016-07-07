<?php

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

		$form->addCommandButton("save", $this->lng->txt("save"));
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


	/**
	* edit style sheet
	*/
	function editObject()
	{


		$this->setSubTabs();


		$this->tpl->setContent();
	}

	/**
	* Properties
	*/
	function propertiesObject()
	{
		global $ilToolbar;


		// export button
		$ilToolbar->addButton($this->lng->txt("export"),
			$this->ctrl->getLinkTarget($this, "exportStyle"));

		$this->initPropertiesForm();
		$this->getPropertiesValues();
		$this->tpl->setContent($this->form->getHTML());
	}
	
	/**
	* Get current values for properties from 
	*
	*/
	public function getPropertiesValues()
	{
		$values = array();
	
		$values["style_title"] = $this->object->getTitle();
		$values["style_description"] = $this->object->getDescription();
		$values["disable_auto_margins"] = (int) $this->object->lookupStyleSetting("disable_auto_margins");
	
		$this->form->setValuesByArray($values);
	}
	
	/**
	* FORM: Init properties form.
	*
	* @param        int        $a_mode        Edit Mode
	*/
	public function initPropertiesForm($a_mode = "edit")
	{
		global $lng, $rbacsystem;
		
		include_once("Services/Form/classes/class.ilPropertyFormGUI.php");
		$this->form = new ilPropertyFormGUI();
	
		// title
		$ti = new ilTextInputGUI($this->lng->txt("title"), "style_title");
		$ti->setMaxLength(128);
		$ti->setSize(40);
		$ti->setRequired(true);
		$this->form->addItem($ti);
		
		// description
		$ta = new ilTextAreaInputGUI($this->lng->txt("description"), "style_description");
		//$ta->setCols();
		//$ta->setRows();
		$this->form->addItem($ta);
		
		// disable automatic margins for left/right alignment
		$cb = new ilCheckboxInputGUI($this->lng->txt("sty_disable_auto_margins"), "disable_auto_margins");
		$cb->setInfo($this->lng->txt("sty_disable_auto_margins_info"));
		$this->form->addItem($cb);
		
		// save and cancel commands
		
		if ($a_mode == "create")
		{
			$this->form->addCommandButton("save", $lng->txt("save"));
			$this->form->addCommandButton("cancelSave", $lng->txt("cancel"));
		}
		else
		{
			if ($this->checkWrite())
			{
				$this->form->addCommandButton("update", $lng->txt("save"));
			}
		}
	                
		$this->form->setTitle($lng->txt("edit_stylesheet"));
		$this->form->setFormAction($this->ctrl->getFormAction($this));
	 
	}
	
	/**
	* Update properties
	*/
	function updateObject()
	{
		global $lng, $ilCtrl, $tpl;
		
		$this->initPropertiesForm("edit");
		if ($this->form->checkInput())
		{
			$this->object->setTitle($this->form->getInput("style_title"));
			$this->object->setDescription($this->form->getInput("style_description"));
			$this->object->writeStyleSetting("disable_auto_margins",
				$this->form->getInput("disable_auto_margins"));
			$this->object->update();
			ilUtil::sendInfo($lng->txt("msg_obj_modified"), true);
			$ilCtrl->redirect($this, "properties");
		}
		else
		{
			$this->form->setValuesByPost();
			$tpl->setContent($this->form->getHtml());
		}
	}



	/**
	 * After import
	 *
	 * @param
	 * @return
	 */
	function afterImport(ilObject $a_new_obj)
	{

	}


	/**
	* update style sheet
	*/
	function cancelObject()
	{
		global $lng;

		ilUtil::sendInfo($lng->txt("msg_cancel"), true);
		$this->ctrl->returnToParent($this);
	}
	
	/**
	* admin and normal tabs are equal for roles
	*/
	function getAdminTabs()
	{
		$this->getTabs();
	}

	/**
	* output tabs
	*/
	function setTabs()
	{
		global $lng;

		$this->getTabs($this->tabs_gui);

		if (strtolower(get_class($this->object)) == "ilobjstylesheet")
		{
			$this->tpl->setVariable("HEADER", $this->object->getTitle());
		}
		else
		{
			$this->tpl->setVariable("HEADER", $lng->txt("create_stylesheet"));
		}
	}

	/**
	* adds tabs to tab gui object
	*
	* @param	object		$tabs_gui		ilTabsGUI object
	*/
	function getTabs()
	{
		global $lng, $ilCtrl, $ilTabs, $ilHelp;
		
		$ilHelp->setScreenIdComponent("sty");
		
		if ($ilCtrl->getCmd() == "editTagStyle")
		{
			// back to upper context
			$this->tabs_gui->setBackTarget($lng->txt("back"),
				$ilCtrl->getLinkTarget($this, "edit"));
				
			$t = explode(".", $_GET["tag"]);
			$t2 = explode(":", $t[1]);
			$pc = $this->object->_getPseudoClasses($t[0]);
			if (is_array($pc) && count($pc) > 0)
			{
				// style classes
				$ilCtrl->setParameter($this, "tag", $t[0].".".$t2[0]);
				$this->tabs_gui->addTarget("sty_tag_normal",
					$this->ctrl->getLinkTarget($this, "editTagStyle"), array("editTagStyle", ""),
					get_class($this));
				if ($t2[1] == "")
				{
					$ilTabs->setTabActive("sty_tag_normal");
				}
				
				foreach ($pc as $p)
				{
					// style classes
					$ilCtrl->setParameter($this, "tag", $t[0].".".$t2[0].":".$p);
					$this->tabs_gui->addTarget("sty_tag_".$p,
						$this->ctrl->getLinkTarget($this, "editTagStyle"), array("editTagStyle", ""),
						get_class($this));
					if ($t2[1] == $p)
					{
						$ilTabs->setTabActive("sty_tag_".$p);
					}
				}
				$ilCtrl->setParameter($this, "tag", $_GET["tag"]);
			}
		}
		else
		{
			// back to upper context
			$this->tabs_gui->setBackTarget($lng->txt("back"),
				$this->ctrl->getLinkTarget($this, "returnToUpperContext"));
	
			// style classes
			$this->tabs_gui->addTarget("sty_style_chars",
				$this->ctrl->getLinkTarget($this, "edit"), array("edit", ""),
				get_class($this));

			// colors
			$this->tabs_gui->addTarget("sty_colors",
				$this->ctrl->getLinkTarget($this, "listColors"), "listColors",
				get_class($this));

			// media queries
			$this->tabs_gui->addTarget("sty_media_queries",
				$this->ctrl->getLinkTarget($this, "listMediaQueries"), "listMediaQueries",
				get_class($this));

			// images
			$this->tabs_gui->addTarget("sty_images",
				$this->ctrl->getLinkTarget($this, "listImages"), "listImages",
				get_class($this));

			// table templates
			$this->tabs_gui->addTarget("sty_templates",
				$this->ctrl->getLinkTarget($this, "listTemplates"), "listTemplates",
				get_class($this));
				
			// settings
			$this->tabs_gui->addTarget("settings",
				$this->ctrl->getLinkTarget($this, "properties"), "properties",
				get_class($this));

			// accordiontest
/*
			$this->tabs_gui->addTarget("accordiontest",
				$this->ctrl->getLinkTarget($this, "accordiontest"), "accordiontest",
				get_class($this));*/
		}

	}

	/**
	* adds tabs to tab gui object
	*
	* @param	object		$tabs_gui		ilTabsGUI object
	*/
	function setSubTabs()
	{
		global $lng, $ilTabs, $ilCtrl;
		
		$types = ilObjStyleSheet::_getStyleSuperTypes();
		
		foreach ($types as $super_type => $types)
		{
			// text block characteristics
			$ilCtrl->setParameter($this, "style_type", $super_type);
			$ilTabs->addSubTabTarget("sty_".$super_type."_char",
				$this->ctrl->getLinkTarget($this, "edit"), array("edit", ""),
				get_class($this));
		}

		$ilCtrl->setParameter($this, "style_type", $_GET["style_type"]);
	}


	/**
	* should be overwritten to add object specific items
	* (repository items are preloaded)
	*/
	function addAdminLocatorItems($a_do_not_add_object = false)
	{
		global $ilLocator;

		if ($_GET["admin_mode"] == "settings")	// system settings
		{		
			parent::addAdminLocatorItems(true);
				
			$ilLocator->addItem(ilObject::_lookupTitle(
				ilObject::_lookupObjId($_GET["ref_id"])),
				$this->ctrl->getLinkTargetByClass("ilobjstylesettingsgui", ""));

			if ($_GET["obj_id"] > 0)
			{
				$ilLocator->addItem($this->object->getTitle(),
					$this->ctrl->getLinkTarget($this, "edit"));
			}
		}
		else							// repository administration
		{
			//?
		}

	}
	
	function showUpperIcon()
	{
		global $tree, $tpl, $objDefinition;
		
		if (strtolower($_GET["baseClass"]) == "iladministrationgui")
		{
				$tpl->setUpperIcon(
					$this->ctrl->getLinkTargetByClass("ilcontentstylesettings",
						"edit"));
		}
		else
		{
			// ?
		}
	}

	
	/**
	* return to upper context
	*/
	function returnToUpperContextObject()
	{
		global $ilCtrl;

		/*if ($_GET["baseClass"] == "ilAdministrationGUI")
		{
			$ilCtrl->redirectByClass("ilcontentstylesettingsgui", "edit");
		}*/
		$ilCtrl->returnToParent($this);
	}


}
?>
