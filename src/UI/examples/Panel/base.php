<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

function base() {
    global $DIC;
    $f = $DIC->ui()->factory();
    $renderer = $DIC->ui()->renderer();


    $orderedList = $f->listing()->ordered(array(
            $f->text()->standard("Point 1"),$f->text()->standard("Point 2"),$f->text()->standard("Point 3")
        )
    );


    $card = $f->card("ILIAS", "Everybody loves ILIAS",
        $f->image()->responsive("./templates/default/images/logo/ilias_logo_114x114.png", "Card Example")
    );

    $row = $f->grid()->row(
        array(
            $f->grid()->column(array($orderedList),10),
            $f->grid()->column(array($card),2),
        )
    );


    $blockPanel1 = $f->panel()->block("Title of Block 1",
        array($row)
    );

    $blockPanel2 = $f->panel()->block("Title of Block 2",
        array(
            $f->listing()->descriptive(
                array(
                    array($f->text()->standard("Description 1"),$f->text()->standard("Point 1")),
                    array($f->text()->standard("Description 2"),$f->text()->standard("Point 2")),
                    array($f->text()->standard("Description 3"),$f->text()->standard("Point 3"))
                )
            )
        )
    );

    $bulletin = $f->panel()->bulletin("Bulletin for the Win", array($blockPanel1,$blockPanel2));

    return $renderer->render($bulletin, $renderer);
}
