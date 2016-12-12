<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

function base() {
	//Init Factory and Renderer
	global $DIC;
	$f = $DIC->ui()->factory();
	$renderer = $DIC->ui()->renderer();

	$content = $f->legacy("hello"
	);

	$image = $f->image()->responsive(
		"./templates/default/images/HeaderIcon.svg",
		"Thumbnail Example");

	$card = $f->card(
		"Title",
		$image
	)->withSections(array($content));

	//Render
	return $renderer->render($card);
}
