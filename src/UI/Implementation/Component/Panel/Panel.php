<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Panel;

use ILIAS\UI\Component as C;

class Panel implements C\Panel\Panel {

    /**
     * @var	string
     */
    private $type;


    private  $heading;

    private  $body;

    private $card = null;

    /**
     * @var \ILIAS\UI\Factory
     */
    protected $f;

    public function __construct($type, $heading,$body) {
        global $DIC;
        $this->f = $DIC->ui()->factory();

        $this->type = $type;
        $this->heading = $heading;//$this->getFactory()->text()->heading($heading);
        $this->body = $this->formatBody($body);


    }
    public function withType($type){
        $clone = clone $this;
        $clone->type = $type;
        return $clone;
    }

    /**
     * @inheritdoc
     */
    public function getType() {
        return $this->type;
    }


    public function withHeading($heading){
        assert('self::is_valid_type($type)');
        $clone = clone $this;
        $this->heading = $heading;//$this->getFactory()->text()->heading($heading);
        return $clone;
    }

    /**
     * @inheritdoc
     */
    public function getHeading() {
        return $this->heading;
    }

    public function withBody($body){
        $clone = clone $this;
        $clone->body = $this->formatBody($body);
        return $clone;
    }

    /**
     * @inheritdoc
     */
    public function getBody() {
        return $this->body;
    }

    /**
     * Todo: Discuss this pattern with Richard, Bullets do not have cards. How to procceed? Share a lot of functionality, but not all
     * @param $body
     * @return Panel
     */
    public function withCard($card){
        $clone = clone $this;
        $clone->card = $card;
        return $clone;
    }

    /**
     * @inheritdoc
     */
    public function getCard() {
        return $this->card;
    }


    // Helper
    static protected function is_valid_type($type) {
        static $types = array
        (   self::BLOCK,
            self::HEADING
        );
        return in_array($type, $types);
    }

    /**
     * @return mixed
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * @return mixed
     */
    public function getHeadingClass()
    {
        return $this->getType() == self::HEADING ? "ilHeader":"ilBlockHeader";
    }
    /**
     * @param mixed $factory
     */
    public function setFactory($factory)
    {
        $this->factory = $factory;
    }

    /**
     * Todo: Discuss this pattern with Richard
     * @param $body
     * @return C\Component[]
     */
    protected function formatBody($body){
        global $DIC;
        $f = $DIC->ui()->factory();

        if(is_string($body)){
            $body = $f->text()->standard($body);
        }
        if(!is_array($body)){
            $body = array($body);
        }
        return $body;

    }

}
?>