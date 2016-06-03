<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Grid;

use ILIAS\UI\Implementation\Component\Grid\Row as R;
use ILIAS\UI\Implementation\Component\Grid\Column as C;
use ILIAS\UI\Component\Grid;

class Factory implements \ILIAS\UI\Component\Grid\Factory {
	/**
	 * @inheritdoc
	 */
	public function row($columns) {
		return new R\Row($columns);
    }

	/**
	 * @inheritdoc
	 */
	public function column($content,$width = 12) {
		return new C\Column($content,$width);
	}
}
