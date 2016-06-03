<?php

/* Copyright (c) 2016 Richard Klees <richard.klees@concepts-and-training.de> Extended GPL, see docs/LICENSE */

function grid_row() {
    global $DIC;
    $f = $DIC["UIFactory"]; // this should be $DIC->UI()->Factory();
    $renderer = $DIC["UIRenderer"]; // this should be $DIC->UI()->Renderer();

    $row1 = $f->grid()->row(
        array(
            $f->grid()->column(array(
                $f->text()->standard("Row 1 Column 1")
            ),3),
            $f->grid()->column(array(
                $f->text()->standard("Row 1 Column 2")
            ),3),
            $f->grid()->column(array(
                $f->text()->standard("Row 1 Column 3")
            ),3),
            $f->grid()->column(array(
                $f->text()->standard("Row 1 Column 4")
            ),3)
        )
    );
    $row2 = $f->grid()->row(
    array(
        $f->grid()->column(array(
            $f->text()->standard("Row 2 Column 1")
        ),4),
        $f->grid()->column(array(
            $f->text()->standard("Row 2 Column 2")
        ),4),
        $f->grid()->column(array(
            $f->text()->standard("Row 2 Column 3")
        ),4))
    );
    $html =
        "Test Row 1 (4 Columns): ".$renderer->render($row1,$renderer)."</br>".
        "Test Row 2 (3 Columns): ".$renderer->render($row2,$renderer)."</br>";


    return $html;
}
