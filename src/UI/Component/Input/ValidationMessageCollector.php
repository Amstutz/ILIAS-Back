<?php


namespace ILIAS\UI\Component\Filter;

/**
 * Collects messages during validation of filters.
 */
interface ValidationMessageCollector {

	public function withMessage(ValidationMessage $message);
    public function getMessages();
    public function join(ValidationMessageCollector $collector);
}