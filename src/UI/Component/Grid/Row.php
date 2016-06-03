<?php
/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */


namespace ILIAS\UI\Component\Grid;

/**
 */
interface Row extends \ILIAS\UI\Component\Component {

    public function withColumns($columns);

    public function getColumns();
}