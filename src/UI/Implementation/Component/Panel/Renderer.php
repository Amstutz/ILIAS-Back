<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Panel;

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
		$tpl = $this->getTemplate("tpl.panel.html", true, true);

		global $DIC;
		$f = $DIC->ui()->factory();

		$tpl->setVariable("TYPE",$component->getType());
		$tpl->setVariable("HEADING_CLASS",$component->getHeadingClass());

		$tpl->setVariable("HEADING",$component->getHeading());//$default_renderer->render($component->getHeading(),$default_renderer));

		$body = "";


		if($component->getCard()){
			$row = $f->grid()->row(
					array(
							$f->grid()->column($component->getBody(),8),
							$f->grid()->column(array($component->getCard()),4),
					)
			);
			$body = $default_renderer->render($row);
		}else{
			foreach($component->getBody() as $item){
				$body .= $default_renderer->render($item,$default_renderer);
			}
		}

		$tpl->setVariable("BODY",$body);

		return $tpl->get();
	}

	/**
	 * @inheritdocs
	 */
	protected function getComponentInterfaceName() {
		return "\\ILIAS\\UI\\Component\\Panel\\Panel";
	}
}
