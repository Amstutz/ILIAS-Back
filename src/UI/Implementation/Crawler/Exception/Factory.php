<?php

/* Copyright (c) 2016 Richard Klees <richard.klees@concepts-and-training.de> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Crawler\Exception;


class Factory {

    /**
     * @return CrawlerAssertion
     */
    public function assertion() {
        return new CrawlerAssertion();
    }

    /**
     * @param int $type
     * @param string $info
     * @return CrawlerException
     */
    public function exception($type = -1,$info = "") {
        return new CrawlerException($type,$info);
    }
}