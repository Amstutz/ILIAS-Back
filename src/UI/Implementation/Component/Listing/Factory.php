<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */


namespace \ILIAS\UI\Implementation\Component\Listing;

use \ILIAS\UI\Implementation\Component\Listing\Unordered as U;
use \ILIAS\UI\Implementation\Component\Listing\Ordered as O;
use \ILIAS\UI\Implementation\Component\Listing\Descriptive as D;

/**
 * Class Factory
 * @package ILIAS\UI\Implementation\Component\Listing
 */
class Factory implements \ILIAS\UI\Component\Listing\Factory {

	/**
	 * @inheritdoc
	 */
	public function unordered(array $items){
		return new S\Simple(S\Simple::UNORDERED,$items);
	}

	/**
	 * @inheritdoc
	 */
	public function ordered(array $items){
		return new S\Simple(S\Simple::ORDERED,$items);
	}

	/**
	 * @inheritdoc
	 */
	public function descriptive(array $items){
		return new D\Descriptive($items);
	}
}
