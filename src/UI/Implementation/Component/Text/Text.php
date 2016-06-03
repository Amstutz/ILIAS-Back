<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Text;

use ILIAS\UI\Component as C;

class Text implements C\Text\Text {

    /**
     * @var	string
     */
    private $type;

    /**
     * @var	string
     */
    private  $text;


    public function __construct($type, $text) {
        assert('self::is_valid_type($type)');

        $this->type = $type;
        $this->text = $text;
    }
    public function withType($type){
        assert('self::is_valid_type($type)');
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


    public function withText($text){
        assert('self::is_valid_type($type)');
        $clone = clone $this;
        $clone->text = $text;
        return $clone;
    }

    /**
     * @inheritdoc
     */
    public function getText() {
        return $this->text;
    }


    // Helper
    static protected function is_valid_type($type) {
        static $types = array
        (   self::STANDARD,
            self::HEADING,
            self::CODE
        );
        return in_array($type, $types);
    }

}
?>