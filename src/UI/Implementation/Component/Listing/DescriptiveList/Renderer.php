<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */


namespace ILIAS\UI\Implementation\Component\Listing\DescriptiveList;

use ILIAS\UI\Implementation\Render\AbstractComponentRenderer;
use ILIAS\UI\Renderer as RendererInterface;
use ILIAS\UI\Component;

class Renderer extends AbstractComponentRenderer {
	/**
	 * @inheritdocs
	 */
	public function render(Component\Component $component, RendererInterface $default_renderer) {
		global $DIC;

		$this->checkComponent($component);

		$tpl = $this->getTemplate("tpl.descriptive_list.html", true, true);

		$f = $DIC->ui()->factory();

		foreach($component->getItems() as $key => $item){

			$key = $f->text()->standard($key);

			if(is_string($item)){
				$item = $f->text()->standard($item);
			}

			$content = $default_renderer->render($item,$default_renderer);

			if(trim($content) != ""){
				$tpl->setCurrentBlock("item");
				$tpl->setVariable("DESCRIPTION",$default_renderer->render($key,$default_renderer));
				$tpl->setVariable("CONTENT",$content);
				$tpl->parseCurrentBlock();
			}
		}

		return $tpl->get();
	}

	/**
	 * @inheritdocs
	 */
	protected function getComponentInterfaceName() {
		return array(Component\Listing\DescriptiveList::class);
	}
}
