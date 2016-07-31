<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Component\Panel;

/**
 * This describes how a panel could be modified during construction of UI.
 */
interface Panel extends \ILIAS\UI\Component\Component {
    // Types of glyphs:
    const BLOCK = "default";
    const HEADING = "primary";
    const REPORT = "primary";

    /**
     * Get the type of the panel.
     *
     * @return	string
     */
    public function getType();

    /**
     * Get a panel like this, but with a new type.
     *
     * @param	string	$type onne of the panel types.
     * @return	Glyph
     */
    public function withType($type);


    /**
     * @param string $heading
     * @return \ILIAS\UI\Component\Panel
     */
    public function withHeading($heading);

    /**
     * @return string
     */
    public function getHeading();

    /**
     * @param \ILIAS\UI\Component\Component $body
     * @return \ILIAS\UI\Component\Panel
     */
    public function withBody($body);

    /**
     * @return \ILIAS\UI\Component\Component
     */
    public function getBody();
}
