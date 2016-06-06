<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Grid\Column;

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

		$tpl = $this->getTemplate("tpl.column.html", true, true);


		$column = "";
		$tpl->setVariable("WIDTH",$component->getWidth());
		foreach($component->getContent() as $content){
			$column .= $default_renderer->render($content,$default_renderer);
		}

		$tpl->setVariable("CONTENT",$column);


		return $tpl->get();
	}

	/**
	 * @inheritdocs
	 */
	protected function getComponentInterfaceName() {
		return "\\ILIAS\\UI\\Component\\Grid\\Column";
	}
}
