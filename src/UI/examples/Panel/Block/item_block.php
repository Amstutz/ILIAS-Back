<?php

function item_block() {
    global $DIC;
    $f = $DIC->ui()->factory();
    $renderer = $DIC->ui()->renderer();

    $blockPanel2 = $f->panel()->block("Block Panel Title", array(
            $f->text()->standard("Ordered List"),
            $f->listing()->ordered(array("item 1","item 2","item 3")),
            $f->text()->standard("Unordered List"),
            $f->listing()->unordered(array("item 1","item 2","item 3"))
        )
    );

    return $renderer->render($blockPanel2);
}
