<?php

function block() {
    global $DIC;
    $f = $DIC->ui()->factory();
    $renderer = $DIC->ui()->renderer();

    $ordered_list = $f->listing()->ordered(array(
            $f->text()->standard("Point 1"),$f->text()->standard("Point 2"),$f->text()->standard("Point 3")
        )
    );

    $card = $f->card("ILIAS", "Everybody loves ILIAS",
        $f->image()->responsive("./templates/default/images/logo/ilias_logo_114x114.png", "Card Example")
    );

    $block_panel_1 = $f->panel()->block("Title of Block 1",
        array($ordered_list)
    )->withCard($card);

    $block_panel_2 = $f->panel()->block("Title of Block 2",
        array(
            $f->listing()->descriptive(
                array(
                    "Description 1"=>$f->text()->standard("Point 1"),
                    "Description 2"=>$f->text()->standard("Point 2"),
                    "Description 3"=>$f->text()->standard("Point 3")
                )
            )
        )
    );

    $bulletin = $f->panel()->bulletin("Bulletin for the Win", array($block_panel_1,$block_panel_2));

    return $renderer->render($bulletin);
}
