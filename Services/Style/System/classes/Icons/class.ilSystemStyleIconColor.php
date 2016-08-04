<?php
/***
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$
 *
 */
class ilSystemStyleIconColor
{
    const GREY = 0;
    const RED = 1;
    const GREEN = 2;
    const BLUE = 3;

    /**
     * @var string
     */
    protected $id = "";

    /**
     * @var string
     */
    protected $color = "";

    /**
     * @var string
     */
    protected $name = "";

    /**
     * @var string
     */
    protected $description = "";

    /**
     * @var null
     */
    protected $brightness = null;

    /**
     * KitchenSinkIconColor constructor.
     * @param $id
     * @param $name
     * @param $color
     * @param $description
     */
    public function __construct($id, $name, $color, $description = "")
    {
        $this->id = $id;
        $this->color = $color;
        $this->name = $name;
        $this->description = $description;
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
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor($color)
    {
        $this->color = $color;
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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getDominatAspect(){
        $r = $this->getRedAspect();
        $g = $this->getGreenAspect();
        $b = $this->getBlueAspect();

        if($r == $g && $r==$b && $g==$b){
            return self::GREY;
        }else if($r > $g && $r > $b){
            return self::RED;
        }else if($g > $r && $g > $b){
            return self::GREEN;
        }else{
            return self::BLUE;
        }
    }

    /**
     * @return number
     */
    public function getRedAspect(){
        return hexdec(substr($this->getColor(),0,2));
    }

    /**
     * @return number
     */
    public function getGreenAspect(){
        return hexdec(substr($this->getColor(),2,2));
    }

    /**
     * @return number
     */
    public function getBlueAspect(){
        return hexdec(substr($this->getColor(),4,2));
    }

    public function getPerceivedBrightness(){
        if($this->brightness === null){
            $r = $this->getRedAspect();
            $g = $this->getGreenAspect();
            $b = $this->getBlueAspect();

            $this->brightness =  sqrt(
                $r * $r * .299 +
                $g * $g * .587 +
                $b * $b * .114);
        }
        return $this->brightness;
    }

    /**
     * @param ilSystemStyleIconColor $color1
     * @param ilSystemStyleIconColor $color2
     * @return int
     */
    static function compareColors(ilSystemStyleIconColor $color1, ilSystemStyleIconColor $color2){
        $value1 = $color1->getPerceivedBrightness();
        $value2 = $color2->getPerceivedBrightness();

        if ($value1 == $value2) {
            return 0;
        }
        return ($value1 > $value2) ? +1 : -1;
    }
}