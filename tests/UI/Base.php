<?php

/* Copyright (c) 2016 Richard Klees <richard.klees@concepts-and-training.de> Extended GPL, see docs/LICENSE */

require_once(__DIR__."/../../libs/composer/vendor/autoload.php");

require_once(__DIR__."/Mock/ilIndependentTemplate.php");
require_once(__DIR__."/../../Services/Language/classes/class.ilLanguage.php");


/**
 * Provides common functionality for UI tests.
 */
class ILIAS_UI_TestBase extends PHPUnit_Framework_TestCase {
	public function setUp() {
		assert_options(ASSERT_WARNING, 0);
		assert_options(ASSERT_CALLBACK, null);
	}

	public function tearDown() {
		assert_options(ASSERT_WARNING, 1);
		assert_options(ASSERT_CALLBACK, null);
	}

	public function getUIFactory() {
		return new NoUIFactory();
	}

	public function getTemplateFactory() {
		return new ilIndependentTemplateFactory();
	}

	public function getResourceRegistry() {
		return new LoggingRegistry();
	}

	public function getLanguage() {
		return new ilLanguageMock();
	}

	public function getDefaultRenderer() {
		$ui_factory = $this->getUIFactory();
		$tpl_factory = $this->getTemplateFactory();
		$resource_registry = $this->getResourceRegistry();
		$lng = $this->getLanguage();
		return new DefaultRendererTesting(
						$ui_factory, $tpl_factory, $resource_registry, $lng);
	}

	public function normalizeHTML($html) {
		return trim(str_replace("\n", "", $html));
	}        
}
