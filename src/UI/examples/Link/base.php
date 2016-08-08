<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

function base() {
	global $DIC;
	$f = $DIC->ui()->factory();
	$renderer = $DIC->ui()->renderer();

	$standardLink = $f->link("http://www.google.ch",$f->text()->standard("GOOGLE"));
	$headingLink = $f->link("http://www.google.ch",$f->text()->heading("GOOGLE"));


	$html =
		"Standard: ".$renderer->render($standardLink, $renderer)."</br>".
		"Heading: ".$renderer->render($headingLink, $renderer)."</br>";

	return $html;
}
