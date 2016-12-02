<?php

namespace ILIAS\UI\Component\Input\Container;

/**
 * Bundles some filter items together to form a complete filter. This interface represents the state of the filter after an input has applied to.
 */
interface Container extends \ILIAS\UI\Component\Component {

	/**
	 * @return \ILIAS\UI\Component\Input\Item[]
	 */
	public function getItems();

	/**
	 * @param array|null $input defaults to $_POST
	 * @return FilterWithInput
	 */
	public function withInput(array $input = null);

	/**
	 * Convenience method for count($this->validationErrors()) == 0.
	 *
	 * @return bool
	 */
	public function hasValidContent();


	/**
	 * @throws \LogicException if !$this->hasValidContent()
	 * @return array
	 */
	public function content();


	/**
	 * @throws \LogicException if filter has not been validated
	 * @return string[]
	 */
	public function validationErrors();
}