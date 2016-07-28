<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Panel;

use ILIAS\UI\Component\Panel as P;

class Factory implements \ILIAS\UI\Component\Panel\Factory {
	/**
	 * @inheritdoc
	 */
	public function block($heading,$body) {
		return new Panel(P\Panel::BLOCK,$heading,$body);
	}

	/**
	 * @inheritdoc
	 */
	public function heading($heading,$body) {
		return new Panel(P\Panel::HEADING,$heading,$body);
	}

	/**
	 * @inheritdoc
	 */
	public function bulletin($heading,$body) {
		return new Panel(P\Panel::HEADING,$heading,$body);
	}
}
