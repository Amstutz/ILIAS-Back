<?php

/* Copyright (c) 2016 Richard Klees <richard.klees@concepts-and-training.de> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Render;

/**
 * Wraps an ilTemplate to only provide smaller interface.
 */
class ilTemplateWrapper implements Template {
	/**
 	 * @var	ilTemplate
	 */
	private $tpl;

	final public function __construct(\ilTemplate $tpl) {
		$this->tpl = $tpl;
	}

	/**
 	 * @inheritdocs
	 */
	public function setCurrentBlock($name) {
		return $this->tpl->setCurrentBlock($name);
	}

	/**
 	 * @inheritdocs
	 */
	public function parseCurrentBlock() {
		return $this->tpl->parseCurrentBlock();
	}

	/**
 	 * @inheritdocs
	 */
	public function touchBlock($name) {
		return $this->tpl->touchBlock($name);
	}

	/**
 	 * @inheritdocs
	 */
	public function setVariable($name, $value) {
		return $this->tpl->setVariable($name, $value);
	}

	/**
 	 * @inheritdocs
	 */
	public function get($block = null) {
		if ($block === null) {
			$block = "__global__";
		}
		return $this->tpl->get($block);
	}

	/**
	 * @param $string
	 */
	public function addJavaScript($string){
		$this->tpl->addJavaScript("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/libs/highlight/highlight.pack.js");

	}

	/**
	 * @param $string
	 */
	public function addOnLoadCode($string){
		$this->tpl->addOnLoadCode("hljs.initHighlightingOnLoad();");
	}

	/**
	 * @param $string
	 */
	public function addCss($string){
		$this->tpl->addCss("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/libs/highlight/styles/default.css");
	}
}
