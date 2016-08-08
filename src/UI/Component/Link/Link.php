<?php
/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */


namespace ILIAS\UI\Component\Link;

/**
 */
interface Link extends \ILIAS\UI\Component\Component {
	/**
	 * @param string $caption
	 * @return Link
	 */
	public function withCaption($caption);

	public function getCaption();

	/**
	 * @param string $href
	 * @return Link
	 */
	public function withHref($href);

	public function getHref();
}

