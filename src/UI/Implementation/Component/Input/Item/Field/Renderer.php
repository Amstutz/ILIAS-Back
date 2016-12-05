<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Input\Item\Field;

use ILIAS\UI\Implementation\Render\AbstractComponentRenderer;
use ILIAS\UI\Renderer as RendererInterface;
use ILIAS\UI\Component;

/**
 * Class Renderer
 * @package ILIAS\UI\Implementation\Component\Image
 */
class Renderer extends AbstractComponentRenderer {
	/**
	 * @inheritdocs
	 */
	public function render(Component\Component $component, RendererInterface $default_renderer) {
		/**
		 * @var Component\Input\Item\Item $component
		 */
		$this->checkComponent($component);
		$tpl = $this->getTemplate("Filter/tpl.text.html", true, true);



		//$tpl->setVariable("ID","TODO");
		$tpl->setVariable("FOR",$component->title());
		$tpl->setVariable("TITLE",$component->title());
		if($component->validates()){
			$tpl->setVariable("VALUE",$component->content());
		}else{
			$tpl->setVariable("VALUE",$component->defaultsTo());
		}
		$tpl->setVariable("LABEL",$component->label());
		if($component->isRequired()){
			$tpl->touchBlock("required");
			$tpl->setVariable("REQUIRED","true");

		}

		return $tpl->get();
	}

	/**
	 * @inheritdocs
	 */
	protected function getComponentInterfaceName() {
		return [Component\Input\Item\Field\Text::class];
	}
}
