<?php

function base_text_block() {
    global $DIC;
    $f = $DIC->ui()->factory();
    $renderer = $DIC->ui()->renderer();

    $block = $f->panel()->block("Block Panel Title",$f->text()->standard("Some Content"));

    return $renderer->render($block);
}
