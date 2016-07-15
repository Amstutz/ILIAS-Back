<?php
require_once("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/classes/Models/Less/class.KitchenSinkLessItem.php");


/***
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$
 *
 */
class KitchenSinkLessFile
{
    /**
     * @var KitchenSinkLessVariable[]
     */
    protected $items = array();

    /**
     * @var array
     */
    protected $comments_ids = array();

    /**
     * @var array
     */
    protected $variables_ids= array();

    /**
     * @var array
     */
    protected $categories_ids = array();

    /**
     * @var string
     */
    protected $less_variables_file_path = "";

    /**
     * KitchenSinkLessFile constructor.
     * @param string $less_variables_file
     */
    public function __construct($less_variables_file)
    {
        $this->less_variables_file = $less_variables_file;
    }

    /**
     * @throws ilKitchenSinkException
     */
    public function read(){
        $last_variable_comment = null;
        $last_category_id = null;
        $last_category_name = null;

        try{
            $handle = fopen($this->getLessVariablesFile(), "r");
        }catch(Exception $e){
            throw new ilKitchenSinkException(ilKitchenSinkException::FILE_OPENING_FAILED, $this->getLessVariablesFile());
        }


        if ($handle) {
            $line_number = 1;
            while (($line = fgets($handle)) !== false) {

                if(preg_match('/\/\/==\s(.*)/', $line, $out)){
                    //Check Category
                    $last_category_id = $this->addItem(new KitchenSinkLessCategory($out[1]));
                    $last_category_name = $out[1];
                } else if(preg_match('/\/\/##\s(.*)/', $line, $out)){
                    //Check Comment Category
                    $last_category = $this->getItemById($last_category_id);
                    $last_category->setComment($out[1]);
                } else if(preg_match('/\/\/\*\*\s(.*)/', $line, $out)){
                    //Check Variables Comment
                    $last_variable_comment = $out[1];
                } else if(preg_match('/^@(.*)/', $line, $out)){
                    //Check Variables
                    preg_match('/(?:@)(.*)(?:\:)/', $out[0], $variable);
                    preg_match('/(?::)(.*)(?:;)/', $line, $value);
                    $temp_value = $value[0];
                    $references = array();

                    while(preg_match('/(?:@)([a-zA-Z0-9_-]*)/',$temp_value,$reference)){
                        $references[] = $reference[1];
                        $temp_value = str_replace($reference,"",$temp_value);
                    }

                    $this->addItem(new KitchenSinkLessVariable($variable[1],ltrim ( $value[1] ," \t\n\r\0\x0B" ),$last_variable_comment,$last_category_name, $references));
                    $last_variable_comment = 0;
                }else{
                    $this->addItem(new KitchenSinkLessComment($line));
                }


                $line_number++;
            }
            fclose($handle);
        } else {
            throw new ilKitchenSinkException(ilKitchenSinkException::FILE_OPENING_FAILED);
        }
    }

    public function write(){
        $output = "";

        foreach($this->items as $item){
            $output .= $item->__toString();
        }
        return $output;
    }

    /**
     * @param KitchenSinkLessItem $item
     * @return int
     */
    public function addItem(KitchenSinkLessItem $item){
        $id = array_push($this->items,$item)-1;


        if(get_class($item)=="KitchenSinkLessComment"){
            $this->comments_ids[] = $id;
        }else if(get_class($item)=="KitchenSinkLessCategory"){
            $this->categories_ids[] = $id;
        }else if(get_class($item)=="KitchenSinkLessVariable"){
            $this->variables_ids[] = $id;
        }

        return $id;
    }

    /**
     * @return KitchenSinkLessCategory[]
     */
    public function getCategories(){
        $categories = array();

        foreach($this->categories_ids as $category_id){
            $categories[] = $this->items[$category_id];
        }

        return $categories;

    }

    /**
     * @param string $category
     * @return KitchenSinkLessVariable|null
     */
    public function getVariablesPerCategory($category = ""){
        $variables = array();

        foreach($this->variables_ids as $variables_id){
            if(!$category || $this->items[$variables_id]->getCategoryName() == $category){
                $variables[] = $this->items[$variables_id];
            }
        }

        return $variables;
    }

    public function getItemById($id){
        return $this->items[$id];
    }

    /**
     * @param string $name
     * @return KitchenSinkLessVariable|null
     */
    public function getVariableByName($name = ""){
        foreach($this->variables_ids as $variables_id){
            if($this->items[$variables_id]->getName() == $name){
                return $this->items[$variables_id];
            }
        }
        return null;

    }

    /**
     * @return string
     */
    public function getLessVariablesFile()
    {
        return $this->less_variables_file;
    }

    /**
     * @param string $less_variables_file
     */
    public function setLessVariablesFile($less_variables_file)
    {
        $this->less_variables_file = $less_variables_file;
    }


}
?>
