<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Component\Panel;

/**
 * This describes how a glyph could be modified during construction of UI.
 */
interface Panel extends \ILIAS\UI\Component\Component {
    // Types of glyphs:
    const BLOCK = "default";
    const HEADING = "primary";

    /**
     * Get the type of the glyph.
     *
     * @return	string
     */
    public function getType();

    /**
     * Get a glyph like this, but with a new type.
     *
     * @param	string	$type	One of the glyph types.
     * @return	Glyph
     */
    public function withType($type);



    public function withHeading($heading);

    public function getHeading();

    public function withBody($body);

    public function getBody();
}
