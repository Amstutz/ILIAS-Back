<?php

function base() {
    global $DIC;
    $f = $DIC->ui()->factory();
    $renderer = $DIC->ui()->renderer();

    $ordered = $f->listing()->ordered(
        array(
            $f->text()->standard("Point 1"),
            $f->text()->standard("Point 2"),
            $f->text()->standard("Point 3")
        )
    );

    return $renderer->render($ordered);
}
