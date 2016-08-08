<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Component\Image;

/**
 * This describes how a glyph could be modified during construction of UI.
 */
interface Image extends \ILIAS\UI\Component\Component {
	/**
	 * Types of images
	 */
	const STANDARD = "standard";
	const RESPONSIVE = "responsive";
	/**
	 *
	 * Set the source (path) of the image.
	 * @param string
	 * @return \ILIAS\UI\Component\Image\Image
	 */
	public function withSource($source);

	/**
	 * Get the source (path) of the image.
	 * @return string
	 */
	public function getSource();

	/**
	 * Set the type of the image (currently only 'standard' is supported)
	 * @param string $type
	 * @return \ILIAS\UI\Component\Image\Image
	 */
	public function withType($type);

	/**
	 * Get the type of the image (currently only 'standard' is supported)
	 * @return string
	 */
	public function getType();

	/**
	 * Set the alternative text for screen readers
	 * @param string $alt
	 * @return \ILIAS\UI\Component\Image\Image
	 */
	public function withAlt($alt);


	/**
	 * Get the alternative text for screen readers
	 * @return string
	 */
	public function getAlt();

}
