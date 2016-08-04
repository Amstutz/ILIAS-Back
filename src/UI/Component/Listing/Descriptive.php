<?php
/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */


namespace ILIAS\UI\Component\Listing;

/**
 * Interface Descriptive
 * @package ILIAS\UI\Component\Listing
 */
interface Descriptive extends \ILIAS\UI\Component\Component {

	/**
	 * Sets a key value pair as items for the list. Key is used as title and value as content.
	 * @param string[] $items string => Component | string
	 * @return \ILIAS\UI\Component\Listing\Descriptive
	 */
	public function withItems(array $items);

	/**
	 * @return [] $items string => Component | string
	 */
	public function getItems();
}