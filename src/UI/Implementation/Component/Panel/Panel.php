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

    /**
     * @var \ILIAS\UI\Factory
     */
    protected $f;

    public function __construct($type, $heading,$body) {
        global $DIC;
        $this->f = $DIC->ui()->factory();

        $this->type = $type;
        $this->heading = $heading;//$this->getFactory()->text()->heading($heading);
        $this->body = $body;

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
        assert('self::is_valid_type($type)');
        $clone = clone $this;
        $clone->body = $body;
        return $clone;
    }

    /**
     * @inheritdoc
     */
    public function getBody() {
        return $this->body;
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


}
?>