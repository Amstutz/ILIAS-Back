<?php
include_once("Services/Style/System/classes/Utilities/class.ilSkinStyleXML.php");
include_once("Services/Style/System/classes/Utilities/class.ilSkinXML.php");
include_once("Services/Style/System/classes/Utilities/class.ilSystemStyleSkinContainer.php");
include_once("./Services/Style/System/classes/Less/class.ilSystemStyleLessFile.php");
include_once("Services/Style/System/test/fixtures/mocks/ilSystemStyleConfigMock.php");
include_once("Services/Style/System/test/fixtures/mocks/DICMock.php");

/**
 *
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$*
 */
class ilSkinStyleLessVariableTest extends PHPUnit_Framework_TestCase {


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

    protected function setUp()
    {
        global $DIC;

        $DIC = new DICMock();

        $this->system_style_config = new ilSystemStyleConfigMock();

        mkdir($this->system_style_config->test_skin_temp_path);
        ilSystemStyleSkinContainer::xCopy($this->system_style_config->test_skin_original_path, $this->system_style_config->test_skin_temp_path);

        $this->container = ilSystemStyleSkinContainer::generateFromId("skin1", null, $this->system_style_config);
        $this->style = $this->container->getSkin()->getStyle("style1");
    }

    protected function tearDown()
    {
        ilSystemStyleSkinContainer::recursiveRemoveDir($this->system_style_config->test_skin_temp_path);
    }

    public function testConstructAndRead() {
        $file = new ilSystemStyleLessFile($this->container->getLessVariablesFilePath($this->style->getId()));
        $this->assertEquals(16,count($file->getItems()));
    }

    public function testReadCorrectTypes() {
        $file = new ilSystemStyleLessFile($this->container->getLessVariablesFilePath($this->style->getId()));

        $this->assertEquals(2,count($file->getCategories()));
        $this->assertEquals(6,count($file->getVariablesIds()));
        $this->assertEquals(8,count($file->getCommentsIds()));
    }


    public function testGetVariableByName(){
        $file = new ilSystemStyleLessFile($this->container->getLessVariablesFilePath($this->style->getId()));

        $expected_variable11 = new ilSystemStyleLessVariable("variable11", "value11", "comment variable 11","Category 1", []);
        $expected_variable12 = new ilSystemStyleLessVariable("variable12", "value12", "comment variable 12","Category 1", []);
        $expected_variable13 = new ilSystemStyleLessVariable("variable13", "@variable11", "comment variable 13","Category 1", ["variable11"]);

        $expected_variable21 = new ilSystemStyleLessVariable("variable21", "@variable11", "comment variable 21","Category 2", ["variable11"]);
        $expected_variable22 = new ilSystemStyleLessVariable("variable22", "value21", "comment variable 22","Category 2", []);
        $expected_variable23 = new ilSystemStyleLessVariable("variable23", "@variable21", "comment variable 23","Category 2", ["variable21"]);

        $this->assertEquals($expected_variable11,$file->getVariableByName("variable11"));
        $this->assertEquals($expected_variable12,$file->getVariableByName("variable12"));
        $this->assertEquals($expected_variable13,$file->getVariableByName("variable13"));

        $this->assertEquals($expected_variable21,$file->getVariableByName("variable21"));
        $this->assertEquals($expected_variable22,$file->getVariableByName("variable22"));
        $this->assertEquals($expected_variable23,$file->getVariableByName("variable23"));
    }
}
