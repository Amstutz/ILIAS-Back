<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Text;

use ILIAS\UI\Component\Text\Text as T;

class Factory implements \ILIAS\UI\Component\Text\Factory {
	/**
	 * @inheritdoc
	 */
	public function standard($text) {
		return new Text(T::STANDARD,$text);
	}

	/**
	 * @inheritdoc
	 */
	public function heading($text) {
		return new Text(T::HEADING,$text);
	}


}
