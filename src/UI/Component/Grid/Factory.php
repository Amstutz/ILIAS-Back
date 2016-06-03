<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Component\Grid;

/**
 * This is how the factory for UI elements looks. This should provide access
 * to all UI elements at some point.
 * Todo: Rows and Columns are both parts of the Grid. They are not Grids themselves. Should this be named Grid Component?
 */
interface Factory {

    /**
     * ---
     * title: Grid Row
     * ----
     * @return \ILIAS\UI\Component\Grid\Row
     */
    public function row($columns);

    /**
     * ---
     * title: Grid Column
     * ----
     * @param $content
     * @param int $width
     * @return \ILIAS\UI\Component\Grid\Column
     */

    public function column($content,$width = 12);
}
