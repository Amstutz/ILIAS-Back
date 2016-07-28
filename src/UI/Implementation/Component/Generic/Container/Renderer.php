<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Generic\Container;

use ILIAS\UI\Implementation\Render\AbstractComponentRenderer;
use ILIAS\UI\Renderer as RendererInterface;
use ILIAS\UI\Component;

class Renderer extends AbstractComponentRenderer {
	/**
	 * @inheritdocs
	 */
	public function render(Component\Component $component, RendererInterface $default_renderer) {
		$this->checkComponent($component);

		$html = "";
		foreach($component->getComponents() as $comp){
			$html .= $default_renderer->render($comp,$default_renderer);

		}
		return $html;
	}

	/**
	 * @inheritdocs
	 */
	protected function getComponentInterfaceName() {
		return array(Component\Generic\Container::class);
	}
}
