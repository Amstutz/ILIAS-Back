<?php

/* Copyright (c) 1998-2016 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Settings UI class for system styles
 *
 * @author Alex Killing <alex.killing@gmx.de>
 * @author Timon Amstutz <timon.amstutz@ilub.unibe.ch>

 * @version $Id$
 * @ingroup ServicesStyle
 *
 * @ilCtrl_Calls ilSystemStyleMainGUI: ilSystemStyleOverviewGUI,ilSystemStyleSettingsGUI
 * @ilCtrl_Calls ilSystemStyleMainGUI: ilSystemStyleLessGUI,ilSystemStyleIconsGUI,ilSystemStyleDocumentationGUI
 *
 */
class ilSystemStyleMainGUI
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
	 * @var ILIAS\DI\Container
	 */
	protected $DIC;

	/**
	 * @var ilTabsGUI
	 */
	protected $tabs;

	/**
	 * Constructor
	 */
	function __construct()
	{
		global $DIC;

		$this->ctrl = $DIC->ctrl();
		$this->lng = $DIC->language();
		$this->tabs = $DIC->tabs();

	}


	/**
	 * Execute command
	 */
	function executeCommand()
	{
		$next_class = $this->ctrl->getNextClass($this);

		$this->ctrl->setParameterByClass('ilsystemstylesettingsgui','skin_id',$_GET["skin_id"]);
		$this->ctrl->setParameterByClass('ilsystemstylesettingsgui','style_id',$_GET["style_id"]);
		$this->ctrl->setParameterByClass('ilsystemstylelessgui','skin_id',$_GET["skin_id"]);
		$this->ctrl->setParameterByClass('ilsystemstylelessgui','style_id',$_GET["style_id"]);
		$this->ctrl->setParameterByClass('ilsystemstyleiconsgui','skin_id',$_GET["skin_id"]);
		$this->ctrl->setParameterByClass('ilsystemstyleiconsgui','style_id',$_GET["style_id"]);
		$this->ctrl->setParameterByClass('ilsystemstyledocumentationgui','skin_id',$_GET["skin_id"]);
		$this->ctrl->setParameterByClass('ilsystemstyledocumentationgui','style_id',$_GET["style_id"]);

		switch ($next_class)
		{

			case "ilsystemstylesettingsgui":
				$this->setUnderworldTabs('settings');
				include_once("Settings/class.ilSystemStyleSettingsGUI.php");
				$system_styles_settings = new ilSystemStyleSettingsGUI();
				$this->ctrl->forwardCommand($system_styles_settings);
				break;
			case "ilsystemstylelessgui":
				$this->setUnderworldTabs('less');
				include_once("Less/class.ilSystemStyleLessGUI.php");
				$system_styles_less = new ilSystemStyleLessGUI();
				$this->ctrl->forwardCommand($system_styles_less);
				break;
			case "ilsystemstyleiconsgui":
				$this->setUnderworldTabs('icons');

				include_once("Icons/class.ilSystemStyleIconsGUI.php");
				$system_styles_icons = new ilSystemStyleIconsGUI();
				$this->ctrl->forwardCommand($system_styles_icons);
				break;
			case "ilsystemstyledocumentationgui":
				$this->setUnderworldTabs('documentation');
				include_once("Documentation/class.ilSystemStyleDocumentationGUI.php");
				$system_styles_documentation = new ilSystemStyleDocumentationGUI();
				$this->ctrl->forwardCommand($system_styles_documentation);
				break;
			case "ilsystemstyleoverviewgui":
			default:
				include_once("Overview/class.ilSystemStyleOverviewGUI.php");
				$system_styles_overview = new ilSystemStyleOverviewGUI();
				$this->ctrl->forwardCommand($system_styles_overview);
				break;
		}
	}

	protected function setUnderworldTabs($active = "") {
		$this->tabs->clearTargets();

		$this->tabs->setBackTarget($this->lng->txt("back"),$this->ctrl->getLinkTarget($this));
		$this->tabs->addTab('settings', $this->lng->txt('settings'), $this->ctrl->getLinkTargetByClass('ilsystemstylesettingsgui'));
		$this->tabs->addTab('less', $this->lng->txt('less'), $this->ctrl->getLinkTargetByClass('ilsystemstylelessgui'));
		$this->tabs->addTab('icons', $this->lng->txt('icons'), $this->ctrl->getLinkTargetByClass('ilsystemstyleiconsgui'));
		$this->tabs->addTab('documentation', $this->lng->txt('documentation'), $this->ctrl->getLinkTargetByClass('ilsystemstyledocumentationgui'));

		$this->tabs->activateTab($active);

	}
}