<?php

namespace ILIAS\UI\Implementation\Component\Input\Container\Form;

use ILIAS\UI\Component\Input\Container as C;
use ILIAS\UI\Implementation\Component\Input\Container as IC;


class Standard extends IC\Container implements C\Form\Standard {
	/**
	 * @var string
	 */
	protected $action = "";

	public function getAction(){
		return $this->action;
	}

	/**
	 * @inheritdoc
	 */
	public function __construct($action, $items) {
		$this->checkStringArg("action",$action);
		$this->action = $action;

		parent::__construct($items);
	}
}
