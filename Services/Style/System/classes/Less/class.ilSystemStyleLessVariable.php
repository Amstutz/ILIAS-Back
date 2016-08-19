<?php

/***
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$
 *
 */
class ilSystemStyleLessVariable extends ilSystemStyleLessItem
{

    /**
     * @var string
     */
    protected $name = "";

    /**
     * @var string
     */
    protected $value = "";

    /**
     * @var string
     */
    protected $comment = "";

    /**
     * @var string
     */
    protected $category_name = "";

    /**
     * @var array
     */
    protected $references = array();

    /**
     * ilSystemStyleLessVariable constructor.
     * @param $name
     * @param $value
     * @param $comment
     * @param $category_name
     * @param $references
     */
    public function __construct($name, $value, $comment,$category_name, $references)
    {
        $this->setName($name);
        $this->setValue($value);
        $this->setCategoryName($category_name);
        $this->setComment($comment);
        $this->setReferences($references);
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
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        /**
         * @Todo: Fix this nasty hack to correct the icon-font-path
         */
        if($value == "\"../../Services/UICore/lib/bootstrap-3.2.0/fonts/\""){
            $this->value = "\"../../../../Services/UICore/lib/bootstrap-3.2.0/fonts/\"";
        }else{
            $this->value = $value;

        }
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
     * This function will be needed to write the variable back to the less file and restore it's initial structure
     * in less.
     *
     * @return string
     */
    public function __toString()
    {
        $content = "";
        if($this->getComment()){
            $content .= "//** ".$this->getComment()."\n";
        }
        $content .= "@".$this->getName().":\t\t". $this->getValue().";\n";
       return $content;
    }

    /**
     * @return string
     */
    public function getCategoryName()
    {
        return $this->category_name;
    }

    /**
     * @param string $category_name
     */
    public function setCategoryName($category_name)
    {
        $this->category_name = $category_name;
    }

    /**
     * @return array
     */
    public function getReferences()
    {
        return $this->references;
    }

    /**
     * @param array $references
     */
    public function setReferences($references)
    {
        $this->references = $references;
    }
}