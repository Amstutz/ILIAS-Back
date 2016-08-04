<?php
/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */


namespace ILIAS\UI\Component\Listing;

/**
 * Interface DescriptiveList
 * @package ILIAS\UI\Component\Listing
 */
interface DescriptiveList extends \ILIAS\UI\Component\Component {

	/**
	 * Sets a key value pair as items for the list. Key is used as title and value as content.
	 * @param string[] $items string (key) => string (value)
	 * @return \ILIAS\UI\Component\Listing\DescriptiveList
	 */
	public function withItems(array $items);

	/**
	 * @return string[] $items
	 */
	public function getItems();
}