<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Card;
/**
 * TODO: Might not be needed if shortcut is used.
 */
class Factory implements \ILIAS\UI\Component\Card\Factory {
	/**
	 * @inheritdoc
	 */
	public function standard($title, $content, $image) {
		return new Card($title, $content, $image);
	}
}
