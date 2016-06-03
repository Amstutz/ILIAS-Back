<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Image;

use ILIAS\UI\Component as C;

class Image implements C\Image\Image {

    /**
     * @var	string
     */
    private $type;

    /**
     * @var	string
     */
    private  $src;

    /**
     * @var	string
     */
    private  $alt;


    public function __construct($type, $src, $alt) {
        assert('self::is_valid_type($type)');

        $this->type = $type;
        $this->src = $src;
        $this->alt = $alt;

    }
    public function withType($type){
        assert('self::is_valid_type($type)');
        $clone = clone $this;
        $clone->type = $type;
        return $clone;
    }

    /**
     * @inheritdoc
     */
    public function getType() {
        return $this->type;
    }


    public function withSource($source){
        assert('self::is_valid_type($type)');
        $clone = clone $this;
        $clone->src = $source;
        return $clone;
    }

    /**
     * @inheritdoc
     */
    public function getSource() {
        return $this->src;
    }


    public function withAlt($alt){
        assert('self::is_valid_type($alt)');
        $clone = clone $this;
        $clone->alt = $alt;
        return $clone;
    }

    /**
     * @inheritdoc
     */
    public function getAlt() {
        return $this->alt;
    }

    // Helper
    static protected function is_valid_type($type) {
        static $types = array
        (   self::RESPONSIVE,
            self::CIRCLE,
            self::ROUNDED,
            self::THUMBNAIL,
            self::STANDARD
        );
        return in_array($type, $types);
    }

}
?>