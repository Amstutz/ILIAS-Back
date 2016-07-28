<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Component\Image;

/**
 * This describes how a glyph could be modified during construction of UI.
 */
interface Image extends \ILIAS\UI\Component\Component {
    // Types:
    const ROUNDED = "rounded";
    const CIRCLE = "circle";
    const THUMBNAIL = "thumbnail";
    const RESPONSIVE = "responsive";
    const STANDARD = "standard";

    public function withSource($source);

    public function getSource();

    public function withType($type);

    public function getType();

    public function withAlt($alt);

    public function getAlt();

}
