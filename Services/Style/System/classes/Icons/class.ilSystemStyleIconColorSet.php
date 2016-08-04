<?php
include_once("Services/Style/System/classes/Icons/class.ilSystemStyleIconColor.php");


/***
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$
 *
 */
class ilSystemStyleIconColorSet
{
    /**
     * @var ilSystemStyleIconColor[]
     */
    protected $colors = [];

    /**
     * @param ilSystemStyleIconColor $color
     */
    public function addColor(ilSystemStyleIconColor $color){
        $this->colors[$color->getId()] = $color;
    }

    /**
     * @return ilSystemStyleIconColor[]
     */
    public function getColors()
    {
        return $this->colors;
    }

    /**
     * @param ilSystemStyleIconColor[] $colors
     */
    public function setColors(array $colors)
    {
        $this->colors = $colors;
    }

    /**
     * @param string $id
     * @return ilSystemStyleIconColor
     */
    public function getColorById($id = ""){
        return $this->colors[$id];
    }

    /**
     * @param ilSystemStyleIconColorSet $color_set
     */
    public function mergeColorSet(ilSystemStyleIconColorSet $color_set){
        foreach($color_set->getColors() as $color){
            if(!$this->getColorById($color->getId())){
                $this->addColor($color);
            }
        }
    }

    /**
     * @return array
     */
    public function getColorsSorted(){
        $colors_categories = [];
        foreach($this->getColors() as $color){
            $colors_categories[$color->getDominatAspect()][] = $color;
        }
        ksort($colors_categories);
        foreach($colors_categories as $category => $colors){
            usort($colors_categories[$category],array("ilSystemStyleIconColor","compareColors"));
        }

        return $colors_categories;
    }

    /**
     * @return array
     */
    public function asArray(){
        $colors = [];
        foreach($this->getColors() as $color){
            $colors[] = $color->getId();
        }
        return $colors;
    }

    /**
     * @return array
     */
    public function asString(){
        $colors = "";
        foreach($this->getColors() as $color){
            $colors .= $color->getId()."; ";
        }
        return $colors;
    }
}