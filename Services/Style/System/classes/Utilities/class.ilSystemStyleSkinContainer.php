<?php
include_once("Services/Style/System/classes/Utilities/class.ilSkinStyleXML.php");
include_once("Services/Style/System/classes/class.ilStyleDefinition.php");

/**
 *
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$*
 */
class ilSystemStyleSkinContainer {

    /**
     * @var ilSkinXML
     */
    protected $skin;

    /**
     * @var
     */
    protected $customizing_skin_directory;

    /**
     * ilSystemStyleSkinContainer constructor.
     * @param ilSkinXML $skin
     */
    public function __construct(ilSkinXML $skin)
    {
        $this->skin = $skin;
        $this->customizing_skin_directory = ilStyleDefinition::CUSTOMIZING_SKINS_PATH.$this->getSkin()->getId()."/";

    }

    public function create(){
        mkdir($this->getCustomizingSkinDirectory());
        $this->getSkin()->writeToXMLFile($this->getCustomizingSkinDirectory()."template.xml");
        foreach($this->getSkin()->getStyles() as $style){
            mkdir($this->getCustomizingSkinDirectory().$style->getImageDirectory());
            mkdir($this->getCustomizingSkinDirectory().$style->getFontDirectory());
            mkdir($this->getCustomizingSkinDirectory().$style->getSoundDirectory());
            touch($this->getCustomizingSkinDirectory().$style->getCssFile().".css");
            //Todo copy less structure
            //Todo copy icons
        }

    }

    public function update(ilSkinXML $old_skin, $old_style_id){
        $old_customizing_skin_directory = ilStyleDefinition::CUSTOMIZING_SKINS_PATH.$old_skin->getId()."/";
        $old_style = $old_skin->getStyle($old_style_id);

        unlink($old_customizing_skin_directory."template.xml");
        mkdir($this->getCustomizingSkinDirectory());
        $this->getSkin()->writeToXMLFile($this->getCustomizingSkinDirectory()."template.xml");
        foreach($this->getSkin()->getStyles() as $style){

            rename($old_customizing_skin_directory.$old_style->getImageDirectory(), $this->getCustomizingSkinDirectory().$style->getImageDirectory());
            rename($old_customizing_skin_directory.$old_style->getFontDirectory(), $this->getCustomizingSkinDirectory().$style->getFontDirectory());
            rename($old_customizing_skin_directory.$old_style->getSoundDirectory(), $this->getCustomizingSkinDirectory().$style->getSoundDirectory());
            rename($old_customizing_skin_directory.$old_style->getCssFile().".css", $this->getCustomizingSkinDirectory().$style->getCssFile().".css");
        }
        rmdir($old_customizing_skin_directory);

    }

    public function move(){
        //Todo
    }

    public function delete(){
        //Todo
    }

    public function copy(){
        //Todo
    }

    public function export(){
        //Todo
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
     * @return mixed
     */
    public function getCustomizingSkinDirectory()
    {
        return $this->customizing_skin_directory;
    }

    /**
     * @param mixed $customizing_skin_directory
     */
    public function setCustomizingSkinDirectory($customizing_skin_directory)
    {
        $this->customizing_skin_directory = $customizing_skin_directory;
    }


}