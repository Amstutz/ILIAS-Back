<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */


namespace ILIAS\UI\Implementation\Component\Listing\DescriptiveList;

use ILIAS\UI\Implementation\Render\AbstractComponentRenderer;
use ILIAS\UI\Renderer as RendererInterface;
use ILIAS\UI\Component as C;
use ILIAS\UI\Component\Component;

class Renderer extends AbstractComponentRenderer {
	/**
	 * @inheritdocs
	 */
	public function render(Component $component, RendererInterface $default_renderer) {
		$this->checkComponent($component);

		$tpl = $this->getTemplate("tpl.descriptive_list.html", true, true);

		foreach($component->getItems() as $item){
			$tpl->setCurrentBlock("item");
			$tpl->setVariable("DESCRIPTION",$default_renderer->render($item[0],$default_renderer));
			$tpl->setVariable("CONTENT",$default_renderer->render($item[1],$default_renderer));
			$tpl->parseCurrentBlock();
		}

		return $tpl->get();
	}

	/**
	 * @inheritdocs
	 */
	protected function getComponentInterfaceName() {
		return "\\ILIAS\\UI\\Component\\Listing\\DescriptiveList";
	}
}
