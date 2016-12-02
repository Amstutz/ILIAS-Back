<?php

namespace ILIAS\UI\Implementation\Component\Input\Container;

use ILIAS\UI\Implementation\Component\ComponentHelper;
use ILIAS\UI\Implementation\Component\Input\Item as I;

/**
 * One item in the filter, might be composed from different input elements,
 * which all act as one filter input.
 */
class Container  implements \ILIAS\UI\Component\Input\Container\Container{
	use ComponentHelper;

	/**
	 * @var \ILIAS\UI\Component\Input\Item\Item[]
	 */
	protected $items = [];

	/**
	 * @inheritdoc
	 */
	public function __construct($items) {
		$items = $this->toArray($items);
		$types = [I\Item::class];
		$this->checkArgListElements("items", $items, $types);

		/**
		 * TODO: Is this a good construct, do items need keys. Do they need to know about their key? If yes, who
		 * should set the key, consumer or the form?
		 */
		foreach($items as $item){
			if (array_key_exists($item->title(),$this->items)) {
				throw new \InvalidArgumentException("Argument Items '".$item->title()."': Duplicate title used for inputs in form");
			}
			$this->items[$item->title()] = $item;
		}
	}

	public function getItems(){
		return $this->items;
	}

	public function withPostInput(){
		$this->withInput($_POST);
	}
	/**
	 * @inheritdoc
	 */
	public function withInput(array $input = null){
		foreach($input as $key => $value){
			if(array_key_exists($key, $this->items)){
				
			}
		}
	}

	/**
	 * @inheritdoc
	 */
	public function hasValidContent(){

	}

	/**
	 * @inheritdoc
	 */
	public function withContent($content){
		$this->toArray($content);
		foreach($content as $key => $item_content){
			$this->items[$key]->withContent($item_content);
		}
	}
	/**
	 * @inheritdoc
	 */
	public function content(){
		$content = [];

		foreach($this->items as $item){
			$content[] = $item->getContent();
		}
		return $content;
	}


	/**
	 * @inheritdoc
	 */
	public function validationErrors(){

	}

}
