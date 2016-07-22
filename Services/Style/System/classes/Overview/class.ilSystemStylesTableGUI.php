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
	 * Constructor
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
		$this->addColumn($this->lng->txt("users"));
		$this->addColumn($this->lng->txt("active"));
		$this->addColumn($this->lng->txt("sty_substyles"));
		$this->addColumn($this->lng->txt("actions"));
		
		$this->setFormAction($this->ctrl->getFormAction($a_parent_obj));
		$this->setRowTemplate("tpl.sys_styles_row.html", "Services/Style/System");

		if ($rbacsystem->checkAccess("sty_write_system", (int) $_GET["ref_id"]))
		{
			$this->addCommandButton("saveStyleSettings", $lng->txt("save"));
			$this->addMultiCommand("deleteStyles",$this->lng->txt("delete"));
		}
	}
	
	/**
	 * Get styles
	 *
	 * @param
	 * @return
	 */
	function getStyles()
	{
		// get all user assigned styles
		$all_user_styles = ilObjUser::_getAllUserAssignedStyles();
		
		// output "other" row for all users, that are not assigned to
		// any existing style
		$users_missing_styles = 0;
		foreach($all_user_styles as $style)
		{
			if (!ilStyleDefinition::doesStyleExist($style))
			{
				$style_arr = explode(":", $style);
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
		global $lng, $ilClientIniFile, $ilCtrl;

		$cat_ass = ilSystemStyleSettings::getSystemStyleCategoryAssignments($a_set["skin_id"],
			$a_set["style_id"]);

		if (is_array($a_set["substyle"]))
		{
			foreach ($a_set["substyle"] as $substyle)
			{
				reset($cat_ass);
				$cats = false;
				foreach($cat_ass as $ca)
				{
					if ($ca["substyle"] == $substyle["id"])
					{
						$this->tpl->setCurrentBlock("cat");
						$this->tpl->setVariable("CAT", ilObject::_lookupTitle(
							ilObject::_lookupObjId($ca["ref_id"])));
						$this->tpl->parseCurrentBlock();
						$cats = true;
					}
				}
				if ($cats)
				{
					$this->tpl->touchBlock("cats");
				}
				
				$this->tpl->setCurrentBlock("substyle");
				$this->tpl->setVariable("SUB_STYLE", $substyle["name"]);
				$this->tpl->parseCurrentBlock();
			}
			$this->tpl->touchBlock("substyles");
			
			$ilCtrl->setParameter($this->parent_obj, "style_id", urlencode($a_set["id"]));
			$this->tpl->setCurrentBlock("cmd");
			$this->tpl->setVariable("HREF_CMD", $this->ctrl->getLinkTarget($this->parent_obj,
				"assignStylesToCats"));
			$this->tpl->setVariable("TXT_CMD", $lng->txt("sty_assign_categories"));
			$this->tpl->parseCurrentBlock();
		}
		$this->tpl->setVariable("TITLE", $a_set["title"]);
		$this->tpl->setVariable("TITLE", $a_set["title"]);
		$this->tpl->setVariable("ID", $a_set["id"]);
		
		// number of users
		$this->tpl->setVariable("USERS", $a_set["users"]);

		// activation
		include_once("./Services/Style/System/classes/class.ilSystemStyleSettings.php");
		if (ilSystemStyleSettings::_lookupActivatedStyle($a_set["skin_id"], $a_set["style_id"]))
		{
			$this->tpl->setVariable("CHECKED", ' checked="checked" ');
		}

		if ($ilClientIniFile->readVariable("layout","skin") == $a_set["skin_id"] &&
			$ilClientIniFile->readVariable("layout","style") == $a_set["style_id"])
		{
			$this->tpl->setVariable("CHECKED_DEFAULT", ' checked="checked" ');
		}






		if($a_set["skin_id"]!="default" && $a_set["skin_id"]!="other"){
			$this->tpl->setCurrentBlock("multi_actions");
			$this->tpl->setVariable("MULTI_ACTIONS_ID", $a_set["id"]);

			$this->tpl->parseCurrentBlock();

			$this->ctrl->setParameterByClass('ilSystemStyleSettingsGUI','skin_id',$a_set["skin_id"]);
			$this->ctrl->setParameterByClass('ilSystemStyleSettingsGUI','style_id',$a_set["style_id"]);

			$this->tpl->setCurrentBlock("actions");
			$this->tpl->setVariable("ACTION_TARGET", $this->ctrl->getLinkTargetByClass('ilSystemStyleSettingsGUI'));
			$this->tpl->setVariable("TXT_ACTION", $this->lng->txt('edit'));
			$this->tpl->parseCurrentBlock();

			$this->ctrl->setParameterByClass('ilSystemStyleOverviewGUI','skin_id',$a_set["skin_id"]);
			$this->ctrl->setParameterByClass('ilSystemStyleOverviewGUI','style_id',$a_set["style_id"]);

			$this->tpl->setCurrentBlock("actions");
			$this->tpl->setVariable("ACTION_TARGET", $this->ctrl->getLinkTargetByClass('ilSystemStyleOverviewGUI','delete'));
			$this->tpl->setVariable("TXT_ACTION", $this->lng->txt('delete'));
			$this->tpl->parseCurrentBlock();

			$this->tpl->setCurrentBlock("actions");
			$this->tpl->setVariable("ACTION_TARGET", $this->ctrl->getLinkTargetByClass('ilSystemStyleOverviewGUI','export'));
			$this->tpl->setVariable("TXT_ACTION", $this->lng->txt('export'));
			$this->tpl->parseCurrentBlock();
		}





	}

}
?>
