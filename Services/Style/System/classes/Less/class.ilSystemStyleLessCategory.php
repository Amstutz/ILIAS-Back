<?php
require_once("./Services/Style/System/classes/Less/class.ilSystemStyleLessItem.php");

/**
 * Capsules data of a less category in the variables to less file. A less category has the following structure:
 *
 * //== NameOfCategory
 * //
 * //## Comment
 *
 *
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$
 *
 */
class ilSystemStyleLessCategory extends ilSystemStyleLessItem
{
    /**
     * Name of the category
     *
     * @var string
     */
    protected $name = "";

    /**
     * Comment to describe what this category is about
     *
     * @var string
     */
    protected $comment = "";

    /**
     * ilSystemStyleLessCategory constructor.
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

    /**
     * This function will be needed to write the category back to the less file and restore it's initial structure
     * in less.
     *
     * @return string
     */
    public function __toString()
    {
        return "//== ".$this->getName()."\n//\n//## ".$this->getComment()."\n";
    }
}