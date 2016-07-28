<?php

/* Copyright (c) 2016 Richard Klees <richard.klees@concepts-and-training.de> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Generic;

use ILIAS\UI\Component\Generic as G;

class Factory implements G\Factory {
	/**
	 * @inheritdoc
	 */
	public function html($html) {
        return new Html\Html($html);
    }

	/**
	 * @inheritdoc
	 */
	public function container($components) {
        return new Container\Container($components);
    }
}
