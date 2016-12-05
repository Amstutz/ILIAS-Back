<?php

namespace ILIAS\UI\Implementation\Component\Input\Item;

use ILIAS\UI\Implementation\Component\ComponentHelper;
use ILIAS\UI\Implementation\Component\Input\ValidationMessageCollector;

/**
 * One item in the filter, might be composed from different input elements,
 * which all act as one filter input.
 */
class Item  implements \ILIAS\UI\Component\Input\Item\Item{
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
        $collector = new ValidationMessageCollector();
		if($this->validate($collector)){
			return $this->content;
		}else{
            //TODO do something with the collector here?
			throw new \Exception("Invalid Content");
		}
	}


	public function validate(ValidationMessageCollector $collector){
		if($this->validator){
			return $this->validator->validate($this->content, $collector, $this);
		}
		return true;
	}

    /**
     * TODO: Cache this shorthand
     * @return bool
     */
    public function validates(){
        return $this->validate(new \ILIAS\UI\Implementation\Component\Input\Validation\ValidationMessageCollector());
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
	public function withValidator(\ILIAS\UI\Component\Input\Validation\Validation $validator){
		$clone = clone $this;
		$clone->validator = $validator;

        return $clone;
	}


	/**
	 * @inheritdocs
	 */
	public function withExtractor(callable $extractor){}

}
