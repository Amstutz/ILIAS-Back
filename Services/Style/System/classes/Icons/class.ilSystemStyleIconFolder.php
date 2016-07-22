<?php
require_once("./Services/Style/System/classes/Icons/class.ilSystemStyleIcon.php");


/***
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$
 *
 */
class ilSystemStyleIconFolder
{
    /**
     * @var ilSystemStyleIcon[]
     */
    protected $icons = array();

    /**
     * @var string
     */
    protected $skin_path = "";

    /**
     * @var string
     */
    protected $default_path = "";

    /**
     * ilSystemStyleIconFolder constructor.
     * @param $default_path
     * @param $skin_path
     */
    public function __construct($default_path,$skin_path )
    {
        $this->skin_path = $skin_path;
        $this->default_path = $default_path;

        $this->read();
    }

    public function read($default = false){
        if($default){
            $this->xRead($this->getSkinPath(),"");
        }else{
            $this->xRead($this->getDefaultPath(),"");
        }
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
                    $this->addIcon(new ilSystemStyleIcon($this->getDefaultPath().$rel_path,$this->getSkinPath().$rel_path,$file,$info->getExtension()));
                }

            }
        }
    }

    /**
     * @param ilSystemStyleIconColorSet $color_set
     */
    public function changeIconColors(ilSystemStyleIconColorSet $color_set){
        foreach($this->getIcons() as $icon){
            $icon->changeColor($color_set);
        }
    }

    /**
     * @param ilSystemStyleIconColorSet $color_set
     */
    public function findIconColorUsages(ilSystemStyleIconColorSet $color_set){
        foreach($this->getIcons() as $icon){
            $icon->findUsage($color_set);
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
     * @param ilSystemStyleIcon[] $icons
     */
    public function setIcons($icons)
    {
        $this->icons = $icons;
    }

    /**
     * @return string
     */
    public function getSkinPath()
    {
        return $this->skin_path;
    }

    /**
     * @param string $skin_path
     */
    public function setSkinPath($skin_path)
    {
        $this->skin_path = $skin_path;
    }

    /**
     * @return string
     */
    public function getDefaultPath()
    {
        return $this->default_path;
    }

    /**
     * @param string $default_path
     */
    public function setDefaultPath($default_path)
    {
        $this->default_path = $default_path;
    }

}
?>
