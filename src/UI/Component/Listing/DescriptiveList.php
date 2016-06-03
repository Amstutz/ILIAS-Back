<?php
/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */


namespace ILIAS\UI\Component\Listing;

/**
 */
interface DescriptiveList extends \ILIAS\UI\Component\Component {

    public function withItems($items);

    public function getItems();
}