<?php

/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 *  
 *
 * @author Alex Killing <alex.killing@gmx.de>
 * @author Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 *
 * @version $Id$
 * @ingroup ServicesStyle
 *
 */
class ilSystemStyleSettings
{
	/**
	 * SYSTEM
	 * lookup if a style is activated
	 *
	 * @param $a_skin
	 * @param $a_style
	 * @return bool
	 */
	static function _lookupActivatedStyle($a_skin, $a_style)
	{
		global $ilDB;

		$q = "SELECT count(*) cnt FROM settings_deactivated_s".
			" WHERE skin = ".$ilDB->quote($a_skin, "text").
			" AND style = ".$ilDB->quote($a_style, "text")." ";

		$cnt_set = $ilDB->query($q);
		$cnt_rec = $ilDB->fetchAssoc($cnt_set);

		if ($cnt_rec["cnt"] > 0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	/**
	 * deactivate style
	 *
	 * @param $a_skin
	 * @param $a_style
	 */
	static function _deactivateStyle($a_skin, $a_style)
	{
		global $ilDB;

		ilSystemStyleSettings::_activateStyle($a_skin, $a_style);
		$q = "INSERT into settings_deactivated_s".
			" (skin, style) VALUES ".
			" (".$ilDB->quote($a_skin, "text").",".
			" ".$ilDB->quote($a_style, "text").")";

		$ilDB->manipulate($q);
	}

	/**
	 * activate style
	 *
	 * @param $a_skin
	 * @param $a_style
	 */
	static function _activateStyle($a_skin, $a_style)
	{
		global $ilDB;

		$q = "DELETE FROM settings_deactivated_s".
			" WHERE skin = ".$ilDB->quote($a_skin, "text").
			" AND style = ".$ilDB->quote($a_style, "text");

		$ilDB->manipulate($q);
	}

	/**
	 * Get all system style category assignments
	 *
	 * @param string $a_skin_id skin id
	 * @param string $a_style_id style id
	 * @return array ref ids
	 */
	static function getSystemStyleCategoryAssignments($a_skin_id, $a_style_id)
	{
		global $ilDB;

		$assignments = [];
		$set = $ilDB->query("SELECT substyle, category_ref_id FROM syst_style_cat ".
				" WHERE skin_id = ".$ilDB->quote($a_skin_id, "text").
				" AND style_id = ".$ilDB->quote($a_style_id, "text")
		);
		while (($rec = $ilDB->fetchAssoc($set)))
		{
			$assignments[] = [
					"substyle" => $rec["substyle"],
					"ref_id" => $rec["category_ref_id"]
			];
		}
		return $assignments;
	}

	/**
	 * @param $a_skin_id
	 * @param $a_style_id
	 * @param $a_sub_style_id
	 * @return array
	 */
	static function getSubStyleCategoryAssignments($a_skin_id, $a_style_id, $a_sub_style_id)
	{
		global $ilDB;

		$assignmnts = [];

		$set = $ilDB->query("SELECT substyle, category_ref_id FROM syst_style_cat ".
				" WHERE skin_id = ".$ilDB->quote($a_skin_id, "text").
				" AND substyle = ".$ilDB->quote($a_sub_style_id, "text").
				" AND style_id = ".$ilDB->quote($a_style_id, "text")
		);
		while (($rec = $ilDB->fetchAssoc($set)))
		{
			$assignmnts[] = [
					"substyle" => $rec["substyle"],
					"ref_id" => $rec["category_ref_id"]
			];
		}
		return $assignmnts;
	}

	/**
	 * @param $a_skin_id
	 * @param $a_style_id
	 * @param $a_substyle
	 * @param $a_ref_id
	 * @throws ilSystemStyleException
	 */
	static function writeSystemStyleCategoryAssignment($a_skin_id, $a_style_id,
													   $a_substyle, $a_ref_id)
	{
		global $ilDB;

		$assignments = self::getSubStyleCategoryAssignments($a_skin_id, $a_style_id,$a_substyle);

		foreach($assignments as $assignment){
			if($assignment["ref_id"] == $a_ref_id){
				throw new ilSystemStyleException(ilSystemStyleException::SUBSTYLE_ASSIGNMENT_EXISTS,$a_substyle. ": ".$a_ref_id);
			}
		}
		$ilDB->manipulate("INSERT INTO syst_style_cat ".
				"(skin_id, style_id, substyle, category_ref_id) VALUES (".
				$ilDB->quote($a_skin_id, "text").",".
				$ilDB->quote($a_style_id, "text").",".
				$ilDB->quote($a_substyle, "text").",".
				$ilDB->quote($a_ref_id, "integer").
				")");
	}

	/**
	 * @param $a_skin_id
	 * @param $a_style_id
	 * @param $a_substyle
	 * @param $a_ref_id
	 */
	static function deleteSystemStyleCategoryAssignment($a_skin_id, $a_style_id,
														$a_substyle, $a_ref_id)
	{
		global $ilDB;

		$ilDB->manipulate("DELETE FROM syst_style_cat WHERE ".
				" skin_id = ".$ilDB->quote($a_skin_id, "text").
				" AND style_id = ".$ilDB->quote($a_style_id, "text").
				" AND substyle = ".$ilDB->quote($a_substyle, "text").
				" AND category_ref_id = ".$ilDB->quote($a_ref_id, "integer"));
	}

	/**
	 * @param $a_skin_id
	 * @param $a_style_id
	 * @param $a_substyle
	 */
	static function deleteSubStyleCategoryAssignments($a_skin_id, $a_style_id, $a_substyle)
	{
		global $ilDB;

		$ilDB->manipulate("DELETE FROM syst_style_cat WHERE ".
				" skin_id = ".$ilDB->quote($a_skin_id, "text").
				" AND style_id = ".$ilDB->quote($a_style_id, "text").
				" AND substyle = ".$ilDB->quote($a_substyle, "text"));
	}

	/**
	 * @param $skin_id
	 * @param $style_id
	 */
	static function setCurrentUserPrefStyle($skin_id, $style_id){
		global $DIC;

		$DIC->user()->setPref("skin",$skin_id);
		$DIC->user()->setPref("style",$style_id);
		$DIC->user()->update();
	}

	/**
	 * @return bool
	 */
	static function getCurrentUserPrefSkin(){
		global $DIC;

		return $DIC->user()->getPref("skin");
	}

	/**
	 * @return bool
	 */
	static function getCurrentUserPrefStyle(){
		global $DIC;

		return $DIC->user()->getPref("style");
	}

	/**
	 * @param $skin_id
	 * @param $style_id
	 */
	static function setCurrentDefaultStyle($skin_id, $style_id){
		global $DIC;

		$DIC['ilias']->ini->setVariable("layout","skin", $skin_id);
		$DIC['ilias']->ini->setVariable("layout","style",$style_id);
		$DIC['ilias']->ini->write();
		self::_activateStyle($skin_id, $style_id);

	}

	static function resetDefaultToDelos(){
		self::setCurrentDefaultStyle(ilStyleDefinition::DEFAULT_SKIN_ID,ilStyleDefinition::DEFAULT_STYLE_ID);
	}

	/**
	 * @return string
	 */
	static function getCurrentDefaultSkin(){
		global $DIC;
		$skin_id = $DIC['ilias']->ini->readVariable("layout","skin");

		if(!ilStyleDefinition::skinExists($skin_id)){
			self::resetDefaultToDelos();
			$skin_id = $DIC['ilias']->ini->readVariable("layout","skin");
		}
		return $skin_id;
	}

	/**
	 * @return string
	 */
	static function getCurrentDefaultStyle(){
		global $DIC;
		$skin_id = $DIC['ilias']->ini->readVariable("layout","skin");
		$style_id = $DIC['ilias']->ini->readVariable("layout","style");

		if(!ilStyleDefinition::styleExistsForSkinId($skin_id,$style_id)){
			self::resetDefaultToDelos();
			$style_id = $DIC['ilias']->ini->readVariable("layout","style");
		}
		return $style_id;
	}
}