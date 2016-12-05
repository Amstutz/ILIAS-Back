<?php


namespace ILIAS\UI\Implementation\Component\Input;

/**
 * Todo
 */
class Validation {
    /**
     * @var callable
     */
    protected $validation = null;
    /**
     * @var string
     */
    protected $message_text = "";

    /**
     * @inheritdoc
     */
    public function __construct($validation, $message_text) {
        $this->validation = $validation;
        $this->message_text = $message_text;
    }

    public function getMessageText(){
        return $this->message_text;
    }
    public function getValidation(){
        return $this->item;
    }

    public function validate($res,ValidationMessageCollector $collector,$item){
        if(!call_user_func($this->validation,$res)){
            $collector->withMessage(new ValidationMessage($this->getMessageText(),$item));
            return false;
        }
        return true;
    }

}