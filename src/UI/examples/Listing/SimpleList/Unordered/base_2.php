<?php

function base_2() {
    //Init Factory and Renderer
    global $DIC;
    $f = $DIC->ui()->factory();
    $renderer = $DIC->ui()->renderer();

    //Generate List
    $unordered = $f->listing()->unordered(
        array(
            $f->text()->standard("Point 1"),
            $f->text()->standard("Point 2"),
            $f->text()->standard("Point 3")
        )
    );

    //Render
    return $renderer->render($unordered);
}
