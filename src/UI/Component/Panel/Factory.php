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
     * ---
     * @return \ILIAS\UI\Component\Panel
     */
    public function heading($heading,$body);

    /**
     * ---
     * title: Bulletin
     * description:
     *   purpose: >
     *       Bulletin display user-generated data combining text in lists, tables and sometimes  charts.
     *       Bulletins always draws from two distinct sources: the structure / scaffolding of the bulletins
     *       stems from user-generated content (i.e a question of a survey, a competence with levels) and is
     *       filled with user-generated content harvested by that very structure (i.e. participants’ answers to
     *       the question, self-evaluation of competence)
     *   composition: >
     *       They comprise the structured content used to harvest input and the harvested input alongside:
     *       the text of the harvesting structure is given in full length with a headline,
     *       label of subitems, text of subitems in bulleted lists. This may be followed by a table
     *       comprising or aggregating the harvested input. This might be followed by a picture or a graph.
     *   effect: >
     *       Bulletinss are predominantly used for displaying data. They may however comprise links or buttons.
     *
     * context: >
     *       Review Blocks are to be used in the Results-tab of the survey,
     *       in the stamped-in Competences of a portfolio, in the Layout section of the Administration.
     *
     * rules:
     *   usage:
     *      1: Bulletins are to be used when user generated content of two sources (i.e results, guidelines in a template) is to be displayed alongside each other .
     *   interaction:
     *      2: Links MAY open new views.
     *      3: Buttons MAY trigger actions or inline editing.
     * ---
     * @return \ILIAS\UI\Component\Panel
     */
    public function bulletin($heading,$body);
}
