<?php

include_once("Services/Style/System/classes/Utilities/class.ilSkinStyleXML.php");
include_once("Services/Style/System/classes/Utilities/class.ilSkinXML.php");
include_once("Services/Style/System/classes/Utilities/class.ilSystemStyleSkinContainer.php");
include_once("Services/Style/System/tests/fixtures/mocks/ilSystemStyleConfigMock.php");
include_once("Services/Style/System/tests/fixtures/mocks/DICMock.php");

include_once("Services/Style/System/classes/Utilities/class.ilSystemStyleMessageStack.php");

/**
 *
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$*
 */
class SystemStyleSkinContainerTest extends PHPUnit_Framework_TestCase {


	/**
	 * @var ilSkinXML
	 */
	protected $skin;

	/**
	 * @var ilSkinStyleXML
	 */
	protected $style1 = null;

	/**
	 * @var ilSkinStyleXML
	 */
	protected $style2 = null;

	/**
	 * @var ilSystemStyleConfigMock
	 */
	protected $system_style_config;

	protected function setUp(){
		global $DIC;

		$DIC = new DICMock();

		$this->skin = new ilSkinXML("skin1", "skin 1");

		$this->style1 = new ilSkinStyleXML("style1", "Style 1");
		$this->style1->setCssFile("style1css");
		$this->style1->setImageDirectory("style1image");
		$this->style1->setSoundDirectory("style1sound");
		$this->style1->setFontDirectory("style1font");

		$this->style2 = new ilSkinStyleXML("style2", "Style 2");
		$this->style2->setCssFile("style2css");
		$this->style2->setImageDirectory("style2image");
		$this->style2->setSoundDirectory("style2sound");
		$this->style2->setFontDirectory("style2font");

		$this->system_style_config = new ilSystemStyleConfigMock();
	}

	public function testGenerateFromId() {
		$container = ilSystemStyleSkinContainer::generateFromId($this->skin->getId(),null,$this->system_style_config);
		$this->assertEquals($container->getSkin()->getId(), $this->skin->getId());
		$this->assertEquals($container->getSkin()->getName(), $this->skin->getName());

		$this->assertEquals($container->getSkin()->getStyle($this->style1->getId()), $this->style1);
		$this->assertEquals($container->getSkin()->getStyle($this->style2->getId()), $this->style2);
	}

	public function testCreateDelete(){
		$container = ilSystemStyleSkinContainer::generateFromId($this->skin->getId(),null,$this->system_style_config);

		$container->getSkin()->setId("newSkin");
		$container->create(new ilSystemStyleMessageStack());

		$this->assertTrue(is_dir($this->system_style_config->getCustomizingSkinPath()."newSkin"));
		$container->delete();
		$this->assertFalse(is_dir($this->system_style_config->getCustomizingSkinPath()."newSkin"));
	}

	public function testUpdateSkin(){
		$container = ilSystemStyleSkinContainer::generateFromId($this->skin->getId(),null,$this->system_style_config);
		$old_skin = clone $container->getSkin();
		$container->getSkin()->setId("newSkin2");
		$container->updateSkin($old_skin);
		$this->assertTrue(is_dir($this->system_style_config->getCustomizingSkinPath()."newSkin2"));
		$old_skin = clone $container->getSkin();
		$container->getSkin()->setId($this->skin->getId());
		$container->updateSkin($old_skin);
		$this->assertFalse(is_dir($this->system_style_config->getCustomizingSkinPath()."newSkin2"));
	}

	public function testUpdateStyles(){
		$container = ilSystemStyleSkinContainer::generateFromId($this->skin->getId(),null,$this->system_style_config);
		$skin =  $container->getSkin();

		$old_style = clone $skin->getStyle($this->style1->getId());
		$new_style = $skin->getStyle($this->style1->getId());

		$new_style->setId("style1new");
		$new_style->setName("new Style");
		$new_style->setImageDirectory("style1newimage");
		$new_style->setSoundDirectory("style1newsound");
		$new_style->setFontDirectory("style1newfont");

		$container->updateSkin($skin);
		$container->updateStyle($new_style->getId(),$old_style);

		$this->assertFalse(is_dir($this->system_style_config->getCustomizingSkinPath().$this->skin->getId()."/style1image"));
		$this->assertFalse(is_dir($this->system_style_config->getCustomizingSkinPath().$this->skin->getId()."/style1sound"));
		$this->assertFalse(is_dir($this->system_style_config->getCustomizingSkinPath().$this->skin->getId()."/style1font"));
		$this->assertFalse(is_dir($this->system_style_config->getCustomizingSkinPath().$this->skin->getId()."/style1font"));
		$this->assertFalse(is_file($this->system_style_config->getCustomizingSkinPath().$this->skin->getId()."/style1css.css"));
		$this->assertFalse(is_file($this->system_style_config->getCustomizingSkinPath().$this->skin->getId()."/style1css.less"));
		$this->assertFalse(is_file($this->system_style_config->getCustomizingSkinPath().$this->skin->getId()."/style1css-variables.less"));

		$this->assertTrue(is_dir($this->system_style_config->getCustomizingSkinPath().$this->skin->getId()."/style1newimage"));
		$this->assertTrue(is_dir($this->system_style_config->getCustomizingSkinPath().$this->skin->getId()."/style1newsound"));
		$this->assertTrue(is_dir($this->system_style_config->getCustomizingSkinPath().$this->skin->getId()."/style1newfont"));
		$this->assertTrue(is_dir($this->system_style_config->getCustomizingSkinPath().$this->skin->getId()."/style1newfont"));
		$this->assertTrue(is_file($this->system_style_config->getCustomizingSkinPath().$this->skin->getId()."/style1newcss.css"));
		$this->assertTrue(is_file($this->system_style_config->getCustomizingSkinPath().$this->skin->getId()."/style1newcss.less"));
		$this->assertTrue(is_file($this->system_style_config->getCustomizingSkinPath().$this->skin->getId()."/style1newcss-variables.less"));

		$container->deleteStyle($new_style);
		$this->assertFalse(is_dir($this->system_style_config->getCustomizingSkinPath().$this->skin->getId()."/style1newimage"));
		$this->assertFalse(is_dir($this->system_style_config->getCustomizingSkinPath().$this->skin->getId()."/style1newsound"));
		$this->assertFalse(is_dir($this->system_style_config->getCustomizingSkinPath().$this->skin->getId()."/style1newfont"));
		$this->assertFalse(is_dir($this->system_style_config->getCustomizingSkinPath().$this->skin->getId()."/style1newfont"));
		$this->assertFalse(is_file($this->system_style_config->getCustomizingSkinPath().$this->skin->getId()."/style1newcss.css"));
		$this->assertFalse(is_file($this->system_style_config->getCustomizingSkinPath().$this->skin->getId()."/style1newcss.less"));
		$this->assertFalse(is_file($this->system_style_config->getCustomizingSkinPath().$this->skin->getId()."/style1newcss-variables.less"));

		$container->addStyle($old_style);
		$this->assertTrue(is_dir($this->system_style_config->getCustomizingSkinPath().$this->skin->getId()."/style1image"));
		$this->assertTrue(is_dir($this->system_style_config->getCustomizingSkinPath().$this->skin->getId()."/style1sound"));
		$this->assertTrue(is_dir($this->system_style_config->getCustomizingSkinPath().$this->skin->getId()."/style1font"));
		$this->assertTrue(is_dir($this->system_style_config->getCustomizingSkinPath().$this->skin->getId()."/style1font"));
		$this->assertTrue(is_file($this->system_style_config->getCustomizingSkinPath().$this->skin->getId()."/style1css.css"));
		$this->assertTrue(is_file($this->system_style_config->getCustomizingSkinPath().$this->skin->getId()."/style1css.less"));
		$this->assertTrue(is_file($this->system_style_config->getCustomizingSkinPath().$this->skin->getId()."/style1css-variables.less"));
	}
}
