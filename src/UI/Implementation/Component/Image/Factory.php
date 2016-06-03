<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Image;

use ILIAS\UI\Component\Image\Image as I;

class Factory implements \ILIAS\UI\Component\Image\Factory {
	/**
	 * @inheritdoc
	 */
	public function responsive($src,$alt) {
		return new Image(I::RESPONSIVE,$src,$alt);
    }

	/**
	 * @inheritdoc
	 */
	public function circle($src,$alt) {
		return new Image(I::CIRCLE, $src,$alt);
	}

	/**
	 * @inheritdoc
	 */
	public function rounded($src,$alt) {
		return new Image(I::ROUNDED,$src,$alt);
	}

	/**
	 * @inheritdoc
	 */
	public function thumbnail($src,$alt) {
		return new Image(I::THUMBNAIL,$src,$alt);
	}

	/**
	 * @inheritdoc
	 */
	public function standard($src,$alt) {
		return new Image(I::STANDARD,$src,$alt);
	}
}
