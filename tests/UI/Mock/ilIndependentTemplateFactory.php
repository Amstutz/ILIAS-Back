<?php

use ILIAS\UI\Implementation\Render\TemplateFactory;

class ilIndependentTemplateFactory implements TemplateFactory {
    public function getTemplate($path, $purge_unfilled_vars, $purge_unused_blocks) {
        return new ilIndependentTemplate($path, $purge_unfilled_vars, $purge_unused_blocks);
    }
}