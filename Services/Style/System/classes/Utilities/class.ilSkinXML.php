<?php
include_once("Services/Style/System/classes/Utilities/class.ilSkinStyleXML.php");

/**
 *
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$*
 */
class ilSkinXML implements \Iterator, \Countable{

    /**
     * @var string
     */
    protected $id = "";
    /**
     * @var string
     */
    protected $name = "";

    /**
     * @var ilSkinStyleXML[]
     */
    protected $styles = array();


    /**
     * ilSkinXML constructor.
     * @param string $name
     */
    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @param string $path
     * @return ilSkinXML
     */
    public static function parseFromXML($path = ""){
        $xml = new SimpleXMLElement(file_get_contents($path));

        $id = basename (dirname($path));
        $skin = new self($id,(string)$xml->attributes()["name"]);

        foreach($xml->style as $style_xml){
            $skin->addStyle(ilSkinStyleXML::parseFromXMLElement($style_xml));
        }
        return $skin;
    }

    public function asXML(){
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><template/>');
        $xml->addAttribute("xmlns","http://www.w3.org");
        $xml->addAttribute("version","1");
        $xml->addAttribute("name",$this->getName());

        foreach($this->getStyles() as $style){
            $xml_style = $xml->addChild("style");
            $xml_style->addAttribute("id", $style->getId());
            $xml_style->addAttribute("name", $style->getName());
            $xml_style->addAttribute("image_directory", $style->getImageDirectory());
            $xml_style->addAttribute("css_file", $style->getCssFile());
            $xml_style->addAttribute("sound_directory", $style->getSoundDirectory());
            $xml_style->addAttribute("font_directory", $style->getFontDirectory());
        }

        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());
        return $dom->saveXML();
    }

    public function writeToXMLFile($path){
        file_put_contents($path, $this->asXML());
    }
    /**
     * @param ilSkinStyleXML $style
     */
    public function addStyle(ilSkinStyleXML $style){
        try{
            $this->styles[$style->getId()] = $style;
        }catch(Exception $e){
            //Todo
        }
    }

    /**
     * @param string $id
     */
    public function removeStyle($id){
        unset($this->styles[$id]);
    }

    /**
     * @param $id
     * @return ilSkinStyleXML
     */
    public function getStyle($id){
        return $this->styles[$id];
    }

    /**
     * Iterator implementations
     *
     * @return bool
     */
    public function valid() {
        return current($this->styles) !== false;
    }

    /**
     * @return	mixed
     */
    public function key() {
        return key($this->styles);
    }

    /**
     * @return	mixed
     */
    public function current() {
        return current($this->styles);
    }

    public function next() {
        next($this->styles);
    }
    public function rewind() {
        reset($this->styles);
    }

    /**
     * Countable implementations
     */
    public function count(){
        return count($this->styles);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return ilSkinStyleXML[]
     */
    public function getStyles()
    {
        return $this->styles;
    }

    /**
     * @param ilSkinStyleXML[] $styles
     */
    public function setStyles($styles)
    {
        $this->styles = $styles;
    }
}