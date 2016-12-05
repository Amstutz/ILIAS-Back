<?php


namespace ILIAS\UI\Implementation\Component\Input\Validation;

/**
 * Todo
 */
class Validation implements \ILIAS\UI\Component\Input\Validation\Validation{
    /**
     * @var callable
     */
    protected $method = null;

    /**
     * @var string
     */
    protected $message_text = "";

    /**
     * @var bool
     */
    protected $invert = false;

    /**
     * @inheritdoc
     */
    public function __construct(callable $method, $message_text) {
        $this->validation = $method;
        $this->message_text = $message_text;
    }

    public function getMessageText(){
        return $this->message_text;
    }
    public function getValidationMethod(){
        return $this->method;
    }

    public function invert(){
        $clone = clone $this;
        $this->invert = true;
        return $clone;
    }

    /**
     * @inheritdoc
     */
    public function validate($content_to_validate,
                             \ILIAS\UI\Component\Input\Validation\ValidationMessageCollector $collector,
                             \ILIAS\UI\Component\Input\Item\Item $item){
        $result = call_user_func($this->validation,$content_to_validate);

        if($this->invert()) {
            $result = !$result;

        }

        if($result){
            return true;
        }
        $collector->withMessage(new ValidationMessage($this->getMessageText(),$item));
        return false;
    }

}