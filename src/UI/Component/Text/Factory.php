<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Component\Text;

/**
 * This is how the factory for UI elements looks. This should provide access
 * to all UI elements at some point.
 */
interface Factory {

    /**
     * ---
     * ----
     * @return \ILIAS\UI\Component\Text
     */
    public function standard($text);

    /**
     * ---
     * title: Standard Heading
     * ----
     * @return \ILIAS\UI\Component\Text
     */
    public function heading($text);

    /**
     * ---
     * description:
     *   purpose: >
     *       Use if formated code needs to be represented in a readable manner including syntax highlighting.
     *   composition: >
     *       Contains formatted code.
     *   effect: >
     *       Renders the given code and highlights it syntax.
     * ----
     * @return \ILIAS\UI\Component\Text
     */
    public function code($text);
}
