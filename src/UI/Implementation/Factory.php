<?php

/* Copyright (c) 2016 Richard Klees <richard.klees@concepts-and-training.de> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation;

use ILIAS\UI\Component as AbstractComponent;

class Factory implements \ILIAS\UI\Factory {
	/**
	 * @return AbstractComponent\Counter\Factory
	 */
	public function counter() {
		return new Component\Counter\Factory();
	}

	/**
	 * @return AbstractComponent\Glyph\Factory
	 */
	public function glyph() {
		return new Component\Glyph\Factory();
	}

	/**
	 * Todo: Do we allow this shorctut for elements containing only 1 family member?
	 * @param string $href
	 * @param string $caption
	 * @return AbstractComponent\Link\Link
	 */
	public function link($href,$caption)
	{
		return new Component\Link\Link($href,$caption);
	}

	/**
	 * Todo: Do we allow this shorctut for elements containing only 1 family member?
	 * @param string $title
	 * @param string $content
	 * @param AbstractComponent\Image\Image $image
	 * @return AbstractComponent\Card\Card
	 */
	public function card($title, $content,AbstractComponent\Image\Image $image) {
		return new Component\Card\Card($title, $content,$image);
	}

	/**
	 * @return AbstractComponent\Image\Factory
	 */
	public function image()
	{
		return new Component\Image\Factory();
	}

	/**
	 * @return AbstractComponent\Text\Factory
	 */
	public function text()
	{
		return new Component\Text\Factory();
	}

	/**
	 * @return AbstractComponent\Grid\Factory
	 */
	public function grid()
	{
		return new Component\Grid\Factory();
	}

	/**
	 * @return AbstractComponent\Listing\Factory
	 */
	public function listing()
	{
		return new Component\Listing\Factory();
	}

	/**
	 * @return AbstractComponent\Panel\Factory
	 */
	public function panel(){
		return new Component\Panel\Factory();

	}

	/**
	 * @return AbstractComponent\Generic\Generic
	 */
	public function generic($html)
	{
		return new Component\Generic\Generic($html);
	}
}