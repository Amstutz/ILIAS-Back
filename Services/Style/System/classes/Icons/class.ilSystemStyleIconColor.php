<?php
require_once("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/classes/Models/Less/class.KitchenSinkLessItem.php");

/***
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$
 *
 */
class ilSystemStyleIconColor
{
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
     * @var KitchenSinkIcon[]
     */
    protected $usages = null;

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
        $this->usages = array();
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
     * @return mixed
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return KitchenSinkIcon[]
     */
    public function getUsages()
    {
        return $this->usages;
    }

    /**
     * @return array
     */
    public function getUsagesAsString()
    {
        $usage_string = "";
        foreach($this->getUsages() as $usage){
            $usage_string .= rtrim($usage->getName(),".svg")."; ";
        }
        return $usage_string;
    }


    /**
     * @param KitchenSinkIcon $usages
     */
    public function setUsages($usages)
    {
        $this->usages = $usages;
    }

    /**
     * @param KitchenSinkIcon $usage
     */
    public function addUsage(KitchenSinkIcon $usage){
        $this->usages[] = $usage;

    }


}
?>
