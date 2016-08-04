<?php
require_once("./Services/Style/System/classes/Icons/class.ilSystemStyleIcon.php");

/**
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$
 */

/**
 * Class ilSystemStyleIconFolder
 */
class ilSystemStyleIconFolder
{
    /**
     * @var ilSystemStyleIcon[]
     */
    protected $icons = [];

    /**
     * @var string
     */
    protected $path = "";

    /**
     * @var ilSystemStyleIconColorSet
     */
    protected $color_set = null;

    /**
     * ilSystemStyleIconFolder constructor.
     * @param string $path
     */
    public function __construct($path)
    {
        $this->setPath($path);
        $this->read();
    }

    public function read(){
        $this->xRead($this->getPath(),"");
        $this->sortIcons();
    }

    /**
     *
     */
    public function sortIcons(){
        usort($this->icons, function(ilSystemStyleIcon $a, ilSystemStyleIcon $b){
            if($a->getType() == $b->getType()){
                return strcmp($a->getName(),$b->getName());
            }
            else{
                if($a->getType() == "svg"){
                    return false;
                }else if($b->getType() == "svg"){
                    return true;
                }else{
                    return strcmp($a->getType(),$b->getType());
                }
            }
        });
    }

    /**
     * @param string $src
     * @param string $rel_path
     * @throws ilSystemStyleException
     */
    protected function xRead($src = "",$rel_path=""){
        foreach (scandir($src) as $file) {
            $src_file = rtrim($src, '/') . '/' . $file;
            if (!is_readable($src_file)) {
                throw new ilSystemStyleException(ilSystemStyleException::FILE_OPENING_FAILED, $src_file);
            }
            if (substr($file, 0, 1) != ".") {
                if (is_dir($src_file)) {
                    self::xRead($src_file,$rel_path."/".$file);
                } else {
                    $info = new SplFileInfo($src_file);
                    $extension = $info->getExtension();
                    if($extension == "gif" || $extension == "svg" || $extension == "png"){
                        $this->addIcon(new ilSystemStyleIcon($file,$this->getPath().$rel_path."/".$file,$extension));
                    }
                }
            }
        }
    }


    /**
     * @param array $color_changes
     */
    public function changeIconColors(array $color_changes){
        foreach($this->getIcons() as $icon){
            $icon->changeColor($color_changes);
        }
    }

    /**
     * @param ilSystemStyleIcon $icon
     */
    public function addIcon(ilSystemStyleIcon $icon){
        $this->icons[] = $icon;
    }

    /**
     * @return ilSystemStyleIcon[]
     */
    public function getIcons()
    {
        return $this->icons;
    }

    /**
     *
     */
    public function getIconsSortedByFolder(){
        $folders = [];

        foreach($this->getIcons() as $icon){
            $folders[dirname($icon->getPath())][] = $icon;
        }

        ksort($folders);

        foreach($folders as $id => $folder){
            ksort($folders[$id]);
        }

        return $folders;
    }
    /**
     * @param ilSystemStyleIcon[] $icons
     */
    public function setIcons($icons)
    {
        $this->icons = $icons;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return ilSystemStyleIconColorSet
     */
    public function getColorSet()
    {
        if(!$this->color_set){
            $this->extractColorSet();
        }
        return $this->color_set;
    }

    protected function extractColorSet(){
        $this->color_set = new ilSystemStyleIconColorSet();
        foreach($this->getIcons() as $icon){
            $this->color_set->mergeColorSet($icon->getColorSet());
        }
    }

    /**
     * @param $color_id
     * @return ilSystemStyleIcon[]
     */
    public function getUsagesOfColor($color_id){
        $icons = [];
        foreach($this->getIcons() as $icon){
            if($icon->usesColor($color_id)){
                $icons[] = $icon;
            }
        }
        return $icons;
    }

    /**
     * @param $color_id
     * @return string
     */
    public function getUsagesOfColorAsString($color_id)
    {
        $usage_string = "";
        foreach($this->getUsagesOfColor($color_id) as $icon){
            $usage_string .= rtrim($icon->getName(),".svg")."; ";
        }
        return $usage_string;
    }

    /**
     * @param $color_set
     */
    public function setColorSet($color_set)
    {
        $this->color_set = $color_set;
    }


}