<?php

function report() {
    //Init Factory and Renderer
    global $DIC;
    $f = $DIC->ui()->factory();
    $renderer = $DIC->ui()->renderer();

    //Generate Bulletin
    $ordered_list = $f->listing()->ordered(array(
            "Point 1","Point 2","Point 3"
        )
    );

    $card = $f->card("ILIAS", "Everybody loves ILIAS",
        $f->image()->responsive("./templates/default/images/logo/ilias_logo_114x114.png", "Card Example")
    );

    $block_panel_1 = $f->panel()->block("Title of Block 1",
        $ordered_list
    )->withCard($card);

    $block_panel_2 = $f->panel()->block("Title of Block 2",
            $f->listing()->descriptive(
                array(
                    "Description 1"=>"Point 1",
                    "Description 2"=>"Point 2",
                    "Description 3"=>"Point 3"
                )
            )
    );

    $content = $f->generic()->container(array($block_panel_1,$block_panel_2));
    $report = $f->panel()->report("Bulletin for the Win", $content);

    return $renderer->render($report);
}
