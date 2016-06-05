<?php

/* Copyright (c) 2015 Richard Klees <richard.klees@concepts-and-training.de> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI;

/**
 * This is how the factory for UI elements looks. This should provide access
 * to all UI elements at some point.
 *
 * Consumers of the UI-Service MUST program against this interface and not
 * use any concrete implementations from Internal.
 */
interface Factory {
	/**
	 * ---
	 * title: Counter
	 * description:
	 *   purpose: >
	 *       Counter inform users about the quantity of items indicated
	 *       by a glyph.
	 *   composition: >
	 *       Counters consist of a number and some background color and are
	 *       placed one the 'end of the line' in reading direction of the item
	 *       they state the count for.
	 *   effect: >
	 *       Counters convey information, they are not interactive.
	 *
	 * featurewiki:
	 *       - http://www.ilias.de/docu/goto_docu_wiki_wpage_3854_1357.html
	 *
	 * rules:
	 *   usage:
	 *       1: A counter MUST only be used in combination with a glyph.
	 *   composition:
	 *       2: >
	 *          A counter MUST contain exactly one number greater than zero and no
	 *          other characters.
	 * ---
	 * @return  \ILIAS\UI\Component\Counter\Factory
	 */
	public function counter();

	/**
	 * ---
	 * title: Glyph
	 * description:
	 *   purpose: >
	 *       Glyphs are used to map a generally known concept or symbol to a specific
	 *       concept in ILIAS. Glyph are used when space is scarce.
	 *   composition: >
	 *       A glyph is a typographical character that represents
	 *       something else. As any other typographical character, they can be
	 *       manipulated by regular CSS. If hovered they change to the link-hover-
	 *       color to indicate possible interactions.
	 *   effect: >
	 *       Glyphs act as trigger for some action such as opening a certain
	 *       Overlay type or as shortcut.
	 *   rivals:
	 *       icon: >
	 *           Icons are not interactive as standalone (they can be in an
	 *           interactive container however). They only serve as additional
	 *           hint of the functionality described by some title. Glyphs are
	 *           visually distinguished from object icons.
	 *
	 * background: >
	 *     "In typography, a glyph is an elemental symbol within an agreed set of
	 *     symbols, intended to represent a readable character for the purposes
	 *     of writing and thereby expressing thoughts, ideas and concepts."
	 *     (https://en.wikipedia.org/wiki/Glyph)
	 *
	 *     Lidwell states that such symbols are used "to improve the recognition
	 *     and recall of signs and controls".
	 *     (W.Lidwell,K.Holden,and J.Butler,Universal Principles of Design:
	 *     100 Ways to Enhance Usability, Influence Perception, Increase Appeal,
	 *     Make Better Design Decisions, and Teach Through Design. Rockport
	 *     Publishers, 2003, ch. Iconic Representation, pp. 110 – 111)
	 *
	 * rules:
	 *   usage:
	 *       1: Glyphs MUST NOT be used in content titles.
	 *       2: >
	 *          Glyphs MUST be used for cross-sectional functionality as mail for
	 *          example and NOT for representing objects.
	 *       3: >
	 *          Glyphs SHOULD be used for very simple tasks that are repeated at
	 *          many places throughout the system.
	 *       4: >
	 *          Services such as mail MAY be represented by a glyph AND an icon.
	 *   style:
	 *       5: >
	 *          All Glyphs MUST be taken from the Bootstrap Glyphicon Halflings
	 *          set. Exceptions MUST be approved by the JF.
	 *   accessibility:
	 *       6: >
	 *          The functionality triggered by the Glyph must be indicated to
	 *          screen readers with by the attribute aria-label. If the Glyph
	 *          accompanies some text describing the functionality of the triggered,
	 *          this MUST be indicated by the aria-labelledby attribute.
	 * ---
	 * @return  \ILIAS\UI\Component\Glyph\Factory
	 */
	public function glyph();


	/**
	 * ---
	 * title: Card
	 * description:
	 *   purpose: >
	 *     	A card is a flexible content container for small chunks of structured data.
	 *      Cards are often used in so-called Decks which are a gallery of Cards.
	 *   composition: >
	 *      Cards contain a header, which often includes an Image or Icon and a Title as well as possible actions as
	 *      Default Buttons and 0 to n sections that may contain further links and buttons.
	 *   effect: >
	 *      Cards may contain Interaction Triggers.
	 *   rivals:
	 *      Heading Panel: Heading Panels fill up the complete available width in the Center Content Section. Multiple Heading Panels are stacked vertically.
	 *      Block Panels: Block Panels are used in Sidebars
	 *
	 * featurewiki:
	 *       - http://www.ilias.de/docu/goto_docu_wiki_wpage_3208_1357.html
	 *
	 * rules:
	 *   composition:
	 *      1: Cards MUST contain a title.
	 *      2: Cards SHOULD contain an Image or Icon in the header section.
	 *      3: Cards MAY contain Interaction Triggers.
	 *   style:
	 *      1: Sections of  Cards MUST be separated by Dividers.
	 *   accessibility:
	 *      1: If multiple Cards are used, they MUST be contained in a Deck.
	 * ---
	 * Todo: Do we allow this shortcut for families with only one member?
	 * @param string $title
	 * @param string $content
	 * @param \ILIAS\UI\Component\Image\Image $image
	 * @return \ILIAS\UI\Component\Card\Card
	 */
	public function card($title, $content,\ILIAS\UI\Component\Image\Image $image = null);

	/**
	 * ---
	 * title: Image
     * description:
     *   purpose: Images are used for previous of downloads or view of user images.
	 * ---
	 * Todo: Description in Incomplete!
	 * Todo: Do we allow this shortcut for families with only one member?
	 * @return \ILIAS\UI\Component\Image\Factory
	 */
	public function image();

	/**
	 * ---
	 * title: Text
	 * description:
	 *   purpose: >
	 *      Text is read by users and transmits informative messages to them.
	 *   composition: >
	 *      Text Elements may only contain characters.
	 * rules:
	 *   wording:
	 *      1: Reading is hard thus all written information displayed to the user SHOULD be as short and as non-technical as possible.
	 * ---
	 * @return \ILIAS\UI\Component\Text\Factory
	 */
	public function text();

	/**
	 * ---
	 * title: Link
	 * description:
	 *   purpose: >
	 *      Links are navigational controls and mainly open a new view.
	 *      A ‘view’ is a context, a new and different screen compared to where the user clicked the link.
	 *      Mostly Links trigger interactions that do not leave a permanent change on the system.
	 *      There are exceptions to this rule. Links are used in Input Pickers for example,
	 *      which then result in some input for forms to be stored on the backend.
	 *   composition: >
	 *      Links are textual elements distinguished from other text by their color.
	 *      Links appear typically in lists of objects displaying their title and opening the object if clicked on.
	 *      In ILIAS links that are part of the content are underlined while links that are
	 *      controls are not underlined but merely colored differently.
	 *   effect: >
	 *       The exact effect triggered by clicking on the Link depends on its exact context.
	 *   rivals:
	 *      Buttons: Links are strongly related to Buttons. See the Button entry for an explanation about their difference.
	 *      Glyphs: >
	 *         Are used if the enclosing Container Collection can not provide enough information for
	 *         textual information or if such an information would clutter the screen.
	 *
	 * rules:
	 *   composition:
	 *     1: Links SHOULD only be used for navigational interactions or view changes.
	 *     2: Links MAY be used for selecting objects for carrying out an action (e.g. Picker).
	 *   style:
	 *     1: Links that are part of (user generated) content MUST be underlined, others MUST NOT.
	 * ---
	 *
	 * Todo: Do we allow this shortcut for families with only one member?
	 * @param string $href
	 * @param string $caption
	 * @return \ILIAS\UI\Component\Link\Link
	 */
	public function link($href,$caption="");

	/**
	 * ---
	 * title: Grid
	 * ---
	 * Todo: Description Incomplete
	 * Todo: Is this even an popror UI-Component? We definitely this for internal use, but should we advertise it and describe it to the outside?
	 * Todo: Pro it gives the user (probably needed) flexibility in the organization of views -> much less components will be needed.
	 * Todo: Contra we shift UI decisions to the logic part of ILIAS
	 * @return \ILIAS\UI\Component\Grid\Factory
	 */
	public function grid();

	/**
	 * ---
	 * title: Listing
     * description:
     *   purpose: >
     *     Listings are used to structure textual information.
	 * ---
	 * Todo: Description in Incomplete!
	 * @return \ILIAS\UI\Component\Listing\Factory
	 */
	public function listing();

	/**
	 * ---
	 * title: Panel
	 * description:
	 *   purpose: >
	 *     Panels are used to group titled Content.
	 *   composition: Panels consist of a header and content section.
	 *   effect: The effect of interaction with panels heavily depends on their content.
	 *
	 * rules:
	 *   wording:
	 *      1: Panels MUST contain a heading.
	 *      2: Panels MUST form one Gestalt and so build a perceivable cluster of information
	 * ---
	 * @return \ILIAS\UI\Component\Panel\Factory
	 */
	public function panel();

	/**
	 * ---
	 * title: Generic
	 * description:
	 *   purpose: Can hold any content.
	 *
	 * rules:
	 *   usage:
	 *      1: Do only this component to solve legacy issues.
	 * ---
	 * @return \ILIAS\UI\Component\Generic\Generic
	 */
	public function generic($html);
}
