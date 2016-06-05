<?php
namespace ILIAS\UI\Component\Listing;
/**
 * This is how a factory for glyphs looks like.
 */
interface Factory {

    /**
     * ---
     * title: Unordered
     * ----
     * @return  \ILIAS\UI\Component\Listing\SimpleList
     */
    public function unordered($items);

    /**
     * ---
     * title: Ordered
     * ----
     * @return  \ILIAS\UI\Component\Listing\SimpleList
     */
    public function ordered($items);

    /**
     * ---
     * title: Descriptive
     * description:
     *   purpose: >
     *     Descriptive Lists are used to display key-value doubles of textual-information.
     *   composition: >
     *     Descriptive Lists are composed of a key acting as title describing the type of
     *     information being displayed underneath.
     * ----
     * @return  \ILIAS\UI\Component\Listing\DescriptiveList
     */
    public function descriptive($items);

}