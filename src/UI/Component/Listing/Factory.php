<?php
namespace ILIAS\UI\Component\Listing;
/**
 * This is how a factory for glyphs looks like.
 */
interface Factory {

    /**
     * ---
     * description:
     *   purpose: >
     *     Unordered Lists are used to display a unordered set of textual elements.
     *   composition: >
     *     Unordered Lists are composed of a set of bullets labeling the listed items.
     * ----
     * @return  \ILIAS\UI\Component\Listing\SimpleList
     */
    public function unordered($items);

    /**
     * ---
     * description:
     *   purpose: >
     *     Ordered Lists are used to displayed a numbered set of textual elements. They are used if the order of the
     *     elements is relevant.
     *   composition: >
     *     Ordered Lists are composed of a set of numbers labeling the items enumerated.
     * ----
     * @return  \ILIAS\UI\Component\Listing\SimpleList
     */
    public function ordered($items);

    /**
     * ---
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