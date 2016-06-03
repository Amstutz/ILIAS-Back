<?php

/* Copyright (c) 2016 Richard Klees <richard.klees@concepts-and-training.de> Extended GPL, see docs/LICENSE */

function link_base() {
    global $DIC;
    $f = $DIC["UIFactory"]; // this should be $DIC->UI()->Factory();
    $renderer = $DIC["UIRenderer"]; // this should be $DIC->UI()->Renderer();

    $standardLink = $f->link("http://www.google.ch",$f->text()->standard("GOOGLE"));
    $headingLink = $f->link("http://www.google.ch",$f->text()->heading("GOOGLE"));


    $html =
        "Standard: ".$renderer->render($standardLink, $renderer)."</br>".
        "Heading: ".$renderer->render($headingLink, $renderer)."</br>";

    return $html;
}
