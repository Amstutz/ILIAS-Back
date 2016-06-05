<?php

function base() {
    global $DIC;
    $f = $DIC->ui()->factory();
    $renderer = $DIC->ui()->renderer();

    $descriptive = $f->listing()->descriptive(
        array(
            "Description 1"=>$f->text()->standard("Point 1"),
            "Description 2"=>$f->text()->standard("Point 2"),
            "Description 3"=>$f->text()->standard("Point 3")
        )
    );

    return $renderer->render($descriptive);
}
