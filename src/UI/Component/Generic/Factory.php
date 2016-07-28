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
	 *       Todo
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
	 *       Todo
	 * ---
	 *
	 * @param   \ILIAS\UI\Component\Component[] $components
	 * @return  \ILIAS\UI\Component\Generic\Container
	 */
	public function container($components);
}
