<?php

require_once(__DIR__."/../../Factory/AbstractFactoryTest.php");

class ButtonFactoryTest extends AbstractFactoryTest {
	public $kitchensink_info_settings = array
		( "standard"	=> array("context" => false)
		, "close"		=> array("context" => false)
		);

	public $factory_title = 'ILIAS\\UI\\Component\\Button\\Factory';
}
