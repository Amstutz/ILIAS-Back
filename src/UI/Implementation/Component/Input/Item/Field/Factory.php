<?php

/* Copyright (c) 2016 Amstutz Timon <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Input\Item\Field;

use ILIAS\UI\Component\Input\Item\Field as F;
use ILIAS\UI\Implementation\Component\ComponentHelper;

/**
 * Class Factory
 *
 * @package ILIAS\UI\Implementation\Component\Filter
 */
class Factory implements F\Factory {

	use ComponentHelper;

	/**
	 * @inheritdoc
	 */
	public function text($id,$label) {
		return new Text($id,$label);
	}

	/**
	 * @inheritdoc
	 */
	public function number($id,$label) {
		return new Number($id,$label);
	}

	/**
	 * @inheritdoc
	 */
	public function nameAge($id) {
		return new NameAge($id, "");
	}
}