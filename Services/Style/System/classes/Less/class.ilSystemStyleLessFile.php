<?php
require_once("./Services/Style/System/classes/Less/class.ilSystemStyleLessItem.php");
require_once("./Services/Style/System/classes/Less/class.ilSystemStyleLessCategory.php");
require_once("./Services/Style/System/classes/Less/class.ilSystemStyleLessComment.php");
require_once("./Services/Style/System/classes/Less/class.ilSystemStyleLessVariable.php");


/***
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$
 *
 */
class ilSystemStyleLessFile
{
    /**
     * @var ilSystemStyleLessVariable[]
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
        $this->read();
    }

    /**
     * @throws ilSystemStyleException
     */
    public function read(){
        $last_variable_comment = null;
        $last_category_id = null;
        $last_category_name = null;

        try{
            $handle = fopen($this->getLessVariablesFile(), "r");
        }catch(Exception $e){
            throw new ilSystemStyleException(ilSystemStyleException::FILE_OPENING_FAILED, $this->getLessVariablesFile());
        }


        if ($handle) {
            $line_number = 1;
            while (($line = fgets($handle)) !== false) {

                if(preg_match('/\/\/==\s(.*)/', $line, $out)){
                    //Check Category
                    $last_category_id = $this->addItem(new ilSystemStyleLessCategory($out[1]));
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

                    $this->addItem(new ilSystemStyleLessVariable(
                        $variable[1],
                        ltrim ( $value[1] ," \t\n\r\0\x0B" ),
                        $last_variable_comment,
                        $last_category_name,
                        $references));
                    $last_variable_comment = 0;
                }else{
                    $this->addItem(new ilSystemStyleLessComment($line));
                }


                $line_number++;
            }
            fclose($handle);
        } else {
            throw new ilSystemStyleLessException(ilSystemStyleException::FILE_OPENING_FAILED);
        }
    }

    public function write(){
        file_put_contents($this->getLessVariablesFile(),$this->getContent());
    }

    public function getContent(){
        $output = "";

        foreach($this->items as $item){
            $output .= $item->__toString();
        }
        return $output;
    }

    /**
     * @param ilSystemStyleLessItem $item
     * @return int
     */
    public function addItem(ilSystemStyleLessItem $item){
        $id = array_push($this->items,$item)-1;


        if(get_class($item)=="ilSystemStyleLessComment"){
            $this->comments_ids[] = $id;
        }else if(get_class($item)=="ilSystemStyleLessCategory"){
            $this->categories_ids[] = $id;
        }else if(get_class($item)=="ilSystemStyleLessVariable"){
            $this->variables_ids[] = $id;
        }

        return $id;
    }

    /**
     * @return ilSystemStyleLessCategory[]
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
     * @return ilSystemStyleLessVariable[]|null
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
     * @return ilSystemStyleLessVariable|null
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
