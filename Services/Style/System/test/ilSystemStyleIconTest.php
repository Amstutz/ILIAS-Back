<?php

include_once("Services/Style/System/classes/Utilities/class.ilSkinStyleXML.php");
include_once("Services/Style/System/classes/Utilities/class.ilSkinXML.php");
include_once("Services/Style/System/classes/Utilities/class.ilSystemStyleSkinContainer.php");
include_once("Services/Style/System/test/fixtures/mocks/ilSystemStyleConfigMock.php");
include_once("Services/Style/System/test/fixtures/mocks/DICMock.php");

include_once("Services/Style/System/classes/Icons/class.ilSystemStyleIcon.php");


/**
 *
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$*
 */
class ilSystemStyleIconTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var ilSystemStyleConfigMock
	 */
	protected $system_style_config;

	/**
	 * @var ilSystemStyleSkinContainer
	 */
	protected $container;

	/**
	 * @var ilSkinStyleXML
	 */
	protected $style;

	/**
	 * @var string
	 */
	protected $icon_name = "test_image_1.svg";

	/**
	 * @var string
	 */
	protected $icon_type = "svg";

	protected function setUp(){
		global $DIC;

		$DIC = new DICMock();

		$this->system_style_config = new ilSystemStyleConfigMock();

		mkdir($this->system_style_config->test_skin_temp_path);
		ilSystemStyleSkinContainer::xCopy($this->system_style_config->test_skin_original_path,$this->system_style_config->test_skin_temp_path);

		$this->container = ilSystemStyleSkinContainer::generateFromId("skin1",null,$this->system_style_config);
		$this->style = $this->container->getSkin()->getStyle("style1");
	}

	protected function tearDown(){
		ilSystemStyleSkinContainer::recursiveRemoveDir($this->system_style_config->test_skin_temp_path);
	}

	public function testConstruct() {
		$path = $this->container->getImagesSkinPath($this->style->getId())."/".$this->icon_name;
		$icon = new ilSystemStyleIcon($this->icon_name,$path,$this->icon_type);

		$this->assertEquals($icon->getName(),$this->icon_name);
		$this->assertEquals($icon->getPath(),$path);
		$this->assertEquals($icon->getType(),$this->icon_type);
	}

	public function testGetColorSetSorted(){
		$path = $this->container->getImagesSkinPath($this->style->getId())."/".$this->icon_name;
		$icon = new ilSystemStyleIcon($this->icon_name,$path,$this->icon_type);

		var_dump($icon->getColorSet()->getColorsSorted());
	}
}
