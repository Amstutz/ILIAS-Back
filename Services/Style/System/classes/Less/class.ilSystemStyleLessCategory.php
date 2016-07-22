<?php
require_once("./Services/Style/System/classes/Less/class.ilSystemStyleLessItem.php");

/**
 * Class to display Kitchen Sink Entries
 *
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$
 *
 */
class ilSystemStyleLessCategory extends ilSystemStyleLessItem
{
    /**
     * @var string
     */
    protected $name = "";

    /**
     * @var string
     */
    protected $comment = "";

    /**
     * KitchenSinkLessCategory constructor.
     * @param string $name
     * @param string $comment
     */
    public function __construct($name, $comment = "")
    {
        $this->name = $name;
        $this->comment = $comment;
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
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    public function __toString()
    {
        return "//== ".$this->getName()."\n//\n//## ".$this->getComment()."\n";
    }
}
?>