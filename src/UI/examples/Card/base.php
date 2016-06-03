<?php

/* Copyright (c) 2016 Richard Klees <richard.klees@concepts-and-training.de> Extended GPL, see docs/LICENSE */

function card_base() {
    global $DIC;
    $f = $DIC["UIFactory"]; // this should be $DIC->UI()->Factory();
    $renderer = $DIC["UIRenderer"]; // this should be $DIC->UI()->Renderer();

    $image = $f->image()->responsive("./templates/default/images/logo/ilias_logo_114x114.png", "Thumbnail Example");

    $card = $f->card("Title", "Content",$image);

    return "Test Thumbnail: ".$renderer->render($card, $renderer)."</br>";
}
