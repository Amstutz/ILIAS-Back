<?php

/* Copyright (c) 2016 Richard Klees <richard.klees@concepts-and-training.de> Extended GPL, see docs/LICENSE */

function text_base() {
    global $DIC;
    $f = $DIC["UIFactory"]; // this should be $DIC->UI()->Factory();
    $renderer = $DIC["UIRenderer"]; // this should be $DIC->UI()->Renderer();

    $text = $f->text()->standard("Some Standard Text");
    $heading = $f->text()->heading("Some Heading");


    $html =
        "Standard: ".$renderer->render($text, $renderer)."</br>".
        "Heading: ".$renderer->render($heading, $renderer)."</br>";

    return $html;
}
