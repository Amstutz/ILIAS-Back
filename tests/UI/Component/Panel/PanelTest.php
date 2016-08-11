<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

require_once(__DIR__."/../../../../libs/composer/vendor/autoload.php");
require_once(__DIR__."/../../Base.php");

use \ILIAS\UI\Component as C;

class ComponentMock implements C\Component {}

/**
 * Test on button implementation.
 */
class PanelTest extends ILIAS_UI_TestBase {

	/**
	 * @return \ILIAS\UI\Implementation\Component\Panel\Factory
	 */
	public function getPanelFactory() {
		return new \ILIAS\UI\Implementation\Component\Panel\Factory();
	}



	public function test_implements_factory_interface() {
		$f = $this->get();

		$this->assertInstanceOf("ILIAS\\UI\\Component\\Panel\\Factory", $f);
		$this->assertInstanceOf
		( "ILIAS\\UI\\Component\\Panel\\Default"
				, $f->standard(array("Title"),array(new ComponentMock()))
		);
		$this->assertInstanceOf
		( "ILIAS\\UI\\Component\\Listing\\Unordered"
				, $f->sub(array("Title"),array(new ComponentMock()))
		);
		$this->assertInstanceOf
		( "ILIAS\\UI\\Component\\Listing\\Descriptive"
				, $f->standard(array("Title"),array(new ComponentMock()))
		);
	}

}
