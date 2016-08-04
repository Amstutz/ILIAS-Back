<?php
include_once("Services/Style/System/classes/Utilities/class.ilSkinStyleXML.php");
include_once("Services/Style/System/classes/class.ilStyleDefinition.php");
include_once("Services/Style/System/classes/Exceptions/class.ilSystemStyleException.php");
include_once("Services/Style/System/classes/Utilities/class.ilSystemStyleMessageStack.php");
include_once("Services/Style/System/classes/Utilities/class.ilSystemStyleMessage.php");

/**
 *
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$*
 */
class ilSystemStyleSkinContainer {


    /**
     * @var ilLanguage
     */
    protected $lng;

    /**
     * @var ilSkinXML
     */
    protected $skin;

    /**
     * @var
     */
    protected $customizing_skin_directory;

    /**
     * @var ilSystemStyleMessageStack
     */
    protected static $message_stack = null;

    /**
     * ilSystemStyleSkinContainer constructor.
     * @param ilSkinXML $skin
     * @param ilSystemStyleMessageStack|null $message_stack
     */
    public function __construct(ilSkinXML $skin, ilSystemStyleMessageStack $message_stack = null)
    {
        global $DIC;

        $this->lng = $DIC->language();

        $this->skin = $skin;
        $this->customizing_skin_directory = ilStyleDefinition::CUSTOMIZING_SKINS_PATH.$this->getSkin()->getId()."/";


        if(!$message_stack){
            $this->setMessageStack(new ilSystemStyleMessageStack());
        }else{
            $this->setMessageStack($message_stack);
        }
    }

    /**
     * @param $skin_id
     * @param MessageStack|null $message_stack
     * @return ilSystemStyleSkinContainer
     * @throws ilSystemStyleException
     */
    static function generateFromId($skin_id,ilSystemStyleMessageStack $message_stack = null){
        if(!$skin_id){
            throw new ilSystemStyleException(ilSystemStyleException::NO_SKIN_ID);
        }

        if ($skin_id != "default")
        {
            return new self(ilSkinXML::parseFromXML(ilStyleDefinition::CUSTOMIZING_SKINS_PATH.$skin_id."/template.xml"), $message_stack);
        }else{
            return new self(ilSkinXML::parseFromXML(ilStyleDefinition::DEFAULT_TEMPLATE_PATH), $message_stack);
        }

    }

    public function create(){
        if(file_exists($this->getSkinDirectory())){
            throw new ilSystemStyleException(ilSystemStyleException::SKIN_ALREADY_EXISTS,$this->getSkinDirectory());
        }
        mkdir($this->getSkinDirectory());
        $this->writeSkinToXML();

        foreach($this->getSkin()->getStyles() as $style){
            $this->createResourceDirectory(ilStyleDefinition::DEFAULT_IMAGES_PATH,$style->getImageDirectory());
            $this->createResourceDirectory(ilStyleDefinition::DEFAULT_SOUNDS_PATH,$style->getSoundDirectory());
            $this->createResourceDirectory(ilStyleDefinition::DEFAULT_FONTS_PATH,$style->getFontDirectory());
            $this->createLessStructure($style);
        }

    }

    public function updateSkin(ilSkinXML $old_skin){
        $old_customizing_skin_directory = ilStyleDefinition::CUSTOMIZING_SKINS_PATH.$old_skin->getId()."/";

        //Move if skin id has been changed
        if($old_skin->getId()!= $this->getSkin()->getId()){
            $this->move($old_customizing_skin_directory,$this->getSkinDirectory());
        }

        //Delete old template.xml and write a new one
        unlink($old_customizing_skin_directory."template.xml");
        $this->writeSkinToXML();
    }

    public function updateStyle($style_id, ilSkinStyleXML $old_style){
        $style = $this->getSkin()->getStyle($style_id);

        if($style->getImageDirectory()!=$old_style->getImageDirectory())
        {
            if(file_exists($this->getSkinDirectory().$old_style->getImageDirectory())){
                $this->changeResourceDirectory($style->getImageDirectory(),$old_style->getImageDirectory());
            }else{
                $this->createResourceDirectory(ilStyleDefinition::DEFAULT_IMAGES_PATH,$style->getImageDirectory());
            }
        }

        if($style->getFontDirectory()!=$old_style->getFontDirectory())
        {
            if(file_exists($this->getSkinDirectory().$old_style->getFontDirectory())){
                $this->changeResourceDirectory($style->getFontDirectory(),$old_style->getFontDirectory());
            }else{
                $this->createResourceDirectory(ilStyleDefinition::DEFAULT_FONTS_PATH,$style->getFontDirectory());
            }
        }

        if($style->getSoundDirectory()!=$old_style->getSoundDirectory())
        {
            if(file_exists($this->getSkinDirectory().$old_style->getSoundDirectory())){
                $this->changeResourceDirectory($style->getSoundDirectory(),$old_style->getSoundDirectory());
            }else{
                $this->createResourceDirectory(ilStyleDefinition::DEFAULT_SOUNDS_PATH,$style->getSoundDirectory());
            }
        }

        if(file_exists($this->getSkinDirectory().$old_style->getCssFile().".less")){
            rename($this->getSkinDirectory().$old_style->getCssFile().".less", $this->getSkinDirectory().$style->getCssFile().".less");
        }else{
            $this->createMainLessFile($style);
        }

        if(file_exists($this->getSkinDirectory().$old_style->getCssFile()."-variables.less")){
            rename($this->getSkinDirectory().$old_style->getCssFile()."-variables.less", $this->getLessVariablesFilePath($style->getId()));
        }else{
            $this->copyVariablesFromDefault($style);
        }

        if(file_exists($this->getSkinDirectory().$old_style->getCssFile().".css")){
            rename($this->getSkinDirectory().$old_style->getCssFile().".css", $this->getSkinDirectory().$style->getCssFile().".css");
        }else{
            try{
                $this->compileLess($style->getId());
            }catch(Exception $e){
                $this->getMessageStack()->addMessage(
                    new ilSystemStyleMessage($e->getMessage(),
                        ilSystemStyleMessage::TYPE_ERROR
                    ));
                copy (ilStyleDefinition::DELOS_PATH.".css",$this->getCSSFilePath($style->getId()));
            }
        }

        $this->writeSkinToXML();
    }

    protected function resourcesStyleReferences($resource){
        $references_ids = array();
        foreach ($this->getSkin()->getStyles() as $style) {
            if($style->referencesResource($resource)){
                $references_ids[] = $style->getId();
            }
        }
        return $references_ids;

    }

    /**
     * @param $source
     * @param $target
     * @throws ilSystemStyleException
     */
    protected function createResourceDirectory($source, $target){
        $path = $this->getSkinDirectory().$target;

        mkdir($path);
        self::xCopy($source,$path);
        $this->getMessageStack()->addMessage(
            new ilSystemStyleMessage($this->lng->txt("dir_created").$path,
                ilSystemStyleMessage::TYPE_SUCCESS
            ));

    }

    /**
     * @param $new_dir
     * @param $old_dir
     * @throws ilSystemStyleException
     */
    protected function changeResourceDirectory($new_dir,$old_dir){
        $absolut_new_dir = $this->getSkinDirectory().$new_dir;
        $absolut_old_dir = $this->getSkinDirectory().$old_dir;

        if(file_exists($absolut_new_dir)){
            $this->getMessageStack()->addMessage(
                new ilSystemStyleMessage($this->lng->txt("dir_changed_to").$absolut_new_dir,
                    ilSystemStyleMessage::TYPE_SUCCESS
                ));
            $this->getMessageStack()->addMessage(
                new ilSystemStyleMessage($this->lng->txt("dir_preserved_backup").$absolut_old_dir,
                    ilSystemStyleMessage::TYPE_SUCCESS
                ));
        }else{
            mkdir($absolut_new_dir);
            self::xCopy($absolut_old_dir, $absolut_new_dir);
            $this->getMessageStack()->addMessage(
                new ilSystemStyleMessage($this->lng->txt("dir_copied_to").$absolut_new_dir,
                    ilSystemStyleMessage::TYPE_SUCCESS
                ));
            if(count($this->resourcesStyleReferences($old_dir))==0){
                $this->recursiveRemoveDir($this->getSkinDirectory().$old_dir);
                $this->getMessageStack()->addMessage(
                    new ilSystemStyleMessage($this->lng->txt("dir_deleted").$absolut_old_dir,
                        ilSystemStyleMessage::TYPE_SUCCESS
                    ));
            }else{
                $this->getMessageStack()->addMessage(
                    new ilSystemStyleMessage($this->lng->txt("dir_preserved_linked").$absolut_old_dir,
                        ilSystemStyleMessage::TYPE_SUCCESS
                    ));
            }
        }
    }

    /**
     * @param $dir
     */
    protected function removeResourceDirectory($dir){
        $absolut_dir = $this->getSkinDirectory().$dir;

        if(file_exists($absolut_dir)) {
            if (count($this->resourcesStyleReferences($dir)) == 0) {
                $this->recursiveRemoveDir($this->getSkinDirectory() . $dir);
                $this->getMessageStack()->addMessage(
                    new ilSystemStyleMessage($this->lng->txt("dir_deleted") . $dir,
                        ilSystemStyleMessage::TYPE_SUCCESS
                    ));
            } else {
                $this->getMessageStack()->addMessage(
                    new ilSystemStyleMessage($this->lng->txt("dir_preserved_linked") . $dir,
                        ilSystemStyleMessage::TYPE_SUCCESS
                    ));
            }
        }
    }

    /**
     * @param ilSkinStyleXML $style
     * @throws ilSystemStyleException
     */
    protected function createLessStructure(ilSkinStyleXML $style){
        $this->createMainLessFile($style);
        $this->copyVariablesFromDefault($style);
        $this->copyCSSFromDefault($style);
        $this->compileLess($style->getId());
    }

    /**
     * @param ilSkinStyleXML $style
     */
    public function createMainLessFile(ilSkinStyleXML $style){
        $path = $this->getLessFilePath($style->getId());
        file_put_contents($path,$this->getLessMainFileContent($style));
        $this->getMessageStack()->addMessage(
            new ilSystemStyleMessage($this->lng->txt("main_less_created").$path,
                ilSystemStyleMessage::TYPE_SUCCESS
            ));
    }
    /**
     * @param ilSkinStyleXML $style
     */
    public function copyVariablesFromDefault(ilSkinStyleXML $style){
        copy (ilStyleDefinition::DEFAULT_VARIABLES_PATH,$this->getLessVariablesFilePath($style->getId()));
    }

    /**
     * @param ilSkinStyleXML $style
     */
    public function copyCSSFromDefault(ilSkinStyleXML $style){
        copy (ilStyleDefinition::DELOS_PATH.".css",$this->getCSSFilePath($style->getId()));
    }

    static function xCopy($src, $dest)
    {
        foreach (scandir($src) as $file) {
            $src_file = rtrim($src, '/') . '/' . $file;
            $dest_file = rtrim($dest, '/') . '/' . $file;
            if (!is_readable($src_file)) {
                throw new ilSystemStyleException(ilSystemStyleException::FILE_OPENING_FAILED, $src_file);
            }
            if (substr($file, 0, 1) != ".") {
                if (is_dir($src_file)) {
                    if (!file_exists($dest_file)) {
                        try {
                            mkdir($dest_file);
                        } catch (Exception $e) {
                            throw new ilSystemStyleException(ilSystemStyleException::FOLDER_CREATION_FAILED, "Copy " . $src_file . " to " . $dest_file . " Error: " . $e);
                        }
                    }
                    self::xCopy($src_file, $dest_file);
                } else {
                    try {
                        copy($src_file,$dest_file);
                    } catch (Exception $e) {
                        throw new ilSystemStyleException(ilSystemStyleException::FILE_CREATION_FAILED, "Copy " . $src_file . " to " . $dest_file . " Error: " . $e);
                    }
                }
            }
        }
    }

    public function recursiveRemoveDir($dir){
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir."/".$object))
                        $this->recursiveRemoveDir($dir."/".$object);
                    else
                        unlink($dir."/".$object);
                }
            }
            rmdir($dir);
        }
    }



    protected function getLessMainFileContent(ilSkinStyleXML $style){
        $content = "@import \"".ilStyleDefinition::DELOS_PATH."\";\n";
        $content .= "// Import Custom Less Files here\n";

        $content .= "@import \"".$this->getLessVariablesFilePath($style->getId())."\";\n";
        return $content;
    }

    public function move($from,$to){
        rename($from,$to);
    }


    public function delete(){
        $this->recursiveRemoveDir($this->getSkinDirectory());
        $this->getMessageStack()->addMessage(
            new ilSystemStyleMessage($this->lng->txt("skin_deleted").$this->getSkinDirectory(),
                ilSystemStyleMessage::TYPE_SUCCESS
            ));
    }

    protected function deleteFile($path){
        if(file_exists($path)){
            unlink($path);
            $this->getMessageStack()->addMessage(
                new ilSystemStyleMessage($this->lng->txt("file_deleted").$path,
                    ilSystemStyleMessage::TYPE_SUCCESS
                ));
        }
    }

    public function deleteStyle(ilSkinStyleXML $style){

        $this->removeResourceDirectory($style->getImageDirectory());
        $this->removeResourceDirectory($style->getFontDirectory());
        $this->removeResourceDirectory($style->getSoundDirectory());

        $this->deleteFile($this->getSkinDirectory().$style->getCssFile().".less");
        $this->deleteFile($this->getSkinDirectory().$style->getCssFile().".css");
        $this->deleteFile($this->getSkinDirectory().$style->getCssFile()."-variables.less");

        if($style->isSubstyle()){
            ilSystemStyleSettings::deleteSubStyleCategoryAssignments($this->getSkin()->getId(),$style->getSubstyleOf(),$style->getId());
            $this->getMessageStack()->prependMessage(
                new ilSystemStyleMessage($this->lng->txt("style_assignments_deleted").$style->getName(),
                    ilSystemStyleMessage::TYPE_SUCCESS
                ));
        }

        $this->getSkin()->removeStyle($style->getId());
        $this->writeSkinToXML();
        $this->getMessageStack()->prependMessage(
            new ilSystemStyleMessage($this->lng->txt("style_deleted").$style->getName(),
                ilSystemStyleMessage::TYPE_SUCCESS
            ));
    }

    public function copy(){
        $new_skin_id_addon = "";

        while(ilStyleDefinition::skinExists($this->getSkin()->getId().$new_skin_id_addon)){
            $new_skin_id_addon .= "Copy";
        }

        $new_skin_path = rtrim($this->getSkinDirectory(),"/").$new_skin_id_addon;

        mkdir($new_skin_path);
        $this->xCopy($this->getSkinDirectory(),$new_skin_path);
        $this->getMessageStack()->addMessage(new ilSystemStyleMessage($this->lng->txt("directory_created: ".$new_skin_path),ilSystemStyleMessage::TYPE_SUCCESS));
        return self::generateFromId($this->getSkin()->getId().$new_skin_id_addon);

    }

    public function export(){
        $rel_tmp_zip = "../".$this->getSkin()->getId().".zip";
        $temp_zip = rtrim($this->getSkinDirectory(),"/").".zip";
        ilUtil::zip($this->getSkinDirectory(),$rel_tmp_zip,true);
        ilFileDelivery::deliverFileAttached($temp_zip, $this->getSkin()->getId().".zip",null, true);
    }


    /**
     * @param $import_zip_path
     * @param $name
     * @param ilSystemStyleMessageStack|null $message_stack
     * @return ilSystemStyleSkinContainer
     * @throws ilSystemStyleException
     */
    public static function import($import_zip_path, $name, ilSystemStyleMessageStack $message_stack = null){
        $skin_id = rtrim($name,".zip");

        while(ilStyleDefinition::skinExists($skin_id)){
            $skin_id .= "Duplicate";
        }

        $skin_path = ilStyleDefinition::CUSTOMIZING_SKINS_PATH.$skin_id;
        mkdir($skin_path);

        $temp_zip_path = $skin_path."/".$name;
        move_uploaded_file ( $import_zip_path ,$temp_zip_path );
        ilUtil::unzip($temp_zip_path);
        unlink($temp_zip_path);

        return self::generateFromId($skin_id,$message_stack);
    }

    public function compileLess($style_id){
        global $DIC;

        $lessc_path =  $DIC['ilias']->ini->readVariable("tools","lessc");

        if(!$lessc_path){
            throw new ilSystemStyleException(ilSystemStyleException::LESS);
        }
        $output = shell_exec($lessc_path." ".$this->getLessFilePath($style_id));
        if(!$output){
            $less_error = shell_exec($lessc_path." ".$this->getLessFilePath($style_id)." 2>&1");
            if(!$less_error){
                throw new ilSystemStyleException(ilSystemStyleException::LESS_COMPILE_FAILED, "Empty css output, unknown error.");
            }
            throw new ilSystemStyleException(ilSystemStyleException::LESS_COMPILE_FAILED, $less_error);
        }
        file_put_contents($this->getCSSFilePath($style_id),$output);
    }



    /**
     * @return ilSkinXML
     */
    public function getSkin()
    {
        return $this->skin;
    }

    /**
     * @param ilSkinXML $skin
     */
    public function setSkin($skin)
    {
        $this->skin = $skin;
    }

    /**
     * @return mixed
     */
    public function getSkinDirectory()
    {
        return $this->customizing_skin_directory;
    }

    /**
     * @param mixed $customizing_skin_directory
     */
    public function setSkinDirectory($customizing_skin_directory)
    {
        $this->customizing_skin_directory = $customizing_skin_directory;
    }

    public function getCSSFilePath($style_id){
        return $this->customizing_skin_directory.$this->getSkin()->getStyle($style_id)->getCssFile().".css";
    }

    public function getLessFilePath($style_id){
        return $this->customizing_skin_directory.$this->getSkin()->getStyle($style_id)->getCssFile().".less";
    }

    public function getLessVariablesFilePath($style_id){
        return $this->customizing_skin_directory.$this->getSkin()->getStyle($style_id)->getCssFile()."-variables.less";
    }

    public function getImagesSkinPath($style_id){
        return $this->customizing_skin_directory.$this->getSkin()->getStyle($style_id)->getImageDirectory();
    }



    /**
     * @return array|null
     */
    protected static function getCachedAllStylesInformation()
    {
        return self::$cached_all_styles_information;
    }

    /**
     * @param array|null $cached_all_styles_information
     */
    protected static function setCachedAllStylesInformation($cached_all_styles_information)
    {
        self::$cached_all_styles_information = $cached_all_styles_information;
    }

    /**
     * @return ilSystemStyleMessageStack
     */
    public static function getMessageStack()
    {
        return self::$message_stack;
    }

    /**
     * @param ilSystemStyleMessageStack $message_stack
     */
    public static function setMessageStack($message_stack)
    {
        self::$message_stack = $message_stack;
    }

    public function addStyle(ilSkinStyleXML $style){
        $this->getSkin()->addStyle($style);
        $old_style = new ilSkinStyleXML("","");
        $this->updateStyle($style->getId(),  $old_style);
    }

    protected function writeSkinToXML(){
        $this->getSkin()->writeToXMLFile($this->getSkinDirectory()."template.xml");
    }
}