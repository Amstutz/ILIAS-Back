<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Panel;

use ILIAS\UI\Component as C;
use ILIAS\UI\Implementation\Component\ComponentHelper;

/**
 * Class Panel
 * @package ILIAS\UI\Implementation\Component\Panel
 */
class Panel implements C\Panel\Panel {
	use ComponentHelper;

	/**
	 * @var	string
	 */
	private $type;

	/**
	 * @var string
	 */
	private  $heading;

	/**
	 * @var \ILIAS\UI\Component\Component
	 */
	private  $body;

	/**
	 * @var \ILIAS\UI\Component\Card\Card
	 */
	private $card = null;

	/**
	 * @var array
	 */
	private static $types = array
		(   self::BLOCK,
			self::HEADING,
			self::REPORT
		);

	/**
	 * @param $type
	 * @param $heading
	 * @param $body
	 */
	public function __construct($type, $heading,$body) {
		$this->checkArgIsElement("type", $type, self::$types, "panel type");
		$this->checkStringArg("string",$heading);

		$this->type = $type;
		$this->heading = $heading;
		$this->body = $body;


	}

	/**
	 * @inheritdoc
	 */
	public function withType($type){
		$this->checkArgIsElement("type", $type, self::$types, "panel type");

		$clone = clone $this;
		$clone->type = $type;
		return $clone;
	}

	/**
	 * @inheritdoc
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @inheritdoc
	 */
	public function withHeading($heading){
		$this->checkStringArg("heading", $heading);

		$clone = clone $this;
		$this->heading = $heading;//$this->getFactory()->text()->heading($heading);
		return $clone;
	}

	/**
	 * @inheritdoc
	 */
	public function getHeading() {
		return $this->heading;
	}

	/**
	 * @inheritdoc
	 */
	public function withBody($body){
		$clone = clone $this;
		$clone->body = $body;
		return $clone;
	}

	/**
	 * @inheritdoc
	 */
	public function getBody() {
		return $this->body;
	}

	/**
	 * @inheritdoc
	 */
	public function withCard($card){
		$clone = clone $this;
		$clone->card = $card;
		return $clone;
	}

	/**
	 * @inheritdoc
	 */
	public function getCard() {
		return $this->card;
	}
}
?>