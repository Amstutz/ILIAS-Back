<?php
namespace ILIAS\UI\Implementation\Component\Input\Validation;
use \ILIAS\UI\Component\Input\Validation as V;

/**
 * Todo
 */
class ValidationMessage implements V\ValidationMessage{
	/**
	 * @var \ILIAS\UI\Component\Input\Item\Item
	 */
	protected $item = null;
	/**
	 * @var string
	 */
	protected $message = "";
	/**
	 * @inheritdoc
	 */
	public function __construct($message,$item) {
		$this->message = $message;
		$this->item = $item;
	}
	public function getMessage(){
		return $this->message;
	}
	public function getItem(){
		return $this->item;
	}
}