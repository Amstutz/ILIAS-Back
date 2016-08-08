<?php

class ilLanguageMock extends \ilLanguage {
    public $requested = array();
    public function __construct() {}
    public function txt($a_topic, $a_default_lang_fallback_mod = "") {
        $this->requested[] = $a_topic;
        return $a_topic;
    }
}