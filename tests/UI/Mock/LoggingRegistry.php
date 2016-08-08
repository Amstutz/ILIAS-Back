<?php

use ILIAS\UI\Implementation\Render\ResourceRegistry;

/**
 * Class LoggingRegistry
 */
class LoggingRegistry implements ResourceRegistry {
    public $resources = array();

    /**
     * @param string $name
     * @return $this
     */
    public function register($name) {
        $this->resources[] = $name;
        return $this;
    }
}