<?php
require_once(ILIAS_ABSOLUTE_PATH."/Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/classes/Exceptions/class.ilKitchenSinkException.php");
require_once("class.KitchenSinkEntryDescription.php");

/**
 * Class to display Kitchen Sink Entries
 *
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$
 *
 */
class KitchenSinkEntry
{
    /**
     * @var string
     */
    protected $id = "";

    /**
     * @var string
     */
    protected $title = "";

    /**
     * @var string
     */
    protected $type = "";

    /**
     * @var string
     */
    protected $php_class = "";

    /**
     * @var array
     */
    protected $external_class = array();

    /**
     * @var string
     */
    protected $status_entry = "";

    /**
     * @var array
     */
    protected $status_implementation = array();

    /**
     * @var KitchenSinkEntryDescription
     */
    protected $description = null;

    /**
     * @var string
     */
    protected $background = "";

    /**
     * @var string
     */
    protected $context = "";

    /**
     * @var string
     */
    protected $selector = "";


    /**
     * @var array
     */
    protected $feature_wiki_references = array();

    /**
     * @var array
     */
    protected $rules = array();

    /**
     * @var array
     */
    protected $relations = array();

    /**
     * @var string[]
     */
    protected $children = array();

    /**
     * @var array
     */
    protected $must_be_used_by = array();

    /**
     * @var array
     */
    protected $may_be_used_by = array();

    /**
     * @var array
     */
    protected $less_variables = array();

    /**
     * @var bool
     */
    protected $is_dummy = false;

    /**
     * @var string
     */
    protected $html = "";

    /**
     * @var string
     */
    protected $php = "";

    /**
     * @var string
     */
    protected $php_path = "";

    /**
     * @var array
     */
    protected $log = array();

    /**
     * KitchenSinkEntry constructor.
     * @param bool|false $json
     * @param string $dummyId
     */
    public function __construct($json = false, $dummyId = "") {
        if (!$json && $dummyId == ""){
            throw new ilKitchenSinkException(ilKitchenSinkException::EMPTY_ENTRY);
        }

        if($json){
            $this->setId($json->id);
            $this->setTitle($json->title);
            $this->setType($json->type);
            $this->setPhpClass($json->phpClass);
            $this->setExternalClass($json->externalLibrary);
            $this->setStatusEntry($json->statusEntry);
            $this->setStatusImplementation($json->statusImplementation);
            $this->setDescription(new KitchenSinkEntryDescription($json->description));
            $this->setSelector($json->selector);
            $this->setRules($json->rules);
            $this->setBackground($json->background);
            $this->setContext($json->context);
            $this->setFeatureWikiReferences($json->featureWikiReferences);
            $this->setRules($json->rules);
            $this->setRelations($json->relations);
            $this->setLessVariables($json->lessVariables);
            $this->setLog($json->log);
            if(true){
                $this->setHtmlByPath($json->html);
            }
            if($json->php != ""){
                $this->setPhpByPath($json->php);
            }
        }else{
            $this->setId($dummyId);
            $this->setTitle($dummyId);
            $this->setStatusEntry("dummy");
            $this->setStatusImplementation("dummy");
            $this->setDescription(new KitchenSinkEntryDescription());
            $this->setIsDummy(true);
            $dummy_relations = new stdClass();
            $dummy_relations->isA = "undefined";
            $this->setRelations($dummy_relations);
        }
    }


    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        if($id){
            $this->id = $id;
        }else{
            throw new ilKitchenSinkException(ilKitchenSinkException::INVALID_MANDATORY_ENTRY_ATTRIBUTE, "ID");
        }
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        if($title){
            $this->title = $title;
        }else{
            throw new ilKitchenSinkException(ilKitchenSinkException::INVALID_MANDATORY_ENTRY_ATTRIBUTE, "Title of entry: ".$this->getId());
        }
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getPhpClass()
    {
        return $this->php_class;
    }

    /**
     * @param string $php_class
     */
    public function setPhpClass($php_class)
    {
        $this->php_class = $php_class;
    }

    /**
     * @return array
     */
    public function getExternalClass()
    {
        return $this->external_class;
    }

    /**
     * @param array $external_class
     */
    public function setExternalClass($external_class)
    {
        $this->external_class = $external_class;
    }

    /**
     * @return string
     */
    public function getStatusEntry()
    {
        return $this->status_entry;
    }

    /**
     * @param string $status_entry
     */
    public function setStatusEntry($status_entry)
    {
        if($status_entry){
            $this->status_entry = $status_entry;
        }else{
            throw new ilKitchenSinkException(ilKitchenSinkException::INVALID_MANDATORY_ENTRY_ATTRIBUTE, "Entry status of entry: ".$this->getId());
        }
    }

    /**
     * @return array
     */
    public function getStatusImplementation()
    {
        return $this->status_implementation;
    }

    /**
     * @param array $status_implementation
     */
    public function setStatusImplementation($status_implementation)
    {
        if($status_implementation){
            $this->status_implementation = $status_implementation;
        }else{
            throw new ilKitchenSinkException(ilKitchenSinkException::INVALID_MANDATORY_ENTRY_ATTRIBUTE, "Implementation status of entry: ".$this->getId());
        }
    }

    /**
     * @return KitchenSinkEntryDescription
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     *
     */
    public function setDescription(KitchenSinkEntryDescription $description)
    {
        if($description){
            $this->description = $description;
        }else{
            throw new ilKitchenSinkException(ilKitchenSinkException::INVALID_MANDATORY_ENTRY_ATTRIBUTE, "Description of entry: ".$this->getId());
        }
    }


    /**
     * @return string
     */
    public function getBackground()
    {
        return $this->background;
    }

    /**
     * @param string $background
     */
    public function setBackground($background)
    {
        $this->background = $background;
    }

    /**
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param string $context
     */
    public function setContext($context)
    {
        $this->context = $context;
    }

    /**
     * @return array
     */
    public function getFeatureWikiReferences()
    {
        return $this->feature_wiki_references;
    }

    /**
     * @param array $feature_wiki_references
     */
    public function setFeatureWikiReferences($feature_wiki_references)
    {
        $this->feature_wiki_references = $feature_wiki_references;
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @param array $rules
     */
    public function setRules($rules)
    {
        $this->rules = $rules;
    }

    /**
     * @return string
     */
    public function getSelector()
    {
        return $this->selector;
    }

    /**
     * @param string $selector
     */
    public function setSelector($selector)
    {
        $this->selector = $selector;
    }


    /**
     * @return array
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * @param array $relations
     */
    public function setRelations($relations)
    {
        if(!$relations){
            throw new ilKitchenSinkException(ilKitchenSinkException::INVALID_MANDATORY_ENTRY_ATTRIBUTE, "Relations of entry: ".$this->getId());
        }else if(!$relations->isA){
            throw new ilKitchenSinkException(ilKitchenSinkException::INVALID_MANDATORY_ENTRY_ATTRIBUTE, "isA relations of entry: ".$this->getId());

        }
        $this->relations = $relations;
    }

    /**
     * @return string[]
     */
    public function getChildrenIds()
    {
        return $this->children;
    }

    /**
     * @param $children
     */
    public function setChildrenIds($children)
    {
        $this->children = $children;
    }

    /**
     * @param string $child_id
     */
    public function addChildId($child_id = ""){
        $this->children[] = $child_id;
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }


    /**
     * @param string $html
     */
    public function setHtmlByPath($path)
    {
        global $DIC;
        $tpl = $DIC["tpl"];
        $tpl->getStandardTemplate();
        require_once("./libs/composer/vendor/autoload.php");
        $path = ILIAS_ABSOLUTE_PATH."/src/UI/examples/Glyph/attachment_with_counters.php";
        include_once($path);
        $html = Glyph_attachment_with_counters();
        if(!$html){
            throw new ilKitchenSinkException(ilKitchenSinkException::INVALID_FILE_PATH, $path);
        }
        $this->html = $html;
    }

    /**
     * @return string
     */
    public function getPhp()
    {
        return $this->php;
    }

    /**
     * @param string $php
     */
    public function setPhpByPath($path)
    {
        $path = ILIAS_ABSOLUTE_PATH."/Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/data/uiComponents/".$path;
        $php = file_get_contents($path);
        $this->setPhpPath($path);
        if(!$php){
            throw new ilKitchenSinkException(ilKitchenSinkException::INVALID_FILE_PATH, $path);
        }
        $this->php = $php;
    }

    /**
     * @return string
     */
    public function getPhpPath()
    {
        return $this->php_path;
    }

    /**
     * @param string $php_path
     */
    public function setPhpPath($php_path)
    {
        $this->php_path = $php_path;
    }

    /**
     * @return mixed
     */
    public function getPhpClassInstance(){
        require_once($this->getPhpPath());
        $file_name = basename($this->getPhpPath());
        $matches = array();
        preg_match('/\.(.*?)\./s', $file_name, $matches);
        $class_name = $matches[1];
        return new $class_name();
    }

    /**
     * @return array
     */
    public function getMustBeUsedBy()
    {
        return $this->must_be_used_by;
    }

    /**
     * @param array $must_be_used_by
     */
    public function setMustBeUsedBy($must_be_used_by)
    {
        $this->must_be_used_by = $must_be_used_by;
    }

    /**
     * @param string $id
     */
    public function addMustBeUsedBy($id=""){
        $this->must_be_used_by[] = $id;
    }
    /**
     * @return array
     */
    public function getMayBeUsedBy()
    {
        return $this->may_be_used_by;
    }

    /**
     * @param array $may_be_used_by
     */
    public function setMayBeUsedBy($may_be_used_by)
    {
        $this->may_be_used_by = $may_be_used_by;
    }

    /**
     * @param string $id
     */
    public function addMayBeUsedBy($id = ""){
        $this->may_be_used_by[] = $id;
    }

    /**
     * @return array
     */
    public function getLessVariables()
    {
        return $this->less_variables;
    }

    /**
     * @param array $less_variables
     */
    public function setLessVariables($less_variables)
    {
        $this->less_variables = $less_variables;
    }


    /**
     * @return boolean
     */
    public function isDummy()
    {
        return $this->is_dummy;
    }

    /**
     * @param boolean $is_dummy
     */
    public function setIsDummy($is_dummy)
    {
        $this->is_dummy = $is_dummy;
    }

    /**
     * @return array
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * @param array $log
     */
    public function setLog($log)
    {
        $this->log = $log;
    }

    public function getStatusType(){
        if(!$this->getStatusImplementation()){
            return "danger";
        }

        switch(strtolower($this->getStatusImplementation())){
            case "static":
                return "default";
            case "proposal":
            case "to be revised":
            case "to be implemented":
                return "warning";
            case "proposed":
                return "info";
            case "accepted":
                return "success";
            default:
                return "danger";
        }
    }

}
?>