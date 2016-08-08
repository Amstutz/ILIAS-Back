<?php

function base_text_block() {
	global $DIC;
	$f = $DIC->ui()->factory();
	$renderer = $DIC->ui()->renderer();

	$block = $f->panel()->block("Block Panel Title","Some Content");

	return $renderer->render($block);
}
