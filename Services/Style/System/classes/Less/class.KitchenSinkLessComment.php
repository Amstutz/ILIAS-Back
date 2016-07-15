<?php
require_once("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/classes/Models/Less/class.KitchenSinkLessItem.php");
/**
 *
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$
 *
 */
class KitchenSinkLessComment extends KitchenSinkLessItem
{

    /**
     * @var string
     */
    protected $comment = "";

    /**
     * KitchenSinkLessComment constructor.
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

    public function __toString()
    {
        return $this->getComment();
    }

}
?>