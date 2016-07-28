<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Link;

use ILIAS\UI\Implementation\Render\AbstractComponentRenderer;
use ILIAS\UI\Renderer as RendererInterface;
use ILIAS\UI\Component;

class Renderer extends AbstractComponentRenderer {
	/**
	 * @inheritdocs
	 */
	public function render(Component\Component $component, RendererInterface $default_renderer) {
		$this->checkComponent($component);
		$tpl = $this->getTemplate("tpl.link.html", true, true);

		$tpl->setVariable("CAPTION",$default_renderer->render($component->getCaption(),$default_renderer));

		$tpl->setVariable("HREF",$component->getHref());

		return $tpl->get();
	}

	/**
	 * @inheritdocs
	 */
	protected function getComponentInterfaceName() {
		return array(Component\Link\Link::class);
	}
}
