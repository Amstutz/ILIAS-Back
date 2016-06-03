<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Component\Panel;

/**
 * This is how the factory for UI elements looks. This should provide access
 * to all UI elements at some point.
 */
interface Factory {

    /**
     * ---
     * title: Block Panel
     * ---
     * @return \ILIAS\UI\Component\Panel
     */
    public function block($heading,$body);

    /**
     * ---
     * title: Heading Panel
     * ---
     * @return \ILIAS\UI\Component\Panel
     */
    public function heading($heading,$body);

    /**
     * ---
     * title: Bulletin
     * ---
     * @return \ILIAS\UI\Component\Panel
     */
    public function bulletin($heading,$body);
}
