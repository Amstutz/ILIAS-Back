<?php

class DefaultRendererTesting extends \ILIAS\UI\Implementation\DefaultRenderer {
    public function getResourceRegistry() {
        return $this->resource_registry;
    }
}

