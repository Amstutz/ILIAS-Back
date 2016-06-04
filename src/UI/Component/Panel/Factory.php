<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Component\Panel;

/**
 * This is how the factory for UI elements looks. This should provide access
 * to all UI elements at some point.
 */
interface Factory {

    /**
     * ---
     * title: Block
     * description:
     *   purpose: >
     *       Block Panels are used to display global services in Sidebars or group content in the Center Content. The might also be used to perform some nested grouping inside a Heading Panel as used in the Bulletin.
     *   composition: >
     *       The header section might contain a cog to change some settings of the content displayed in the Block Panel.
     *   effect: >
     *       Panels on the personal Desktop are sortable and might be hidden through the Cog Glyph.
     *
     * background: >
     *       Tiddwell describes the Pattern of “Collapsible Panels in which one can
     *       “Put secondary or optional material into panels that can be opened and closed by the user”.
     *       They work well to acompany a “Center Stage” where content needs to take visual priority.
     *       Hiding noncritical pieces of content helps to simplify the interface.
     *       When a user hides a module that supports the main content, it simply collapses, giving its space back over to the main content (or to whitespace).
     *       This is an example of the principle of progressive disclosure — show hidden content “just in time,” when and where the user needs it.
     *       She futher recommends “Collapsible Panels” for: (a) Content that annotates, modifies, explains, or otherwise supports the content in the main part of the page.
     *       (b) Services that may not be important enough for any of them to be open by default.
     *       Their value may vary a lot from user to user. Some will really want to see a particular service, and others won’t care about it at all.
     *       Even for one user, a service may be useful sometimes, but not other times.
     *       When it’s not open, its space is better used by the page’s main content.
     *       (c) Users that may want to open more than one service at the same time.
     *
     * rules:
     *   usage:
     *      1: Block Panels MIGHT be used inside Heading Panels to structure nested content.
     *   interaction:
     *      2: Block Panel MIGHT be moveable per Drag and Drop
     *   style:
     *      3: Block Panels MIGHT contain a cog to change settings of the content displayed inside the panel.
     * ---
     * @return \ILIAS\UI\Component\Panel
     */
    public function block($heading,$body);

    /**
     * ---
     * title: Heading
     * ---
     * @return \ILIAS\UI\Component\Panel
     */
    public function heading($heading,$body);

    /**
     * ---
     * title: Bulletin
     * ---
     * @return \ILIAS\UI\Component\Panel
     */
    public function bulletin($heading,$body);
}
