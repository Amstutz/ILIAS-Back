<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Link;

use ILIAS\UI\Component as C;

class Link implements C\Link\Link {

    /**
     * @var	string
     */
    private $href;

    /**
     * @var	string
     */
    private  $caption;


    public function __construct($href, $caption) {
        $this->href = $href;
        $this->caption = $caption;
    }
    public function withHref($href){
        $clone = clone $this;
        $clone->href = $href;
        return $clone;
    }

    /**
     * @inheritdoc
     */
    public function getHref() {
        return $this->href;
    }


    public function withCaption($caption){
        $clone = clone $this;
        $clone->caption = $caption;
        return $clone;
    }

    /**
     * @inheritdoc
     */
    public function getCaption() {
        return $this->caption;
    }
}
?>