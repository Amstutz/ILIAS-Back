<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

function grid() {
    //Init Factory and Renderer
    global $DIC;
    $f = $DIC->ui()->factory();
    $renderer = $DIC->ui()->renderer();

    //Generate Card and Grid
    $image = $f->image()->responsive(
        "./templates/default/images/logo/ilias_logo_114x114.png", "Thumbnail Example");

    $card = $f->card("Title", "Content",$image);

    $column = $f->grid()->column($card,2);

    $row = $f->grid()->row(array(
        $column,$column,$column,$column,$column,$column
    ));


    //Render
    return $renderer->render($row).$renderer->render($row);
}
