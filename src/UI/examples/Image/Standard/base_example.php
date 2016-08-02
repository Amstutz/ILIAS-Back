<?php
/**
 * Base Example for rendering an Image
 */
function base_example() {
    //Loading factories
    global $DIC;
    $f = $DIC->ui()->factory();
    $renderer = $DIC->ui()->renderer();

    //Genarating and rendering the image
    $image = $f->image()->standard(
        "./templates/default/images/HeaderIcon.svg",
        "Thumbnail Example");
    $html = $renderer->render($image);

    return $html;
}
