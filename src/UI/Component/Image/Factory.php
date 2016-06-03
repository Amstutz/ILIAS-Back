<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Component\Image;

/**
 * This is how the factory for UI elements looks. This should provide access
 * to all UI elements at some point.
 */
interface Factory {
	/**
	 * ---
	 * title: Standard Image
	 * ----
	 * @return  \ILIAS\UI\Component\Image\Image
	 */
	public function standard($src,$alt);
	/**
	 * ---
	 * title: Responsive Image
	 * ----
	 * @return  \ILIAS\UI\Component\Image\Image
	 */
	public function responsive($src,$alt);

	/**
	 * ---
	 * title: Circle Image
	 * ----
	 * @return  \ILIAS\UI\Component\Image\Image
	 */
	public function circle($src,$alt);

	/**
	 * ---
	 * title: Rounded Image
	 * ----
	 * @return  \ILIAS\UI\Component\Image\Image
	 */
	public function rounded($src,$alt);

	/**
	 * ---
	 * title: Thumbnail Image
	 * ----
	 * @return  \ILIAS\UI\Component\Image\Image
	 */
	public function thumbnail($src,$alt);
}
