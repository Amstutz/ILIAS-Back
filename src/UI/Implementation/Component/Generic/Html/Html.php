<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Generic\Html;

use ILIAS\UI\Component as C;

class Html implements C\Generic\Html {

	/**
	 * @var	string
	 */
	private $html;


	public function __construct($html) {
		$this->html = $html;
	}

	/**
	 * @return string
	 */
	public function getHtml(){
		return $this->html;
	}

}
?>