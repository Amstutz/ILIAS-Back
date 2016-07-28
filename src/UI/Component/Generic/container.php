<?php
/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */


namespace ILIAS\UI\Component\Generic;

/**
 */
interface Container extends \ILIAS\UI\Component\Component {
    /**
     * Get Components contained by this component.
     *
     * @return	\ILIAS\UI\Component\Component
     */
    public function getComponents();
}

