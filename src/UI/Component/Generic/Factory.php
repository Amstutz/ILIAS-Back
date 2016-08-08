<?php
/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */
namespace ILIAS\UI\Component\Generic;
/**
 * This is how the factory for UI elements looks. This should provide access
 * to all UI elements at some point.
 */
interface Factory {
	/**
	 * ---
	 * description:
	 *   purpose: >
	 *     This component is used to wrap an existing ILIAS UI element into a UI component. This is useful if a container
	 *     of the UI components needs to contain content that is not yet implement in the centralized UI components.
	 *   composition: >
	 *     The html component contains html as string.
	 *
	 * rules:
	 *   wording:
	 *      1: This components MUST only be used to ensure backwards compatibility with existing UI elements in ILIAS.
	 * ---
	 *
	 * @param   string $html
	 * @return  \ILIAS\UI\Component\Generic\Html
	 */
	public function html($html);

	/**
	 * ---
	 * description:
	 *   purpose: >
	 *       Containers are used to store and array of UI components. This is useful if a contaienr should render not
	 *       only one UI component but an ordered set of components.
	 * ---
	 *
	 * @param   \ILIAS\UI\Component\Component[] $components
	 * @return  \ILIAS\UI\Component\Generic\Container
	 */
	public function container($components);
}
