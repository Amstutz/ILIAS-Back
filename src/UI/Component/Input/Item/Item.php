<?php

/* Copyright (c) 2016 Fabian Schmid <fs@studer-raimann.ch> Extended GPL, see docs/LICENSE */
/* Copyright (c) 2016 Richard Klees <richard.klees@concepts-and-training.de> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Component\Input\Item;

/**
 * One item in the filter, might be composed from different input elements,
 * which all act as one filter input.
 */
interface Item extends \ILIAS\UI\Component\Component {

	/**
	 * @return string
	 */
	public function label();


	/**
	 * @return  mixed
	 */
	public function defaultsTo();


	/**
	 * Set the default value to be displayed.
	 *
	 * @param   mixed $default
	 */
	public function withDefault($default);

	/**
	 * Set the default value to be displayed.
	 *
	 * @param   mixed $default
	 */
	public function required($required = false);

	/**
	 * @return boolean
	 */
	public function isRequired();

	/**
	 * Make this filter non visible on initial rendering.
	 *
	 * @return null
	 */
	public function withNoInitialVisibility();


	/**
	 * Add an input validation to the filter.
	 *
	 * Validator must take two parameters:
	 *    - current content of the filter item
	 *    - ValidationMessageCollector to collect the error messages on the content
	 *
	 * Users must assume that validators run in parallel, i.e. you should not assume
	 * any specific order of validation.
	 *
	 * e.g.:
	 *
	 * function($res, ValidationMessageCollector $collector) use $lng {
	 *     if($res < 10) {
	 *         $collector->error($lng->txt('input_must_be_lower_than_ten'));
	 *     }
	 * }
	 *
	 *
	 * @param   callable $validator
	 * @return  Item
	 */
	public function withValidator(\ILIAS\UI\Implementation\Component\Input\Validation $validator);


	/**
	 * Add an input extractor to the filter item.
	 *
	 * Extractor must take on parameter
	 *    - current content of the filter item
	 * and transform it to a new content.
	 *
	 * e.g.:
	 *
	 * function ($usr_id) {
	 *     if (!is_int($usr_id)) {
	 *         throw new \InvalidArgumentException("PANIC!");
	 *     }
	 *     return ilObjUser::_lookupLogin($usr_id);
	 * }
	 *
	 * @param callable $extractor
	 * @return Item
	 */
	public function withExtractor(callable $extractor);
}

