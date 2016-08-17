<?php
require_once("./Services/Style/System/classes/Less/class.ilSystemStyleLessItem.php");
/**
 *
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$
 *
 */
class ilSystemStyleLessComment extends ilSystemStyleLessItem
{

    /**
     * @var string
     */
    protected $comment = "";

    /**
     * ilSystemStyleLessComment constructor.
     * @param string $comment
     */
    public function __construct($comment)
    {
        $this->comment = $comment;
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
     * @return string
     */
    public function __toString()
    {
        return $this->getComment();
    }

}