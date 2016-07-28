<?php
/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */


namespace ILIAS\UI\Component\Generic;

/**
 */
interface Html extends \ILIAS\UI\Component\Component {
    /**
     * Get HTML stored in this component.
     *
     * @return	string
     */
    public function getHtml();
}

