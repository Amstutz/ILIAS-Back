<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

function base() {
    global $DIC;
    $f = $DIC->ui()->factory();
    $renderer = $DIC->ui()->renderer();

    $text = $f->text()->standard("Some Standard Text");
    $heading = $f->text()->heading("Some Heading");


    $html =
        "Standard: ".$renderer->render($text, $renderer)."</br>".
        "Heading: ".$renderer->render($heading, $renderer)."</br>";

    return $html;
}
