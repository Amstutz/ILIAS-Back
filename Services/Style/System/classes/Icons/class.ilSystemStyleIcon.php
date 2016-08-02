<?php
include_once("Services/Style/System/classes/Icons/class.ilSystemStyleIconColorSet.php");


/***
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$
 *
 */
class ilSystemStyleIcon
{

    /**
     * @var string
     */
    protected $path = "";

    /**
     * @var string
     */
    protected $name = "";

    /**
     * @var string
     */
    protected $type = "";

    /**
     * @var ilSystemStyleIconColorSet
     */
    protected $color_set = null;

    /**
     * ilSystemStyleIcon constructor.
     * @param $name
     * @param $path
     * @param $type
     */
    public function __construct($name, $path, $type)
    {
        $this->setName($name);
        $this->setType($type);
        $this->setPath($path);
    }


    /**
     * @param ilSystemStyleIconColorSet $old_colors
     * @param ilSystemStyleIconColorSet $new_colors
     */
    public function changeColor(array $color_changes){
        if($this->getType() == "svg"){
            $icon = file_get_contents($this->getPath());
            foreach($color_changes as $old_color => $new_color){
                $icon = preg_replace (  '/'.$old_color.'/i' , $new_color, $icon, -1 );
            }
            file_put_contents ($this->getPath(),$icon);
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
     * @return mixed
     */
    public function __toString()
    {
        return $this->getName();
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
        if($this->getType() == "svg"){
            $icon_content = file_get_contents($this->getPath());
            $color_matches = [];
            preg_match_all (  '/(?<=#)[\dabcdef]{6}/i' ,$icon_content,$color_matches);
            if(is_array($color_matches) && is_array($color_matches[0]))
            foreach($color_matches[0] as $color_value){
                $numeric = strtoupper(str_replace("#","",$color_value));
                $color = new ilSystemStyleIconColor($numeric,$color_value,$numeric,$color_value);
                $this->getColorSet()->addColor($color);
            }
        }
    }

    /**
     * @param ilSystemStyleIconColorSet $color_set
     */
    public function setColorSet($color_set)
    {
        $this->color_set = $color_set;
    }

    /**
     * @param $color_id
     * @return bool
     */
    public function usesColor($color_id){
        return $this->getColorSet()->getColorById($color_id) != null;
    }
}