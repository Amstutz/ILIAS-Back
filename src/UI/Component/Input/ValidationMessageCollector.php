<?php

/* Copyright (c) 2016 Fabian Schmid <fs@studer-raimann.ch> Extended GPL, see docs/LICENSE */
/* Copyright (c) 2016 Richard Klees <richard.klees@concepts-and-training.de> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Component\Filter;

/**
 * Collects messages during validation of filters.
 */
interface ValidationMessageCollector {

	/**
	 * Signal an error in the field.
	 *
	 * @param string $message
	 * @return null
	 */
	public function error($message);
}