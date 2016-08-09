<?php
include_once("Services/Style/System/classes/Utilities/class.ilSkinXML.php");
include_once("Services/Style/System/classes/Utilities/class.ilSystemStyleSkinContainer.php");
include_once("Services/Style/System/classes/class.ilSystemStyleSettings.php");


/* Copyright (c) 1998-2012 ILIAS open source, Extended GPL, see docs/LICENSE */


/**
 * parses the template.xml that defines all styles of the current template
 *
 * @author Alex Killing <alex.killing@gmx.de>
 * @author Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version $Id$
 *
 */
class ilStyleDefinition
{
	/**
	 * @var string
	 */
	const DEFAULT_TEMPLATE_PATH = "./templates/default/template.xml";


	/**
	 * @var string
	 */
	const DELOS_PATH = "./templates/default/delos";

	/**
	 * @var string
	 */
	const DEFAULT_SKIN_ID = "default";

	/**
	 * @var string
	 */
	const DEFAULT_STYLE_ID = "delos";
	/**
	 * @var string
	 */
	const DEFAULT_VARIABLES_PATH = "./templates/default/less/variables.less";


	/**
	 * @var string
	 */
	const DEFAULT_IMAGES_PATH = "./templates/default/images/";

	/**
	 * @var string
	 */
	const DEFAULT_FONTS_PATH = "./templates/default/fonts/";

	/**
	 * @var string
	 */
	const DEFAULT_SOUNDS_PATH = "";

	/**
	 * string
	 */
	const CUSTOMIZING_SKINS_PATH = "./Customizing/global/skin/";

	/**
	 * currently selected style
	 * @var ilSkinStyleXML
	 */
	static $current_style;

	/**
	 * @var ilSkinXML[]
	 */
	static $skins = array();

	/**
	 * @var ilSkinXML
	 */
	protected $skin;

	/**
	 * @var array|null
	 */
	protected static $cached_all_styles_information = null;


	/**
	 * ilStyleDefinition constructor.
	 * @param string $skin_id
	 */
	function __construct($skin_id = "")
	{
		if($skin_id == ""){
			$skin_id = self::getCurrentSkin();
		}

		if ($skin_id != self::DEFAULT_SKIN_ID)
		{
			$this->setSkin(ilSkinXML::parseFromXML(self::CUSTOMIZING_SKINS_PATH.$skin_id."/template.xml"));

		}else{
			$this->setSkin(ilSkinXML::parseFromXML(self::DEFAULT_TEMPLATE_PATH));
		}
	}

	/**
	 * get the current skin
	 *
	 * use always this function instead of getting the account's skin
	 * the current skin may be changed on the fly by setCurrentSkin()
	 *
	 * @return	string|null	skin id
	 */
	public static function getCurrentSkin()
	{
		/**
		 * @var $ilias ILIAS
		 */
		global $DIC,$ilias;

		if (is_object($ilias)) {
			$skin_id = $ilias->account->skin;
			if(!self::skinExists($skin_id)){
				ilUtil::sendFailure($DIC->language()->txt("skin_does_not_exit").$skin_id);
				$skin_id = self::DEFAULT_SKIN_ID;
			}
			return $skin_id;
		}else{
			return null;
		}


	}


	public function getStyles()
	{
		return $this->getSkin()->getStyles();
	}


	public function getTemplateName()
	{
		return $this->getSkin()->getName();
	}


	public function getStyle($a_id)
	{
		return $this->getSkin()->getStyle($a_id);
	}


	public function getStyleName($a_id)
	{
		return $this->getSkin()->getStyle($a_id)->getName();
	}


	public function getImageDirectory($style_id)
	{
		if(!$this->getSkin()->getStyle($style_id)){
			throw new ilSystemStyleException(ilSystemStyleException::NO_STYLE_ID,$style_id);
		}
		return $this->getSkin()->getStyle($style_id)->getImageDirectory();
	}

	public function getSoundDirectory($style_id)
	{
		return $this->getSkin()->getStyle($style_id)->getSoundDirectory();
	}


	public static function getAllSkins(){
		if(!self::$skins){
			/**
			 * @var $skins ilSkinXML[]
			 */
			$skins = array();
			$skins[self::DEFAULT_SKIN_ID] = ilSkinXML::parseFromXML(self::DEFAULT_TEMPLATE_PATH);


			if(is_dir(self::CUSTOMIZING_SKINS_PATH)){
				$cust_skins_directory = new RecursiveDirectoryIterator(self::CUSTOMIZING_SKINS_PATH,FilesystemIterator::SKIP_DOTS);
				foreach ($cust_skins_directory as $skin_folder) {
					if($skin_folder->isDir()){
						$template_path = $skin_folder->getRealPath()."/template.xml";
						if (file_exists($template_path))
						{
							$skin = ilSkinXML::parseFromXML($template_path);
							$skins[$skin->getId()] = $skin;
						}
					}
				}
			}

			self::setSkins($skins);
		}

		return self::$skins;
	}

	/**
	 * @deprecated
	 * @return ilSkinXML[]
	 */
	public static function getAllTemplates(){
		return self::getAllSkins();
	}


	/**
	* Check whether a skin exists. Not using array_key_exists($skin_id,self::getAllSkins()); for performance reasons
	*
	* @param	string	$skin		skin id
	*
	* @return	boolean
	*/
	public static function skinExists($skin_id)
	{
		if ($skin_id == self::DEFAULT_SKIN_ID)
		{
			if (is_file(self::DEFAULT_TEMPLATE_PATH))
			{
				return true;
			}
		}
		else
		{
			if (is_file(self::CUSTOMIZING_SKINS_PATH.$skin_id."/template.xml"))
			{
				return true;
			}
		}
		return false;
	}
	
	/**
	 * get the current style
	 *
	 * use always this function instead of getting the account's style
	 * the current style may be changed on the fly by setCurrentStyle()

	 * @return	string|null	style id
	 */
	public static function getCurrentStyle()
	{
		global $DIC;

		/**
		 * @var ilStyleDefinition $styleDefinition
		 */
		global $ilias, $styleDefinition, $tree;
		
		if (isset(self::$current_style))
		{
			return self::$current_style;
		}

		if(!is_object($ilias))
		{
			return null;
		}

		self::setCurrentStyle($ilias->account->prefs['style']);

		if (is_object($styleDefinition))
		{
			// Todo Fix this for suubstyles
			if ($styleDefinition->getSkin()->hasStyleSubstyles(self::$current_style))
			{
				// read assignments, if given
				$assignments = ilSystemStyleSettings::getSystemStyleCategoryAssignments(self::getCurrentSkin(), self::$current_style);
				if (count($assignments) > 0)
				{
					$ref_ass = array();
					foreach ($assignments as $a)
					{
						$ref_ass[$a["ref_id"]] = $a["substyle"];
					}

					$ref_id = false;
					if($_GET["ref_id"]){
						$ref_id = $_GET["ref_id"];
					}else if($_GET["target"]){
						$target_arr = explode("_",$_GET["target"] );
						$ref_id = $target_arr[1];
					}

					// check whether any ref id assigns a new style
					if (is_object($tree) && $ref_id  && $tree->isInTree($ref_id))
					{

						$path = $tree->getPathId($ref_id);
						for ($i = count($path) - 1; $i >= 0; $i--)
						{
							if (isset($ref_ass[$path[$i]]))
							{
								self::$current_style = $ref_ass[$path[$i]];
								return self::$current_style;
							}
						}
					}
				}
			}
		}

		if(!self::styleExistsForCurrentSkin(self::$current_style)){
			ilUtil::sendFailure($DIC->language()->txt("style_does_not_exit").self::$current_style);
			self::setCurrentSkin(self::DEFAULT_SKIN_ID);
			self::setCurrentStyle(self::DEFAULT_STYLE_ID);
		}
		return self::$current_style;
	}

	/**
	 * Get all skins/styles
	 *
	 * @param
	 * @return
	 */
	public static function getAllSkinStyles()
	{
		/**
		 * @var ilStyleDefinition $styleDefinition
		 */
		global $styleDefinition;



		if(!self::getCachedAllStylesInformation()){
			$all_styles = array();

			$skins = $styleDefinition->getSkins();

			foreach ($skins as $skin)
			{
				foreach ($skin->getStyles() as $style)
				{
					$num_users = ilObjUser::_getNumberOfUsersForStyle($skin->getId(), $style->getId());

					// default selection list
					$all_styles[$skin->getId().":".$style->getId()] =
							array (
									"title" => $skin->getId()." / ".$style->getId(),
									"id" => $skin->getId().":". $style->getId(),
									"template_id" => $skin->getId(),
									"skin_id" => $skin->getId(),
									"style_id" =>  $style->getId(),
									"template_name" => $skin->getName(),
									"substyle_of" => $style->getSubstyleOf(),
									"style_name" => $style->getName(),
									"users" => $num_users
							);
				}
			}
			self::setCachedAllStylesInformation($all_styles);

		}

		return self::getCachedAllStylesInformation();
	}


	/**
	 * @param $a_skin
	 */
	public static function setCurrentSkin($a_skin)
	{
		/**
		 * @var ilStyleDefinition $styleDefinition
		 */
		global $styleDefinition;

		if (is_object($styleDefinition) && $styleDefinition->getSkin()->getName() != $a_skin)
		{
			$styleDefinition = new ilStyleDefinition($a_skin);
		}
	}



	public static function styleExists($style_id){
		foreach(self::getSkins() as $skin)
		{
			if($skin->hasStyle($style_id)){
				return true;
			}
		}
		return false;
	}

	public static function styleExistsForSkinId($skin_id,$style_id){
		if(!self::skinExists($skin_id)){
			return false;
		}
		$skin = ilSystemStyleSkinContainer::generateFromId($skin_id)->getSkin();
		return $skin->hasStyle($style_id);
	}

	public static function styleExistsForCurrentSkin($style_id){
		/**
		 * @var ilStyleDefinition $styleDefinition
		 */
		global $styleDefinition;

		return $styleDefinition->getSkin()->hasStyle($style_id);
	}

	/**
	 * @param $a_style
	 */
	public static function setCurrentStyle($a_style)
	{
		self::$current_style = $a_style;
	}

	/**
	 * @return ilSkinXML[]
	 */
	public static function getSkins()
	{
		return self::getAllSkins();
	}

	/**
	 * @param ilSkinXML[] $skins
	 */
	public static function setSkins($skins)
	{
		self::$skins = $skins;
	}

	/**
	 * @return ilSkinXML
	 */
	public function getSkin()
	{
		return $this->skin;
	}

	/**
	 * @param ilSkinXML $skin
	 */
	public function setSkin($skin)
	{
		$this->skin = $skin;
	}

	/**
	 * @return array|null
	 */
	protected static function getCachedAllStylesInformation()
	{
		return self::$cached_all_styles_information;
	}

	/**
	 * @param array|null $cached_all_styles_information
	 */
	protected static function setCachedAllStylesInformation($cached_all_styles_information)
	{
		self::$cached_all_styles_information = $cached_all_styles_information;
	}


}