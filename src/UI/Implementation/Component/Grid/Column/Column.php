<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Grid\Column;


class Column implements \ILIAS\UI\Component\Grid\Column {

    private $content = array();
    private $width = 12;


    public function __construct($content, $width = 12) {
        $this->content = $this->formatContent($content);
        $this->width = $width;
    }

    public function widthContent($content){
        $clone = clone $this;
        $this->content = $this->formatContent($content);
        return $clone;
    }

    public function getContent(){
        return $this->content;
    }

    public function widthWith($width){
        $clone = clone $this;
        $clone->width = $width;
        return $clone;

    }

    public function getWidth(){
        return $this->width;
    }

    /**
     * Todo Discuss this with richard
     */
    protected function formatContent($content){
        if(!is_array($content)){
            $content = array($content);
        }
        return $content;
    }
}
?>