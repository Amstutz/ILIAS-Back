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
        if(file_exists($this->getCustomizingSkinDirectory())){
            throw new ilSystemStyleException(ilSystemStyleException::SKIN_ALREADY_EXISTS,$this->getCustomizingSkinDirectory());
        }
        mkdir($this->getCustomizingSkinDirectory());
        $this->writeSkinToXML();

        foreach($this->getSkin()->getStyles() as $style){
            $this->createImagesDirectory($style);
            $this->createFontsDirectory($style);
            $this->createSoundsDirectory($style);
            $this->createLessStructure($style);
        }

    }

    public function update(ilSkinXML $old_skin, $old_style_id){
        $old_customizing_skin_directory = ilStyleDefinition::CUSTOMIZING_SKINS_PATH.$old_skin->getId()."/";
        $old_style = $old_skin->getStyle($old_style_id);

        //Move if skin has been renamed
        if($old_skin->getId()!= $this->getSkin()->getId()){
            $this->move($old_customizing_skin_directory,$this->getCustomizingSkinDirectory());
        }

        //Delete old template.xml and write a new one
        unlink($old_customizing_skin_directory."template.xml");
        $this->writeSkinToXML();

        //For each style, make sure, that all directories exist
        foreach($this->getSkin()->getStyles() as $style){
            if(file_exists($old_customizing_skin_directory.$old_style->getImageDirectory())){
                rename($old_customizing_skin_directory.$old_style->getImageDirectory(), $this->getCustomizingSkinDirectory().$style->getImageDirectory());
            }else{
                $this->createImagesDirectory($style);
            }
            if(file_exists($old_customizing_skin_directory.$old_style->getFontDirectory())){
                rename($old_customizing_skin_directory.$old_style->getFontDirectory(), $this->getCustomizingSkinDirectory().$style->getFontDirectory());
            }else{
                $this->createFontsDirectory($style);
            }
            if(file_exists($old_customizing_skin_directory.$old_style->getSoundDirectory())){
                rename($old_customizing_skin_directory.$old_style->getSoundDirectory(), $this->getCustomizingSkinDirectory().$style->getSoundDirectory());
            }else{
                $this->createSoundsDirectory($style);
            }
            if(file_exists($old_customizing_skin_directory.$old_style->getCssFile().".less")){
                rename($old_customizing_skin_directory.$old_style->getCssFile().".less", $this->getCustomizingSkinDirectory().$style->getCssFile().".less");
            }else{
                $this->createMainLessFile($style);
            }
            if(file_exists($old_customizing_skin_directory.dirname($old_style->getCssFile())."/variables.less")){
                rename($old_customizing_skin_directory.dirname($old_style->getCssFile())."/variables.less", $this->getLessVariablesFilePath($style->getId()));
            }else{
                $this->copyVariablesFromDefault($style);
            }
            if(file_exists($old_customizing_skin_directory.$old_style->getCssFile().".css")){
                rename($old_customizing_skin_directory.$old_style->getCssFile().".css", $this->getCustomizingSkinDirectory().$style->getCssFile().".css");
            }else{
                try{
                    $this->compileLess($style->getId());
                }catch(Exception $e){
                    $this->getMessageStack()->addMessage(
                        new ilSystemStyleMessage($e->getMessage(),
                        ilSystemStyleMessage::TYPE_SUCCESS
                        ));
                    copy (ilStyleDefinition::DELOS_PATH.".css",$this->getCSSFilePath($style->getId()));
                }
            }

        }
    }

    /**
     * @param ilSkinStyleXML $style
     * @throws ilSystemStyleException
     */
    protected function createImagesDirectory(ilSkinStyleXML $style){
        $path = $this->getCustomizingSkinDirectory().$style->getImageDirectory();
        mkdir($path);
        self::xCopy(ilStyleDefinition::DEFAULT_IMAGES_PATH,$path);
        $this->getMessageStack()->addMessage(
            new ilSystemStyleMessage($this->lng->txt("image_dir_created").$path,
                ilSystemStyleMessage::TYPE_SUCCESS
            ));

    }

    /**
     * @param ilSkinStyleXML $style
     * @throws ilSystemStyleException
     */
    protected function createFontsDirectory(ilSkinStyleXML $style){
        $path = $this->getCustomizingSkinDirectory().$style->getFontDirectory();
        mkdir($path);
        self::xCopy(ilStyleDefinition::DEFAULT_FONTS_PATH,$path);
        $this->getMessageStack()->addMessage(
            new ilSystemStyleMessage($this->lng->txt("fonts_dir_created").$path,
            ilSystemStyleMessage::TYPE_SUCCESS
        ));

    }

    /**
     * @param ilSkinStyleXML $style
     */
    protected function createSoundsDirectory(ilSkinStyleXML $style){
        $path = $this->getCustomizingSkinDirectory().$style->getSoundDirectory();
        mkdir($path);
        $this->getMessageStack()->addMessage(
            new ilSystemStyleMessage($this->lng->txt("sounds_dir_created").$path,
                ilSystemStyleMessage::TYPE_SUCCESS
            ));

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
        file_put_contents($path,$this->getLessMainFileContent());
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



    protected function getLessMainFileContent(){
        $content = "@import \"".ilStyleDefinition::DELOS_PATH."\";\n";
        $content .= "// Import Custom Less Files here\n";
        $content .= "@import \"variables.less\";\n";
        return $content;
    }

    public function move($from,$to){
        rename($from,$to);
    }


    public function delete(){
        $this->recursiveRemoveDir($this->getCustomizingSkinDirectory());
        $this->getMessageStack()->addMessage(
            new ilSystemStyleMessage($this->lng->txt("skin_deleted").$this->getCustomizingSkinDirectory(),
                ilSystemStyleMessage::TYPE_SUCCESS
            ));
    }

    public function copy(){
        $new_skin_id_addon = "";

        while(ilStyleDefinition::skinExists($this->getSkin()->getId().$new_skin_id_addon)){
            $new_skin_id_addon .= "Copy";
        }

        $new_skin_path = rtrim($this->getCustomizingSkinDirectory(),"/").$new_skin_id_addon;

        mkdir($new_skin_path);
        $this->xCopy($this->getCustomizingSkinDirectory(),$new_skin_path);
        $this->getMessageStack()->addMessage(new ilSystemStyleMessage($this->lng->txt("directory_created: ".$new_skin_path),ilSystemStyleMessage::TYPE_SUCCESS));
        return self::generateFromId($this->getSkin()->getId().$new_skin_id_addon);

    }

    public function export(){
        $rel_tmp_zip = "../".$this->getSkin()->getId().".zip";
        $temp_zip = rtrim($this->getCustomizingSkinDirectory(),"/").".zip";
        ilUtil::zip($this->getCustomizingSkinDirectory(),$rel_tmp_zip,true);
        ilFileDelivery::deliverFileAttached($temp_zip, $this->getSkin()->getId().".zip",null, true);
    }


    /**
     * @param $import_zip_path
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
        $output = shell_exec("lessc ".$this->getLessFilePath($style_id));
        if(!$output){
            $less_error = shell_exec("lessc ".$this->getLessFilePath($style_id)." 2>&1");
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
    public function getCustomizingSkinDirectory()
    {
        return $this->customizing_skin_directory;
    }

    /**
     * @param mixed $customizing_skin_directory
     */
    public function setCustomizingSkinDirectory($customizing_skin_directory)
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
        return $this->customizing_skin_directory.dirname($this->getSkin()->getStyle($style_id)->getCssFile())."/variables.less";
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
        $this->writeSkinToXML();
    }

    protected function writeSkinToXML(){
        $this->getSkin()->writeToXMLFile($this->getCustomizingSkinDirectory()."template.xml");
    }
}