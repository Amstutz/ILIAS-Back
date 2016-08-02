<?php

/* Copyright (c) 2016 Richard Klees <richard.klees@concepts-and-training.de> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation;

use ILIAS\UI\Component as AbstractComponent;

class Factory implements \ILIAS\UI\Factory {
	/**
	 * @inheritdoc
	 */
	public function counter() {
		return new Component\Counter\Factory();
	}
	/**
	 * @inheritdoc
	 */
	public function glyph() {
		return new Component\Glyph\Factory();
	}
	/**
	 * @inheritdoc
	 */
	public function button() {
		return new Component\Button\Factory();
	}

	/**
	 * @inheritdoc
	 */
	public function link($href,$caption="")
	{
		return new Component\Link\Link($href,$caption);
	}

	/**
	 * @inheritdoc
	 */
	public function card($title,\ILIAS\UI\Component\Image\Image $image = null){
		return new Component\Card\Card($title,$image);
	}

	/**
	 * @inheritdoc
	 */
	public function deck(array $cards){
		return new Component\Deck\Deck($cards, Component\Deck\Deck::SIZE_S);
	}
	/**
	 * @inheritdoc
	 */
	public function image()
	{
		return new Component\Image\Factory();
	}

	/**
	 * @inheritdoc
	 */
	public function listing()
	{
		return new Component\Listing\Factory();
	}

	/**
	 * @inheritdoc
	 */
	public function panel(){
		return new Component\Panel\Factory();

	}

	/**
	 * @inheritdoc
	 */
	public function generic()
	{
		return new Component\Generic\Factory();
	}
}