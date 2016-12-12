<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

require_once(__DIR__ . "/../../../../libs/composer/vendor/autoload.php");
require_once(__DIR__ . "/../../Base.php");

use \ILIAS\UI\Component as C;
use \ILIAS\UI\Component\Filter\ValidationMessageCollector;

/**
 * Specification for semantics of Filter\Item
 */
class FilterItemTest extends ILIAS_UI_TestBase {

	/**
	 * @return \ILIAS\UI\Implementation\Factory
	 */
	public function getFactory() {
		return new \ILIAS\UI\Implementation\Factory();
	}


	/**
	 * @return \ILIAS\UI\Component\Filter\Factory
	 */
	public function factory() {
		return $this->getFactory()->filter();
	}


	/**
	 * There will be some names in the $_POST-array chosen by the implementation
	 * of filter and item. This should create an appropriate array where the values
	 * are given in the order of the filter items in the filter.
	 */
	public function create_post_array(array $values) {
		throw new \LogicException("Implement me!");
	}


	public function test_base() {
		$f = $this->factory();

		$filter = $f->filter([ $f->text("label", "") ]);

		$some_string = "hello world!";
		$inputs = $this->create_post_array([ $some_string ]);
		$with_inputs = $filter->withInputs($inputs);
		$this->assertTrue($with_inputs->hasValidContent());
		$this->assertCount(0, $with_inputs->validationErrors());
		$this->assertEquals([ $some_string ], $with_inputs->content());
	}


	public function test_two_inputs() {
		$f = $this->factory();

		$filter = $f->filter([ $f->text("label", ""), $f->text("second_label", "") ]);

		$some_string1 = "hello world!";
		$some_string2 = "hello alex!";
		$inputs = $this->create_post_array([ $some_string1, $some_string2 ]);
		$with_inputs = $filter->withInputs($inputs);
		$this->assertTrue($with_inputs->hasValidContent());
		$this->assertCount(0, $with_inputs->validationErrors());
		$this->assertEquals([ $some_string1, $some_string2 ], $with_inputs->content());
	}


	public function test_with_extractor() {
		$f = $this->factory();

		$some_string1 = "hello world!";
		$some_string2 = "hello world!";
		$filter = $f->filter([
			$f->text("label", "")->withExtractor(function ($res) use ($some_string1, $some_string2) {
				$this->assertEquals($some_string1, $res);

				return $some_string2;
			}),
		]);

		$inputs = $this->create_post_array([ $some_string1 ]);
		$with_inputs = $filter->withInputs($inputs);
		$this->assertTrue($with_inputs->hasValidContent());
		$this->assertCount(0, $with_inputs->validationErrors());
		$this->assertEquals([ $some_string2 ], $with_inputs->content());
	}


	public function test_with_two_extractor() {
		$f = $this->factory();

		$some_string1 = "hello world!";
		$some_string2 = "hello alex!";
		$some_string3 = "hello?";
		$filter = $f->filter([
			$f->text("label", "")->withExtractor(function ($res) use ($some_string1, $some_string2) {
				$this->assertEquals($some_string1, $res);

				return $some_string2;
			})->withExtractor(function ($res) use ($some_string2, $some_string3) {
				$this->assertEquals($some_string2, $res);

				return $some_string3;
			}),
		]);

		$inputs = $this->create_post_array([ $some_string1 ]);
		$with_inputs = $filter->withInputs($inputs);
		$this->assertTrue($with_inputs->hasValidContent());
		$this->assertCount(0, $with_inputs->validationErrors());
		$this->assertEquals([ $some_string3 ], $with_inputs->content());
	}


	public function test_with_validator_valid() {
		$f = $this->factory();

		$some_string = "hello world!";
		$filter = $f->filter([
			$f->text("label", "")->withValidator(function ($res, ValidationMessageCollector $collector) use ($some_string) {
				$this->assertEquals($some_string, $res);
			}),
		]);

		$inputs = $this->create_post_array([ $some_string ]);
		$with_inputs = $filter->withInputs($inputs);
		$this->assertTrue($with_inputs->hasValidContent());
		$this->assertCount(0, $with_inputs->validationErrors());
		$this->assertEquals([ $some_string ], $with_inputs->content());
	}


	public function test_with_validator_invalid() {
		$f = $this->factory();

		$some_string = "hello world!";
		$some_error_message = "error";
		$filter = $f->filter([
			$f->text("label", "")->withValidator(function ($res, ValidationMessageCollector $collector) use ($some_string, $some_error_message) {
				$this->assertEquals($some_string, $res);
				$collector->error($some_error_message);
			}),
		]);

		$inputs = $this->create_post_array([ $some_string ]);
		$with_inputs = $filter->withInputs($inputs);
		$this->assertFalse($with_inputs->hasValidContent());
		$this->assertEquals([ $some_error_message ], $with_inputs->validationErrors());
		try {
			$with_inputs->content();
			$this->assertFalse("This should not happen!");
		} catch (\LogicException $e) {
		}
	}


	public function test_with_validator_invalid_two_errors() {
		$f = $this->factory();

		$some_string = "hello world!";
		$some_error_message1 = "error1";
		$some_error_message2 = "error2";
		$filter = $f->filter([
			$f->text("label", "")
			  ->withValidator(function ($res, ValidationMessageCollector $collector) use ($some_string, $some_error_message1, $some_error_message2) {
				  $this->assertEquals($some_string, $res);
				  $collector->error($some_error_message1);
				  $collector->error($some_error_message2);
			  }),
		]);

		$inputs = $this->create_post_array([ $some_string ]);
		$with_inputs = $filter->withInputs($inputs);
		$this->assertFalse($with_inputs->hasValidContent());
		$this->assertEquals([ $some_error_message1, $some_error_message2 ], $with_inputs->validationErrors());
		try {
			$with_inputs->content();
			$this->assertFalse("This should not happen!");
		} catch (\LogicException $e) {
		}
	}


	public function test_with_two_validator_invalid() {
		$f = $this->factory();

		$some_string = "hello world!";
		$some_error_message1 = "error";
		$some_error_message2 = "error2";
		$filter = $f->filter([
			$f->text("label", "")->withValidator(function ($res, ValidationMessageCollector $collector) use ($some_string, $some_error_message1) {
				$this->assertEquals($some_string, $res);
				$collector->error($some_error_message1);
			})->addValidator(function ($res, ValidationMessageCollector $collector) use ($some_string, $some_error_message2) {
				$this->assertEquals($some_string, $res);
				$collector->error($some_error_message2);
			}),
		]);

		$inputs = $this->create_post_array([ $some_string ]);
		$with_inputs = $filter->withInputs($inputs);
		$this->assertFalse($with_inputs->hasValidContent());
		$this->assertEquals([ $some_error_message1, $some_error_message2 ], $with_inputs->validationErrors());
		try {
			$with_inputs->content();
			$this->assertFalse("This should not happen!");
		} catch (\LogicException $e) {
		}
	}
}
