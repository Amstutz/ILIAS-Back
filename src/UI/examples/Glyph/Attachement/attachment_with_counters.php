<?php

/* Copyright (c) 2016 Richard Klees <richard.klees@concepts-and-training.de> Extended GPL, see docs/LICENSE */

function attachment_with_counters() {
	global $DIC;
	$f = $DIC->ui()->factory();
	$renderer = $DIC->ui()->renderer();

	$attachment = $f->glyph()->attachment()
						->withCounter($f->counter()->status(1))
						->withCounter($f->counter()->novelty(2));

	return "Attachment with counters: ".$renderer->render($attachment)."</br>";
}
