<?php

/* Copyright (c) 2016 Richard Klees <richard.klees@concepts-and-training.de> Extended GPL, see docs/LICENSE */

function listing_base() {
    global $DIC;
    $f = $DIC["UIFactory"]; // this should be $DIC->UI()->Factory();
    $renderer = $DIC["UIRenderer"]; // this should be $DIC->UI()->Renderer();

    $unordered = $f->listing()->unordered(
        array(
            $f->text()->standard("Point 1"),$f->text()->standard("Point 2"),$f->text()->standard("Point 3")
        )
    );
    $ordered = $f->listing()->ordered(
        array(
            $f->text()->standard("Point 1"),$f->text()->standard("Point 2"),$f->text()->standard("Point 3")
        )
    );
    $descriptive = $f->listing()->descriptive(
        array(
            array($f->text()->standard("Description 1"),$f->text()->standard("Point 1")),
            array($f->text()->standard("Description 2"),$f->text()->standard("Point 2")),
            array($f->text()->standard("Description 3"),$f->text()->standard("Point 3"))
        )
    );


    $html =
        "Unordered: ".$renderer->render($unordered, $renderer).
        "Ordered: ".$renderer->render($ordered, $renderer).
        "Descriptive: ".$renderer->render($descriptive, $renderer);

    return $html;
}
