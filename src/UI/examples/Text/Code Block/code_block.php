<?php
function code_block() {
    global $DIC;
    $f = $DIC->ui()->factory();
    $renderer = $DIC->ui()->renderer();

    $some_code = file_get_contents ("index.php");
    $code = $f->text()->code($some_code);

    return $renderer->render($code);
}