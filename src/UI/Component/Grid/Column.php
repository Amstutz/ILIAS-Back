<?php
/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */


namespace ILIAS\UI\Component\Grid;

/**
 */
interface Column extends \ILIAS\UI\Component\Component {

    public function widthContent($content);

    public function getContent();

    public function widthWith($width);

    public function getWidth();
}