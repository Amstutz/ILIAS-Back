<?php

/* Copyright (c) 2016 Amstutz Timon <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Card;

use ILIAS\UI\Component\Card as C;

class Card implements C\Card {

    /**
     * @var
     */
    protected $title;

    /**
     * @var
     */
    protected $content;

    /**
     * @var
     */
    protected $image;

    /**
     * @var ILIAS\UI\Factory
     */
    protected $f;

    /**
     * Card constructor.
     * @param string $title
     * @param string $content
     * @param \ILIAS\UI\Component\Image\Image $image
     */
    public function __construct($title, $content,\ILIAS\UI\Component\Image\Image $image) {
        global $DIC;

        $this->f = $DIC->ui()->factory();

        $this->title = $this->f->text()->heading($title);
        $this->content = $this->f->text()->standard($content);
        $this->image = $image;
    }

    /**
     * @inheritdoc
     */
    public function withTitle($title){
        global $DIC;

        $clone = clone $this;
        $clone->title = $this->f->text()->heading($title);
        return $clone;
    }

    /**
     * @inheritdoc
     */
    public function getTitle(){
        return $this->title;
    }


    /**
     * @inheritdoc
     */
    public function withContent($content){
        global $DIC;

        $clone = clone $this;
        $clone->content =  $this->f->text()->standard($content);
        return $clone;
    }

    /**
     * @inheritdoc
     */
    public function getContent(){
        return $this->content;
    }


    /**
     * @inheritdoc
     */
    public function withImage($image){
        $clone = clone $this;
        $clone->image = $image;
        return $clone;
    }

    /**
     * @inheritdoc
     */
    public function getImage(){
        return $this->image;
    }

    /**
     * @inheritdoc
     */
    public function getFactory()
    {
        return $this->factory;
    }
}
?>
