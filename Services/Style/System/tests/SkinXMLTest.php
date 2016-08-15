<?php
/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

include_once("Services/Style/System/classes/Utilities/class.ilSkinStyleXML.php");
include_once("Services/Style/System/classes/Utilities/class.ilSkinXML.php");

/**
 *
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$*
 */
class SkinXMLTest extends PHPUnit_Framework_TestCase {


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
     * @var string
     */
    protected $skins_path = "Services/Style/System/tests/fixtures/skins/";

    protected function setUp(){
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

    }

    public function testSkinNameAndId() {
        $this->assertEquals("skin1", $this->skin->getId());
        $this->assertEquals("skin 1", $this->skin->getName());

    }

    public function testAddStyle() {
        $this->assertEquals(count($this->skin),0);
        $this->assertEquals(count($this->skin->getStyles()),0);
        $this->skin->addStyle($this->style1);
        $this->assertEquals(count($this->skin),1);
        $this->assertEquals(count($this->skin->getStyles()),1);
        $this->skin->addStyle($this->style1);
        $this->assertEquals(count($this->skin),1);
        $this->assertEquals(count($this->skin->getStyles()),1);
        $this->skin->addStyle($this->style2);
        $this->assertEquals(count($this->skin),2);
        $this->assertEquals(count($this->skin->getStyles()),2);
    }

    public function testGetStyles() {
        $this->skin->addStyle($this->style1);
        $this->skin->addStyle($this->style2);

        $this->assertNotEquals($this->skin->getStyle("style2"),$this->style1);
        $this->assertEquals($this->skin->getStyle("style2"),$this->style2);
    }

    public function testRemoveStyles() {
        $this->skin->addStyle($this->style1);
        $this->skin->addStyle($this->style2);
        $this->assertEquals(count($this->skin),2);
        $this->skin->removeStyle("style1");
        $this->assertEquals(count($this->skin),1);
        $this->skin->removeStyle("style2");
        $this->assertEquals(count($this->skin),0);
        $this->skin->removeStyle("style2");
        $this->assertEquals(count($this->skin),0);
    }

    public function testAsXML() {
        $this->skin->addStyle($this->style1);
        $this->skin->addStyle($this->style2);
        $this->assertEquals($this->skin->asXML(),file_get_contents($this->skins_path."/skin1/skin1.xml"));
    }

    public function testWriteXML() {
        $this->skin->addStyle($this->style1);
        $this->skin->addStyle($this->style2);
        $this->skin->writeToXMLFile($this->skins_path."/skin1/skin1-copy.xml");
        $this->assertEquals(file_get_contents($this->skins_path."/skin1/skin1-copy.xml")
            ,file_get_contents($this->skins_path."/skin1/skin1.xml"));
        unlink ($this->skins_path."/skin1/skin1-copy.xml");
    }

    public function testReadXML() {
        $skin = ilSkinXML::parseFromXML($this->skins_path."/skin1/skin1.xml");
        $this->assertEquals($skin->asXML(),file_get_contents($this->skins_path."/skin1/skin1.xml"));
    }
}
