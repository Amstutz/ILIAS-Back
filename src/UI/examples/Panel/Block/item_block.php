<?php

function item_block() {
	global $DIC;
	$f = $DIC->ui()->factory();
	$renderer = $DIC->ui()->renderer();

	$content = $f->generic()->container(array(
		$f->listing()->ordered(array("item 1","item 2","item 3")),
		$f->listing()->unordered(array("item 1","item 2","item 3"))
	));

	$blockPanel2 = $f->panel()->block("Block Panel Title",$content);

	return $renderer->render($blockPanel2);
}
