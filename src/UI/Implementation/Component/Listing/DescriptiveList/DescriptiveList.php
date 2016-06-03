<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Listing\DescriptiveList;

use ILIAS\UI\Component as C;

class DescriptiveList implements C\Listing\DescriptiveList {

    /**
     * @var	string
     */
    private  $items;


    public function __construct($items) {
        $this->items = $items;
    }

    public function withItems($items){
        assert('self::is_valid_type($type)');
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
}
?>