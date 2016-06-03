<?php
namespace ILIAS\UI\Component\Listing;
/**
 * This is how a factory for glyphs looks like.
 */
interface Factory {

    /**
     * ---
     * title: Unordered List
     * ----
     * @return  \ILIAS\UI\Component\Listing\SimpleList
     */
    public function unordered($items);

    /**
     * ---
     * title: Ordered List
     * ----
     * @return  \ILIAS\UI\Component\Listing\SimpleList
     */
    public function ordered($items);

    /**
     * ---
     * title: Descriptive List
     * ----
     * @return  \ILIAS\UI\Component\Listing\DescriptiveList
     */
    public function descriptive($items);

}