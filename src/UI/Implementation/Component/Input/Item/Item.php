<?php

namespace ILIAS\UI\Implementation\Component\Input\Item;

use ILIAS\UI\Implementation\Component\ComponentHelper;

/**
 * One item in the filter, might be composed from different input elements,
 * which all act as one filter input.
 */
class Item  {
	use ComponentHelper;

	/**
	 * @var bool
	 */
	protected $required = false;

	/**
	 * @var callable
	 */
	protected $validator = null;

	/**
	 * @var mixed
	 */
	protected $content = null;

	/**
	 * @var string
	 */
	protected $label = "";

	/**
	 * @var string
	 */
	protected $title = "";

	/**
	 * @inheritdoc
	 */
	public function __construct($label) {
		$this->checkStringArg("label",$label);
		$this->label = $label;
	}

	/**
	 * @inheritdocs
	 */
	public function label(){
		return $this->label;
	}

	public function withTitle($title){
		$this->checkStringArg("title",$title);

		$clone = clone $this;
		$clone->title = $title;
		return $clone;
	}

	public function title(){
		return $this->title;
	}


	/**
	 * @inheritdocs
	 */
	public function defaultsTo(){}

	/**
	 * @inheritdocs
	 */
	public function withDefault($default){}


	public function withContent($content){
		$clone = clone $this;
		$clone->content = $content;
		return $clone;
	}

	public function content(){
		if($this->validate()){
			return $this->content;
		}else{
			throw new \Exception("Invalid Content");
		}

	}


	public function validate(){
		if($this->validator){
			return call_user_func($this->validator,$this->content);
		}
		return false;
	}

	/**
	 * @inheritdocs
	 */
	public function required($required = false){
		$this->checkBoolArg("required", $required);
		$clone = clone $this;
		$clone->required = $required;
		return $clone;
	}

	public function isRequired(){
		return $this->required;
	}

	/**
	 * @inheritdocs
	 */
	public function withNoInitialVisibility(){}


	/**
	 * @inheritdocs
	 */
	public function withValidator(callable $validator){
		$this->checkCallableArg("required", $validator);
		$clone = clone $this;
		$clone->validator = $validator;
		return $clone;
	}


	/**
	 * @inheritdocs
	 */
	public function withExtractor(callable $extractor){}

}
