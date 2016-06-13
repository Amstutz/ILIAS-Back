<?php

function points_1000() {
    //Init Factory and Renderer
    global $DIC;
    $f = $DIC->ui()->factory();
    $renderer = $DIC->ui()->renderer();

    //Generate List
    $list = array();

    for($i  = 0; $i< 1000; $i++){
        $list[] = "Point ".$i;

    }
    $ordered = $f->listing()->ordered($list);

    //Render
    return $renderer->render($ordered);
}
