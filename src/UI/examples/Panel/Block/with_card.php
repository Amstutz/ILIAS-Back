<?php

function with_card() {
    global $DIC;
    $f = $DIC->ui()->factory();
    $renderer = $DIC->ui()->renderer();

    $block = $f->panel()->block("Block Panel Title",$f->generic()->html("Some Content"))->withCard(
        $f->card("Card Heading","Card Content"));

    return $renderer->render($block);
}
