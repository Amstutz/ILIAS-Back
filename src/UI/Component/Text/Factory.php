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
     * title: Standard Text
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
}
