<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

function base() {
    global $DIC;
    $f = $DIC->ui()->factory();
    $renderer = $DIC->ui()->renderer();

    $image = $f->image()->responsive("./templates/default/images/logo/ilias_logo_114x114.png", "Thumbnail Example");

    $card = $f->card("Title", "Content",$image);

    return "Test Thumbnail: ".$renderer->render($card, $renderer)."</br>";
}
