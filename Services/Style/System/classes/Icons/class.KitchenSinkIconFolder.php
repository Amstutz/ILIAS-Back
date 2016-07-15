<?php
require_once("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/classes/Models/Icon/class.KitchenSinkIcon.php");


/***
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$
 *
 */
class KitchenSinkIconFolder
{
    /**
     * @var KitchenSinkIcon[]
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
     * KitchenSinkIconFolder constructor.
     * @param $default_path
     * @param $skin_path
     */
    public function __construct($default_path,$skin_path )
    {
        $this->skin_path = $skin_path;
        $this->default_path = $default_path;
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
        usort($this->icons, function(KitchenSinkIcon $a, KitchenSinkIcon $b){
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
     * @throws ilKitchenSinkException
     */
    protected function xRead($src = "",$rel_path=""){
        foreach (scandir($src) as $file) {

            $src_file = rtrim($src, '/') . '/' . $file;
            if (!is_readable($src_file)) {
                throw new ilKitchenSinkException(ilKitchenSinkException::FILE_OPENING_FAILED, $src_file);
            }
            if (substr($file, 0, 1) != ".") {
                if (is_dir($src_file)) {
                    self::xRead($src_file,$rel_path."/".$file);
                } else {

                    $info = new SplFileInfo($src_file);
                    $this->addIcon(new KitchenSinkIcon($this->getDefaultPath().$rel_path,$this->getSkinPath().$rel_path,$file,$info->getExtension()));
                }

            }
        }
    }

    /**
     * @param KitchenSinkIconColorSet $color_set
     */
    public function changeIconColors(KitchenSinkIconColorSet $color_set){
        foreach($this->getIcons() as $icon){
            $icon->changeColor($color_set);
        }
    }

    /**
     * @param KitchenSinkIconColorSet $color_set
     */
    public function findIconColorUsages(KitchenSinkIconColorSet $color_set){
        foreach($this->getIcons() as $icon){
            $icon->findUsage($color_set);
        }
    }


    public function addIcon(KitchenSinkIcon $icon){
        $this->icons[] = $icon;
    }

    /**
     * @return KitchenSinkIcon[]
     */
    public function getIcons()
    {
        return $this->icons;
    }

    /**
     * @param KitchenSinkIcon[] $icons
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
