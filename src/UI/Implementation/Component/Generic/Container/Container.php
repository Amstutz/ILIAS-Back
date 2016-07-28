<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Generic\Container;

use ILIAS\UI\Component as C;

class Container implements C\Generic\Container {
    /**
     * @var	\ILIAS\UI\Implementation\Component\Component[]
     */
    private $components;

    /**
     * Container constructor.
     * @param \ILIAS\UI\Implementation\Component\Component[]
     */
    public function __construct($components) {
        $this->components = $components;
    }

    /**
     * @return string
     */
    public function getComponents(){
        return $this->components;
    }
}
?>