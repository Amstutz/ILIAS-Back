<?php
/**
 *
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$*
 */
class ilSkinStyleXML
{
    /**
     * @var string
     */
    protected $id = "";

    /**
     * @var string
     */
    protected $name = "";

    /**
     * @var string
     */
    protected $sound_directory = "";

    /**
     * @var string
     */
    protected $image_directory = "";

    /**
     * @var string
     */
    protected $font_directory = "";

    /**
     * @var string
     */
    protected $css_file = "";

    /**
     * @var string
     */
    protected $substyle_of = "";

    /**
     * ilSkinStyleXML constructor.
     * @param string $id
     * @param string $name
     */
    public function __construct($id, $name, $css_file = "", $image_directory = "", $font_directory = "", $sound_directory = "")
    {
        $this->setId($id);
        $this->setName($name);

        if($css_file == ""){
            $css_file = $this->getId();
        }

        if($image_directory == ""){
            $image_directory = "images";
        }

        if($font_directory == ""){
            $font_directory = "fonts";
        }

        if($sound_directory == ""){
            $sound_directory = "sound";
        }

        $this->setCssFile($css_file);
        $this->setImageDirectory($image_directory);
        $this->setFontDirectory($font_directory);
        $this->setSoundDirectory($sound_directory);
    }

    /**
     * @param SimpleXMLElement $xml_element
     * @return ilSkinStyleXML
     */
    static function parseFromXMLElement(SimpleXMLElement $xml_element){
        $style = new self((string)$xml_element->attributes()["id"],
            (string)$xml_element->attributes()["name"],
            (string)$xml_element->attributes()["css_file"],
            (string)$xml_element->attributes()["image_directory"],
            (string)$xml_element->attributes()["font_directory"],
            (string)$xml_element->attributes()["sound_directory"]

        );
        return $style;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @throws ilSystemStyleException
     */
    public function setId($id)
    {
        if (strpos($id, ' ') !== false) {
            throw new ilSystemStyleException(ilSystemStyleException::INVALID_CHARACTERS_IN_ID, $id);
        }
        $this->id = str_replace(" ","_",$id);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSoundDirectory()
    {
        return $this->sound_directory;
    }

    /**
     * @param string $sound_directory
     */
    public function setSoundDirectory($sound_directory)
    {
        $this->sound_directory = $sound_directory;
    }

    /**
     * @return string
     */
    public function getImageDirectory()
    {
        return $this->image_directory;
    }

    /**
     * @param string $image_directory
     */
    public function setImageDirectory($image_directory)
    {
        $this->image_directory = $image_directory;
    }

    /**
     * @return string
     */
    public function getCssFile()
    {
        return $this->css_file;
    }

    /**
     * @param string $css_file
     */
    public function setCssFile($css_file)
    {
        $this->css_file = $css_file;
    }

    /**
     * @return string
     */
    public function getFontDirectory()
    {
        return $this->font_directory;
    }

    /**
     * @param string $font_directory
     */
    public function setFontDirectory($font_directory)
    {
        $this->font_directory = $font_directory;
    }

    /**
     * @return string
     */
    public function getSubstyleOf()
    {
        return $this->substyle_of;
    }

    /**
     * @param string $substyle_of
     */
    public function setSubstyleOf($substyle_of)
    {
        $this->substyle_of = $substyle_of;
    }

    public function isSubstyle(){
        return $this->getSubstyleOf() != "";
    }
}