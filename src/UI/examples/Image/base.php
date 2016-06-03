<?php

/* Copyright (c) 2016 Richard Klees <richard.klees@concepts-and-training.de> Extended GPL, see docs/LICENSE */

function image_base() {
    global $DIC;
    $f = $DIC["UIFactory"]; // this should be $DIC->UI()->Factory();
    $renderer = $DIC["UIRenderer"]; // this should be $DIC->UI()->Renderer();

    $thumbnail1 = $f->image()->standard("./templates/default/images/logo/ilias_logo_114x114.png", "Thumbnail Example");
    $thumbnail2 = $f->image()->rounded("./templates/default/images/logo/ilias_logo_114x114.png", "Thumbnail Example");
    $thumbnail3 = $f->image()->circle("./templates/default/images/logo/ilias_logo_114x114.png", "Thumbnail Example");
    $thumbnail4 = $f->image()->responsive("./templates/default/images/logo/ilias_logo_114x114.png", "Thumbnail Example");
    $thumbnail5 = $f->image()->thumbnail("./templates/default/images/logo/ilias_logo_114x114.png", "Thumbnail Example");


    $html =
        "Test Thumbnail Standard: ".$renderer->render($thumbnail1, $renderer)."</br>".
        "Test Thumbnail Rounded: ".$renderer->render($thumbnail2, $renderer)."</br>".
        "Test Thumbnail Circle: ".$renderer->render($thumbnail3, $renderer)."</br>".
        "Test Thumbnail Responsive: ".$renderer->render($thumbnail4, $renderer)."</br>".
        "Test Thumbnail Thumbnail: ".$renderer->render($thumbnail5, $renderer)."</br>";

    return $html;
}
