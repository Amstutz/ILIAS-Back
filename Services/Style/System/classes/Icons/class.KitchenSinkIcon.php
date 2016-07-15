<?php
require_once("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/classes/Models/Less/class.KitchenSinkLessItem.php");
require_once("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/classes/Models/Icon/class.KitchenSinkIconColorSet.php");


/***
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$
 *
 */
class KitchenSinkIcon extends KitchenSinkLessItem
{
    /**
     * @var string
     */
    protected $default_directory = "";


    /**
     * @var string
     */
    protected $skin_directory = "";

    /**
     * @var string
     */
    protected $name = "";

    /**
     * @var string
     */
    protected $type = "";

    /**
     * @var KitchenSinkIconColorSet
     */
    protected $color_set;

    /**
     * KitchenSinkIcon constructor.
     * @param string $default_directory
     * @param string $skin_directory
     * @param string $name
     * @param string $type
     * @param KitchenSinkIconColorSet $color_set
     */
    public function __construct($default_directory, $skin_directory, $name, $type)
    {
        $this->default_directory = $default_directory;
        $this->skin_directory = $skin_directory;
        $this->name = $name;
        $this->type = $type;
    }


    public function changeColor(KitchenSinkIconColorSet $color_set){
        if($this->getType() == "svg"){
            $icon = file_get_contents($this->getDefaultPath());
            foreach($color_set::getDefaultColors() as $default_color){
                if($color_set->getColorById($default_color->getId())){
                    $icon = preg_replace (  '/'.$default_color->getColor().'/' , $color_set->getColorById($default_color->getId())->getColor() , $icon, -1 );
                }
            }
            file_put_contents ($this->getSkinPath(),$icon);
        }
    }

    public function findUsage(KitchenSinkIconColorSet $color_set){
        if($this->getType() == "svg"){
            $icon = file_get_contents($this->getDefaultPath());
            foreach($color_set::getDefaultColors() as $default_color){
                if(preg_match (  '/'.$default_color->getColor().'/' ,$icon)){
                    $default_color->addUsage($this);
                }
            }
        }
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getSkinDirectory()
    {
        return $this->skin_directory;
    }

    /**
     * @param string $skin_directory
     */
    public function setSkinDirectory($skin_directory)
    {
        $this->skin_directory = $skin_directory;
    }

    /**
     * @return string
     */
    public function getDefaultDirectory()
    {
        return $this->default_directory;
    }

    /**
     * @param string $default_directory
     */
    public function setDefaultDirectory($default_directory)
    {
        $this->default_directory = $default_directory;
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

    /***
     * @return string
     */
    public function getDefaultPath(){
        return $this->getDefaultDirectory()."/".$this->getName();
    }

    /***
     * @return string
     */
    public function getSkinPath(){
        return $this->getSkinDirectory()."/".$this->getName();
    }
    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->getName();
    }
}
?>
