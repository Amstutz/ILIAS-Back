<?php

/* Copyright (c) 2016 Timon Amstutz <timon.amstutz@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

namespace ILIAS\UI\Implementation\Component\Listing\SimpleList;

class Renderer2 implements \ILIAS\UI\Renderer  {
    /**
     * @inheritdocs
     */
    public function render(\ILIAS\UI\Component\Component $component) {
        global $DIC;
        $f = $DIC->ui()->factory();

        $html = "";
        if((new \ReflectionClass($component))->getShortName() == "SimpleList")
        {
            if(count($component->getItems())>0){
                $html = "<ul>";

                foreach($component->getItems() as $item){
                    if(is_string($item)){
                        $item = $f->text()->standard($item);
                    }

                    $html .= "<li>".$this->render($item)."</li>";

                }
                $html .= "</ul>";
            }
        }
        else if((new \ReflectionClass($component))->getShortName() == "Text"){
            $html = $component->getText();
        }
        else{
            throw new \Exception("Unknown Component, Idiot: ".(new \ReflectionClass($component))->getShortName());
        }


        return $html;
    }
}
