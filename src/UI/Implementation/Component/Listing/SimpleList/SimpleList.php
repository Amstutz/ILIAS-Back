<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Listing\SimpleList;

use ILIAS\UI\Component as C;

class SimpleList implements C\Listing\SimpleList {
    /**
     * @var	string
     */
    private $type;

    /**
     * @var	string
     */
    private  $items;


    public function __construct($type, $items) {
        $this->type = $type;
        $this->items = $items;
    }

    public function withType($type){
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


    public function withItems($items){
        $clone = clone $this;
        $clone->items = $items;
        return $clone;
    }

    /**
     * @inheritdoc
     */
    public function getItems() {
        return $this->items;
    }


    // Helper
    static protected function is_valid_type($type) {
        static $types = array
        (   self::UNORDERED,
            self::ORDERED
        );
        return in_array($type, $types);
    }
}
?>