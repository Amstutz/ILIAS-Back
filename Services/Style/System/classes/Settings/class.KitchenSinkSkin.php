<?php
include_once("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/classes/Exceptions/class.ilKitchenSinkException.php");
include_once("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/classes/Models/Less/class.KitchenSinkLessFile.php");
include_once("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/classes/Models/Less/class.KitchenSinkLessCategory.php");
include_once("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/classes/Models/Less/class.KitchenSinkLessComment.php");
include_once("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/classes/Models/Less/class.KitchenSinkLessVariable.php");
include_once("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/classes/Models/Icon/class.KitchenSinkIconFolder.php");
include_once("./Services/Xml/classes/class.ilXmlWriter.php");
include_once("./Services/Form/classes/class.ilPropertyFormGUI.php");

/**
 *
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$*
 */
class KitchenSinkSkin
{
    /**
     * @var ilTemplate
     */
    protected $tpl;

    /**
     * @var ilCtrl $ctrl
     */
    protected $ctrl;

    /**
     * @var ilObjUser $user;
     */
    protected $user;
    /**
     * @var string
     */
    static $default_less_file = './templates/default/delos.less';

    /**
     * @var string
     */
    static $default_less_variables_file = "./templates/default/less/variables.less";

    /**
     * @var string
     */
    protected $default_images_dir = "./templates/default/images";

    /**
     * @var string
     */
    protected $default_font_dir = "./templates/default/fonts";
    /**
     * @var string
     */
    static $skin_name = "";

    /**
     * @var string
     */
    protected $skin_dir = "";

    /**
     * @var string
     */
    protected $skin_images_dir = "";

    /**
     * @var string
     */
    protected $skin_variables_file_name = "variables.less";

    /**
     * @var string
     */
    protected $skin_less_file_dir = "";

    /**
     * @var string
     */
    protected $skin_font_dir = "";

    /**
     * @var KitchenSinkLessFile
     */
    protected $less_file = null;


    /**
     * @var KitchenSinkIconFolder
     */
    protected $icon_folder = null;

    /**
     * @var KitchenSinkIconColorSet
     */
    protected $icon_color_set = null;
    /**
     * KitchenSinkSkin constructor.
     */
    public function __construct() {
        /**
         * @var ilObjUser $ilUser
         */
        global $ilCtrl, $ilUser, $tpl;

        $this->user = $ilUser;
        $this->ctrl = $ilCtrl;
        $this->tpl = $tpl;

        $this->setSkinName("ksSkinOf".$this->user->getLogin());
        $this->setSkinDir("./Customizing/global/skin/".$this->getSkinName());
        $this->setSkinImagesDir($this->getSkinDir()."/images");
        $this->setSkinLessFileDir(ILIAS_ABSOLUTE_PATH."/Customizing/global/skin/".$this->getSkinName()."/".$this->getSkinName().".less");
        $this->setSkinFontDir("./Customizing/global/skin/".$this->getSkinName()."/fonts");

    }

    static function xCopy($src, $dest)
    {
        foreach (scandir($src) as $file) {
            $src_file = rtrim($src, '/') . '/' . $file;
            $dest_file = rtrim($dest, '/') . '/' . $file;
            if (!is_readable($src_file)) {
                throw new ilKitchenSinkException(ilKitchenSinkException::FILE_OPENING_FAILED, $src_file);
            }
            if (substr($file, 0, 1) != ".") {
                if (is_dir($src_file)) {
                    if (!file_exists($dest_file)) {
                        try {
                            mkdir($dest_file);
                        } catch (Exception $e) {
                            throw new ilKitchenSinkException(ilKitchenSinkException::FOLDER_CREATION_FAILED, "Copy " . $src_file . " to " . $dest_file . " Error: " . $e);
                        }
                    }
                    self::xCopy($src_file, $dest_file);
                } else {
                    try {
                        copy($src_file,$dest_file);
                    } catch (Exception $e) {
                        throw new ilKitchenSinkException(ilKitchenSinkException::FILE_CREATION_FAILED, "Copy " . $src_file . " to " . $dest_file . " Error: " . $e);
                    }

                }

            }
        }
    }


    protected function copyImagesFolderRecursively(){
        try {
            mkdir($this->getSkinImagesDir(), 0775, $recursive = true);
        }catch(Exception $e)
        {
            throw new ilKitchenSinkException(ilKitchenSinkException::FOLDER_CREATION_FAILED, $this->getSkinImagesDir()." ".$e->getMessage());
        }
        self::xCopy($this->getDefaultImagesDir(),$this->getSkinImagesDir());
    }

    protected function copyFontsFolderRecursively(){
        try {
            mkdir($this->getSkinFontDir() , 0775 ,  $recursive = true );
        }catch(Exception $e)
        {
            throw new ilKitchenSinkException(ilKitchenSinkException::FOLDER_CREATION_FAILED, $this->getSkinFontDir()." ".$e->getMessage());
        }
        self::xCopy($this->getDefaultFontDir(),$this->getSkinFontDir());
    }

    public function createSkin(){
        if(!file_exists (  $this->getSkinDir() )) {
            if (!mkdir($this->getSkinDir(), 0775, $recursive = true)) {
                throw new ilKitchenSinkException(ilKitchenSinkException::FOLDER_CREATION_FAILED, $this->getSkinDir());
            }
        }

        if(!file_exists (  $this->getSkinImagesDir() )){
            $this->copyImagesFolderRecursively();
        }
        if(!file_exists (  $this->getSkinFontDir() )){
            $this->copyFontsFolderRecursively();
        }

        $less_name = $this->getSkinDir()."/".$this->getSkinName().".less" ;
        if(!file_exists($less_name)){
            file_put_contents( $less_name, "@import '".self::getDefaultLessFileDir()."';@import '".$this->getSkinVariablesFileName()."';");
            file_put_contents( $this->getSkinDir()."/".$this->getSkinVariablesFileName(),$this->getLessFile()->write());
        }

        if(!file_exists($this->getSkinDir()."/template.xml")){
            $xml_writer = new ilXmlWriter();
            $xml_writer->xmlHeader();
            $xml_writer->xmlStartTag("template",array(
                "xmlns"=>"http://www.w3.org",
                "version"=>"1",
                "name"=>$this->getSkinName()));
            $xml_writer->xmlStartTag("style",array(
                "name"=>"Kitchen Sink Style of ".$this->user->getLogin(),
                "id"=>$this->getSkinName(),
                "image_directory"=>"images"));

            $xml_writer->xmlEndTag("style");
            $xml_writer->xmlEndTag("template");
            file_put_contents($this->getSkinDir()."/template.xml",$xml_writer->xmlDumpMem(true));
        }
    }
    public function updateLessVariables(){
        file_put_contents( $this->getSkinDir()."/".$this->getSkinVariablesFileName(),$this->getLessFile()->write());

    }

    protected function compileLess(){
        $output = shell_exec("lessc ".$this->getSkinLessFileDir());
        if(!$output){
            $less_error = shell_exec("lessc ".$this->getSkinLessFileDir()." 2>&1");
            if(!$less_error){
                throw new ilKitchenSinkException(ilKitchenSinkException::LESS_COMPILE_FAILED, "Empty css output, unknown error.");
            }
            throw new ilKitchenSinkException(ilKitchenSinkException::LESS_COMPILE_FAILED, $less_error);
        }
        file_put_contents( $this->getSkinDir()."/".$this->getSkinName().".css",$output);

    }

    protected function selectSkin(){
        if(!file_exists (  $this->getSkinDir() )){
            throw new ilKitchenSinkException(ilKitchenSinkException::SKIN_FOLDER_DOES_NOT_EXIST,$this->getSkinDir());

        }else if(!file_exists($this->getSkinDir()."/".$this->getSkinName().".css")) {
            throw new ilKitchenSinkException(ilKitchenSinkException::SKIN_CSS_DOES_NOT_EXIST,$this->getSkinDir()."/".$this->getSkinName().".css");
        } else {
            $this->user->writePref("skin",$this->getSkinName());
            $this->user->writePref("style",$this->getSkinName());
        }

    }


    public function readIcons(){
        $this->createSkin();
        $icons_folder = new KitchenSinkIconFolder($this->getDefaultImagesDir(),$this->getSkinImagesDir());
        $icons_folder->read();
        $this->setIconFolder($icons_folder);

        $color_set = KitchenSinkIconColorSet::loadColorSetFromFileIfExists($this->getSkinDir());
        $this->setIconColorSet($color_set);

        $this->getIconFolder()->findIconColorUsages($this->getIconColorSet());
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

    public function resetIcons(){
        try{
            $this->recursiveRemoveDir($this->getSkinImagesDir());
        }catch(Exception $e){
            throw new ilKitchenSinkException(ilKitchenSinkException::FOLDER_DELETION_FAILED, " Path: ".$this->getSkinImagesDir(). " Error ".$e->getMessage());
        }
        try{
            if(file_exists($this->getSkinDir()."/".KitchenSinkIconColorSet::getColorSetFileName())){
                try{
                    unlink($this->getSkinDir()."/".KitchenSinkIconColorSet::getColorSetFileName());
                }catch (Exception $e){
                    throw new ilKitchenSinkException(ilKitchenSinkException::FILE_DELETION_FAILED, $this->getSkinDir()."/".KitchenSinkIconColorSet::getColorSetFileName());
                }
            }

        }catch(Exception $e){
            throw new ilKitchenSinkException(ilKitchenSinkException::FILE_DELETION_FAILED, " Path: ".$this->getSkinImagesDir(). " Error ".$e->getMessage());
        }
        $this->copyImagesFolderRecursively();
        $color_set = new KitchenSinkIconColorSet();
        $color_set->setDefaultColors();
        $this->setIconColorSet($color_set);
    }

    /**
     * @param KitchenSinkIconColorSet $color_set
     */
    public function updateIcons(KitchenSinkIconColorSet $color_set){
        $color_set->writeColorSetToFile($this->getSkinDir());
        $this->getIconFolder()->changeIconColors($color_set);
    }

    /**
     * @param bool|false $force_delos
     * @throws ilKitchenSinkException
     */
    public function readLessVariables($force_delos = false){

        if($force_delos || !file_exists (  $this->getSkinDir()."/".$this->getSkinVariablesFileName())){
            $variable_file = $this->getDefaultLessVariablesFile();
        } else{
            $variable_file = $this->getSkinDir()."/".$this->getSkinVariablesFileName();
        }
        $this->less_file = new KitchenSinkLessFile($variable_file);
        $this->less_file->read();
    }

    public function resetLess()
    {
        $this->readLessVariables(true);
        $this->updateLess();

    }

    public function updateLess(){
        $this->createSkin();
        $this->updateLessVariables();
        $this->compileLess();
        $this->selectSkin();
    }

    /**
     * @return string
     */
    public static function getDefaultLessFileDir()
    {
        return self::$default_less_file;
    }

    /**
     * @param string $default_less_file_dir
     */
    public static function setDefaultLessFileDir($default_less_file_dir)
    {
        self::$default_less_file = $default_less_file_dir;
    }

    /**
     * @return string
     */
    public static function getDefaultLessFile()
    {
        return self::$default_less_file;
    }

    /**
     * @param string $default_less_file
     */
    public static function setDefaultLessFile($default_less_file)
    {
        self::$default_less_file = $default_less_file;
    }

    /**
     * @return string
     */
    public static function getDefaultLessVariablesFile()
    {
        return self::$default_less_variables_file;
    }

    /**
     * @param string $default_less_variables_file
     */
    public static function setDefaultLessVariablesFile($default_less_variables_file)
    {
        self::$default_less_variables_file = $default_less_variables_file;
    }

    /**
     * @return string
     */
    public function getSkinLessFileDir()
    {
        return $this->skin_less_file_dir;
    }

    /**
     * @param string $skin_less_file_dir
     */
    public function setSkinLessFileDir($skin_less_file_dir)
    {
        $this->skin_less_file_dir = $skin_less_file_dir;
    }



    /**
     * @return string
     */
    public function getDefaultImagesDir()
    {
        return $this->default_images_dir;
    }

    /**
     * @param string $default_images_dir
     */
    public function setDefaultImagesDir($default_images_dir)
    {
        $this->default_images_dir = $default_images_dir;
    }

    /**
     * @return string
     */
    public function getDefaultFontDir()
    {
        return $this->default_font_dir;
    }

    /**
     * @param string $default_font_dir
     */
    public function setDefaultFontDir($default_font_dir)
    {
        $this->default_font_dir = $default_font_dir;
    }

    /**
     * @return string
     */
    public static function getSkinName()
    {
        return self::$skin_name;
    }

    /**
     * @param string $skin_name
     */
    public static function setSkinName($skin_name)
    {
        self::$skin_name = $skin_name;
    }

    /**
     * @return string
     */
    public function getSkinDir()
    {
        return $this->skin_dir;
    }

    /**
     * @param string $skin_dir
     */
    public function setSkinDir($skin_dir)
    {
        $this->skin_dir = $skin_dir;
    }

    /**
     * @return string
     */
    public function getSkinImagesDir()
    {
        return $this->skin_images_dir;
    }

    /**
     * @param string $skin_images_dir
     */
    public function setSkinImagesDir($skin_images_dir)
    {
        $this->skin_images_dir = $skin_images_dir;
    }

    /**
     * @return string
     */
    public function getSkinVariablesFileName()
    {
        return $this->skin_variables_file_name;
    }

    /**
     * @param string $skin_variables_file_name
     */
    public function setSkinVariablesFileName($skin_variables_file_name)
    {
        $this->skin_variables_file_name = $skin_variables_file_name;
    }



    /**
     * @return string
     */
    public function getSkinFontDir()
    {
        return $this->skin_font_dir;
    }

    /**
     * @param string $skin_font_dir
     */
    public function setSkinFontDir($skin_font_dir)
    {
        $this->skin_font_dir = $skin_font_dir;
    }

    /**
     * @return KitchenSinkLessFile
     */
    public function getLessFile()
    {
        return $this->less_file;
    }

    /**
     * @param KitchenSinkLessFile $less_file
     */
    public function setLessFile($less_file)
    {
        $this->less_file = $less_file;
    }

    /**
     * @return KitchenSinkIconFolder
     */
    public function getIconFolder()
    {
        return $this->icon_folder;
    }

    /**
     * @param KitchenSinkIconFolder $icon_folder
     */
    public function setIconFolder($icon_folder)
    {
        $this->icon_folder = $icon_folder;
    }

    /**
     * @return KitchenSinkIconColorSet
     */
    public function getIconColorSet()
    {
        return $this->icon_color_set;
    }

    /**
     * @param KitchenSinkIconColorSet $icon_color_set
     */
    public function setIconColorSet($icon_color_set)
    {
        $this->icon_color_set = $icon_color_set;
    }



}
?>