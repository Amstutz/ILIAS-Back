<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Panel;

use ILIAS\UI\Implementation\Render\AbstractComponentRenderer;
use ILIAS\UI\Renderer as RendererInterface;
use ILIAS\UI\Component;

class Renderer extends AbstractComponentRenderer {
	/**
	 * @inheritdocs
	 */
	public function render(Component\Component $component, RendererInterface $default_renderer) {
		$this->checkComponent($component);
		$tpl = $this->getTemplate("tpl.panel.html", true, true);

		$tpl->setVariable("TYPE",$component->getType());
		$tpl->setVariable("HEADING_CLASS",Component\Panel\Panel::BLOCK ? "ilBlockHeader":"ilHeader");

		$tpl->setVariable("HEADING",$component->getHeading());//$default_renderer->render($component->getHeading(),$default_renderer));

		if($component->getCard()){
            $tpl->setCurrentBlock("with_card");
            $tpl->setVariable("BODY",  $default_renderer->render($component->getBody()));
            $tpl->setVariable("CARD",  $default_renderer->render($component->getCard()));
            $tpl->parseCurrentBlock();
		}else{
            $tpl->setCurrentBlock("no_card");
            $tpl->setVariable("BODY",  $default_renderer->render($component->getBody()));
            $tpl->parseCurrentBlock();
        }

		return $tpl->get();
	}

	/**
	 * @inheritdocs
	 */
	protected function getComponentInterfaceName() {
		return array(Component\Panel\Panel::class);
	}
}
