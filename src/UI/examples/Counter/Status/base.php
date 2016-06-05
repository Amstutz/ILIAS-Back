<?php
function base() {
    global $DIC;
    $f = $DIC->ui()->factory();
    $renderer = $DIC->ui()->renderer();

    return $renderer->render($f->glyph()->envelope()
        ->withCounter($f->counter()->status(3)));
}
