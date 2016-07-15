<?php

/* Copyright (c) 1998-2014 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 *  
 *
 * @author Alex Killing <alex.killing@gmx.de>
 * @version $Id$
 * @ingroup ServicesStyle
 *
 */
class ilSystemStyleSettings
{
	/**
	 * SYSTEM
	 * lookup if a style is activated
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
	 * * SYSTEM
	 * deactivate style
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
	 * * SYSTEM
	 * activate style
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

		$assignmnts = array();
		$set = $ilDB->query("SELECT substyle, category_ref_id FROM syst_style_cat ".
				" WHERE skin_id = ".$ilDB->quote($a_skin_id, "text").
				" AND style_id = ".$ilDB->quote($a_style_id, "text")
		);
		while ($rec = $ilDB->fetchAssoc($set))
		{
			$assignmnts[] = array("substyle" => $rec["substyle"],
					"ref_id" => $rec["category_ref_id"]);
		}
		return $assignmnts;
	}

	/**
	 * @param $a_skin_id
	 * @param $a_style_id
	 * @param $a_substyle
	 * @param $a_ref_id
	 */
	static function writeSystemStyleCategoryAssignment($a_skin_id, $a_style_id,
													   $a_substyle, $a_ref_id)
	{
		global $ilDB;

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

}

?>