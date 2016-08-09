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
	 *      Heading Panels are used in the Center Content section to group content.
	 *      The structure of this content might be varying from Heading Panel to Heading Panel.
	 *   rivals:
	 *      Block Panels: >
	 *        Block Panels are used in Sidebars or as subpanels of heading panels.
	 *      Cards: >
	 *        Often Cards are used in Decks to display multiple uniformly structured chunks of Data horizontally and vertically.
	 *
	 * rules:
	 *   usage:
	 *      1: In Forms Heading Panel MUST be used  to group different sections into Form Parts.
	 *      2: Heading Panels SHOULD be used in the Center Content as primary Container for grouping content of varying content.
	 *   style:
	 *      1: Heading Sections MUST not have a Cog to enable settings.
	 *   ordering:
	 *      1: Heading Sections MUST not have a Cog to enable settings.
	 * ---
	 * @param string $body
	 * @param \ILIAS\UI\Component\Component $body
	 * @return \ILIAS\UI\Component\Panel
	 */
	public function standard($heading,$body);

	/**
	 * ---
	 * description:
	 *   purpose: >
	 *       Sub Panels are used to structure the content of Standard panels further into titled sections.
	 *   composition: >
	 *       Sub Panels consist of a title and a content section. They may contain a Card on their right side to display
	 *       meta information about the content displayed.
	 *   rivals: >
	 *      Standard Panel: >
	 *        The Standard Panel might contain a Sub Panel.
	 *      Card: >
	 *        The Sub Panels may contain one card.
	 *
	 * rules:
	 *   usage:
	 *      1: Sub Panels MUST only be inside Standard Panels
	 *   composition:
	 *      1: Sub Panels MUST NOT contain Sub Panels or Standard Panels as content.
	 * ---
	 * @param string $title
	 * @param mixed $content \ILIAS\UI\Component\Component[] | \ILIAS\UI\Component\Component
	 * @return \ILIAS\UI\Component\Panel\Sub
	 */
	public function sub($title,$content);

	/**
	 * ---
	 * title: Report
	 * description:
	 *   purpose: >
	 *       Report Panels display user-generated data combining text in lists, tables and sometimes  charts.
	 *       Bulletins always draws from two distinct sources: the structure / scaffolding of the Report Panels
	 *       stems from user-generated content (i.e a question of a survey, a competence with levels) and is
	 *       filled with user-generated content harvested by that very structure (i.e. participants’ answers to
	 *       the question, self-evaluation of competence).
	 *   composition: >
	 *       They comprise the structured content used to harvest input and the harvested input alongside:
	 *       the text of the harvesting structure is given in full length with a headline,
	 *       label of subitems, text of subitems in bulleted lists. This may be followed by a table
	 *       comprising or aggregating the harvested input. This might be followed by a picture or a graph.
	 *       They are composed of a heading panel which may contain several block panels. They might also cantain
	 *       a card to display information requiring special information in their first block.
	 *   effect: >
	 *       Report Panels are predominantly used for displaying data. They may however comprise links or buttons.
	 *
	 * context: >
	 *      Heading Panels: >
	 *        The Report Panels contains one heading panel containing the block panels used to structure information.
	 *      Cards: >
	 *        The Report Panels may contain one card in it's first block.
	 *
	 * rules:
	 *   usage:
	 *      1: Report Panels are to be used when user generated content of two sources (i.e results, guidelines in a template) is to be displayed alongside each other.
	 *   interaction:
	 *      1: Links MAY open new views.
	 *      2: Buttons MAY trigger actions or inline editing.
	 * ---
	 * @param string $body
	 * @param \ILIAS\UI\Component\Component $body
	 * @return \ILIAS\UI\Component\Panel
	 */
	public function report($heading,$blocks);
}
