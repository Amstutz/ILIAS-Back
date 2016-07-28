<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Text;

use ILIAS\UI\Implementation\Render\AbstractComponentRenderer;
use ILIAS\UI\Renderer as RendererInterface;
use ILIAS\UI\Component;

class Renderer extends AbstractComponentRenderer {
	/**
	 * @inheritdocs
	 */
	public function render(Component\Component $component, RendererInterface $default_renderer) {
		$this->checkComponent($component);

		$tpl = $this->getTemplate("tpl.text.html", true, true);

		$tpl->setCurrentBlock($component->getType());
		$tpl->setVariable("TEXT",$component->getText());
		$tpl->parseCurrentBlock($component->getType());

		return $tpl->get();
	}

	/**
	 * @inheritdocs
	 */
	protected function getComponentInterfaceName() {
		return array(Component\Text\Text::class);
	}
}
