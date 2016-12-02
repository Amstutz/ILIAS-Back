<?php
/**
 * Demo Example
 */
function base() {
	//Loading factories
	global $DIC;
	$f = $DIC->ui()->factory();
	$renderer = $DIC->ui()->renderer();

	$text = $f->input()->item()->field()->text("Example Field");
	$text = $text->required(true);

	return $renderer->render($text);
}
