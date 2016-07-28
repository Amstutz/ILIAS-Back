<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Grid\Row;


class Row implements \ILIAS\UI\Component\Grid\Row {

    private $columns;

    public function __construct($columns) {
        $this->columns = $columns;
    }

    public function withColumns($columns){
        $clone = clone $this;
        $this->columns = $columns;
        return $clone;
    }

    public function getColumns() {
        return $this->columns;
    }
}
?>