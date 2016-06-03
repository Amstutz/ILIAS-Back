<?php

/* Copyright (c) 2016 Richard Klees <richard.klees@concepts-and-training.de> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Component\Button;

/**
 * This is how a factory for buttons looks like.
 */
interface Factory {
	/**
	 * ---
	 * title Button
	 * ---
	 * @return  \ILIAS\UI\Component\Button
	 */
	public function def();
}
