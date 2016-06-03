<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Component\Card;

/**
 * This describes how a glyph could be modified during construction of UI.
 */
interface Card extends \ILIAS\UI\Component\Component {
	/**
	 * @param $title
	 * @return Card
	 */
	public function withTitle($title);

	public function getTitle();

	/**
	 * @param $content
	 * @return Card
	 */
	public function withContent($content);

	public function getContent();
	/**
	 * @param $image_path
	 * @return Card
	 */
	public function withImage($image);

	public function getImage();


}
