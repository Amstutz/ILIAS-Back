<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Link;

use ILIAS\UI\Component as C;

class Link implements C\Link\Link {

	/**
	 * @var	string
	 */
	private $href;

	/**
	 * @var	string
	 */
	private  $caption;


	public function __construct($href, $caption = "") {
		$this->href = $href;
		$this->caption = $this->formatCaption($caption);
	}
	public function withHref($href){
		$clone = clone $this;
		$clone->href = $href;
		return $clone;
	}

	/**
	 * @inheritdoc
	 */
	public function getHref() {
		return $this->href;
	}


	public function withCaption($caption){
		$clone = clone $this;
		$clone->caption = $this->foramtCaption($caption);
		return $clone;
	}

	/**
	 * @inheritdoc
	 */
	public function getCaption() {
		return $this->caption;
	}

	/**
	 * Todo: Discuss this with Richard
	 * @param $caption
	 * @return C\Component
	 */
	protected function formatCaption($caption){
		global $DIC;
		$f = $DIC->ui()->factory();

		if($caption){
			if(is_string($caption)){
				$this->caption = $f->text()->standard($caption);
			}else{
				$this->caption = $caption;
			}
		}else{
			$this->caption = $f->text()->standard($this->href);
		}
		return $this->caption;
	}
}
?>