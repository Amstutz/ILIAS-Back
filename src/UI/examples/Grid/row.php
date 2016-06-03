<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

function row() {
    global $DIC;
    $f = $DIC->ui()->factory();
    $renderer = $DIC->ui()->renderer();

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
