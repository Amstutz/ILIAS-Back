<?php
/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */


namespace ILIAS\UI\Component\Text;

/**
 */
interface Text extends \ILIAS\UI\Component\Component {
    // Types:
    const STANDARD = "standard";
    const HEADING = "heading";


    public function getType();

    public function withType($type);

    public function withText($text);

    public function getText();
}