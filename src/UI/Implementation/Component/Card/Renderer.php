<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Card;

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
		$tpl = $this->getTemplate("tpl.card.html", true, true);

		$tpl->setVariable("TITLE",$default_renderer->render($component->getTitle(),$default_renderer));
		$tpl->setVariable("CONTENT",$default_renderer->render($component->getContent(),$default_renderer));
		if($component->getImage()){
			$tpl->setVariable("IMAGE",$default_renderer->render($component->getImage(),$default_renderer));
		}
		return $tpl->get();
	}

	/**
	 * @inheritdocs
	 */
	protected function getComponentInterfaceName() {
		return "\\ILIAS\\UI\\Component\\Card\\Card";
	}
}
