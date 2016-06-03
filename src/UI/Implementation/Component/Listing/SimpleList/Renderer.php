<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Listing\SimpleList;

use ILIAS\UI\Implementation\Render\AbstractComponentRenderer;
use ILIAS\UI\Renderer as RendererInterface;
use ILIAS\UI\Component as C;
use ILIAS\UI\Component\Component;

class Renderer extends AbstractComponentRenderer {
	/**
	 * @inheritdocs
	 */
	public function render(Component $component, RendererInterface $default_renderer) {
		global $DIC;

		$this->checkComponent($component);

		$tpl = $this->getTemplate("tpl.simple_list.html", true, true);

		if(count($component->getItems())>0){
			$tpl->setVariable("TYPE",$component->getType());

			$f = $DIC->ui()->factory();

			foreach($component->getItems() as $item){
				if(is_string($item)){
					$item = $f->text()->standard($item);
				}

				$tpl->setCurrentBlock("item");
				$tpl->setVariable("ITEM", $default_renderer->render($item, $default_renderer));
				$tpl->parseCurrentBlock();

			}
		}



		return $tpl->get();
	}

	/**
	 * @inheritdocs
	 */
	protected function getComponentInterfaceName() {
		return "\\ILIAS\\UI\\Component\\Listing\\SimpleList";
	}
}
