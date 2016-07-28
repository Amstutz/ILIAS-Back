<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Component\Deck;

interface Deck extends \ILIAS\UI\Component\Component {
	const SIZE_XS = 1;
	const SIZE_S = 2;
	const SIZE_M = 3;
	const SIZE_L = 4;
	const SIZE_XL = 6;
	const SIZE_FULL = 12;

	/**
	 * @param \ILIAS\UI\Component\Card\Card[] $cards
	 * @return Deck
	 */
	public function withCards($cards);

	/***
	 * @return \ILIAS\UI\Component\Card\Card[]
	 */
	public function getCards();

	/**
	 * @return Deck
	 */
	public function withCardsSize($size);

	/**
	 * @return int
	 */
	public function getCardsSize();
}
