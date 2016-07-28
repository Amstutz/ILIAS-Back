<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Grid\Row;

use ILIAS\UI\Implementation\Render\AbstractComponentRenderer;
use ILIAS\UI\Renderer as RendererInterface;
use ILIAS\UI\Component;

class Renderer extends AbstractComponentRenderer {
	/**
	 * @inheritdocs
	 */
	public function render(Component\Component $component, RendererInterface $default_renderer) {
		$this->checkComponent($component);


		$tpl = $this->getTemplate("tpl.row.html", true, true);

		$columns = "";
		foreach($component->getColumns() as $column){
			$columns .= $default_renderer->render($column,$default_renderer);
		}
		$tpl->setVariable("COLUMNS",$columns);


		return $tpl->get();
	}

	/**
	 * @inheritdocs
	 */
	protected function getComponentInterfaceName() {
		return array(Component\Grid\Row::class);
	}
}
