<?php
use ILIAS\UI\Factory;
/**
 * Class NoUIFactory
 */
class NoUIFactory implements Factory {
    public function counter() {}
    public function glyph() {}
    public function button() {}
    public function card($title, \ILIAS\UI\Component\Image\Image $image = null) {}
    public function deck(array $cards) {}
    public function listing() {}
}