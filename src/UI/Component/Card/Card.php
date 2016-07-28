<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Component\Card;

interface Card extends \ILIAS\UI\Component\Component {

	/**
	 * @param $title
	 * @return Card
	 */
	public function withTitle($title);

	/***
	 * @return string
	 */
	public function getTitle();

	/**
	 * @param \ILIAS\UI\Component\Component $section
	 * @return Card
	 */
	public function withHeaderSection(\ILIAS\UI\Component\Component $section);

	/**
	 * @return \ILIAS\UI\Component\Component
	 */
	public function getHeaderSection();

	/**
	 * @param \ILIAS\UI\Component\Component[] $sections
	 * @return Card
	 */
	public function withContentSections($sections);

	/**
	 * @return \ILIAS\UI\Component\Component[]
	 */
	public function getContentSections();

	/**
	 * @param \ILIAS\UI\Component\Image\Image $image
	 * @return Card
	 */
	public function withImage(\ILIAS\UI\Component\Image\Image $image);

	/**
	 * @return mixed
	 */
	public function getImage();
}
