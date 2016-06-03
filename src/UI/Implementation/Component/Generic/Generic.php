<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Generic;

use ILIAS\UI\Component as C;

class Generic implements C\Generic\Generic {

    /**
     * @var	string
     */
    private $html;


    public function __construct($html) {
        $this->html = $html;
    }

    /**
     * @return string
     */
    public function getHtml(){
        return $this->html;
    }

}
?>