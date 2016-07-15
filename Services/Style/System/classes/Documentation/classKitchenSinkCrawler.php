<?php
/***
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$
 *
 */
include_once("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/libs/yaml-master/Yaml.php");

include_once("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/libs/yaml-master/Parser.php");
include_once("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/libs/yaml-master/Inline.php");
include_once("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/libs/yaml-master/Exception/ExceptionInterface.php");

include_once("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/libs/yaml-master/Exception/RuntimeException.php");

include_once("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/libs/yaml-master/Exception/ParseException.php");

use Symfony\Component\Yaml;

class KitchenSinkCrawler
{
    protected $items = array();
    /**
     * @throws ilKitchenSinkException
     */
    public function crawl(){
        $last_variable_comment = null;
        $last_category_id = null;
        $last_category_name = null;

        try{
            $handle = fopen("./src/UI/Factory.php", "r");
        }catch(Exception $e){
            throw new ilKitchenSinkException(ilKitchenSinkException::FILE_OPENING_FAILED, "./src/UI/Factory.php");
        }


        if ($handle) {
            $line_number = 1;
            while (($line = fgets($handle)) !== false) {
                if(preg_match('/---/', $line, $out)) {
                    $content = $this->handleEntry($handle);
                    $parser = new Yaml\Parser();
                    $this->items[] = $parser->parse($content);
                }

                $line_number++;
            }
            fclose($handle);
        } else {
            throw new ilKitchenSinkException(ilKitchenSinkException::FILE_OPENING_FAILED);
        }
    }

    protected function handleEntry($handle){
        $item = new stdClass();
        $item->description = array();
        $sections = [
            "description" => array(
                "purpose",
                "composition",
                "effect",
                "rival" => "list"
            ),
            "background",
            "featurewiki",
            "rules" => array(
                "composition" => "list",
                "style" => "list"
            )
        ];

        $content = "---\n";
        while(($line = fgets($handle)) !== false) {
            /**
            foreach($sections as $key => $section){
                if(is_array($section)){
                    if($key == $pointer){
                        foreach($section as $subKey => $subSection){
                            if($subSection == "list"){

                            }else{

                            }
                            if(preg_match('/\*(\s)*'.$subSection.':/', $line, $out)) {
                                $subPointer = $subSection;
                            }
                        }
                    }else{
                        if(preg_match('/\*(\s)*'.$key.':/', $line, $out)) {
                            $pointer = $key;
                            $subPointer = false;
                        }
                    }
                }else{
                    if(preg_match('/\*(\s)*'.$section.':/', $line, $out)) {
                        $pointer = $section;
                        $subPointer = false;
                    }
                }
            }

            if(preg_match('/[[:graph:]]+/', $line, $out) && $pointer) {
                if($subPointer){
                    if (!is_array($item->description[$pointer]) ) {
                        $item->description[$pointer] = array();
                        $item->description[$pointer][$subPointer] = "";
                    }
                    $item->description[$pointer][$subPointer] .= trim(str_replace($subPointer.":","",str_replace("*", "", $line)));
                }else{
                    if (!$item->description[$pointer]) {
                        $item->description[$pointer] = "";
                    }
                    $item->description[$pointer] .= trim(str_replace($pointer.":","",str_replace("*", "", $line)));
                }
            }

            **/

            if(preg_match('/---/', $line, $out)){//preg_match('/\*(\s)\@return/', $line, $out)) {
                //$item->name = $line;
                return $content;

            }
            if(!preg_match('/\*$/', $line, $out)){//preg_match('/\*(\s)\@return/', $line, $out)) {
                $content .= str_replace("* ", "", ltrim($line));

            }

        }
        return $this->items;
       // throw new ilKitchenSinkException("","Unexpected end of file in Factory.php");**/

    }

    public function dataToString(){
        $itemsAsString = "";
        foreach($this->items as $key => $item){
            $item["id"] = $item['title'];
            if(!$item["statusEntry"]){
                $item["statusEntry"] = "Not set";
            }
            if(!$item["statusImplementation"]){
                $item["statusImplementation"] = "Not set";
            }
            $item["relations"] = array();
            $item["relations"]["isA"] =  $item["isA"];
            $itemsAsString .= json_encode($item).",";

        }
        return $itemsAsString;

    }



}
?>
