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
     * description:
     *   purpose: The standard image is used if there is no specific reason to use another specialized one.
	 * ----
	 * @return  \ILIAS\UI\Component\Image\Image
	 */
	public function standard($src,$alt);
	/**
	 * ---
	 * ----
	 * @return  \ILIAS\UI\Component\Image\Image
	 */
	public function responsive($src,$alt);

	/**
	 * ---
	 * ----
	 * @return  \ILIAS\UI\Component\Image\Image
	 */
	public function circle($src,$alt);

	/**
	 * ---
	 * ----
	 * @return  \ILIAS\UI\Component\Image\Image
	 */
	public function rounded($src,$alt);

	/**
	 * ---
	 * ----
	 * @return  \ILIAS\UI\Component\Image\Image
	 */
	public function thumbnail($src,$alt);
}
