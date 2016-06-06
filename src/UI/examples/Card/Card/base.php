<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

function base() {
    //Init Factory and Renderer
    global $DIC;
    $f = $DIC->ui()->factory();
    $renderer = $DIC->ui()->renderer();

    //Generate Card
    $image = $f->image()->responsive(
        "./templates/default/images/logo/ilias_logo_114x114.png", "Thumbnail Example");
    $card = $f->card("Title", "Content",$image);

    //Render
    return $renderer->render($card);
}
