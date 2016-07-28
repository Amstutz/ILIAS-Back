<?php
/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Component\Listing;

/**
 */
interface SimpleList extends \ILIAS\UI\Component\Component {
    const UNORDERED = "ul";
    const ORDERED = "ol";


    public function withType($type);

    public function getType();

    public function withItems($items);

    public function getItems();
}