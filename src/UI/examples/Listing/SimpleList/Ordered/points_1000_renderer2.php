<?php
use ILIAS\UI\Implementation\Component\Listing\SimpleList as SimpleList;

function points_1000_renderer2() {
    //Init Factory and Renderer
    global $DIC;
    $f = $DIC->ui()->factory();
    $renderer = new SimpleList\Renderer2();

    //Generate List
    $list = array();

    for($i  = 0; $i< 1000; $i++){
        $list[] = "Point ".$i;

    }
    $ordered = $f->listing()->ordered($list);

    //Render
    return $renderer->render($ordered);
}
