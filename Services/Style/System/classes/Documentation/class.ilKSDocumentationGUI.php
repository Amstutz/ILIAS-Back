<?php
require_once("libs/composer/vendor/autoload.php");
require_once("libs/composer/vendor/symfony/yaml/Yaml.php");
include_once("class.ilKSDocumentationExplorerGUI.php");
include_once("class.ilKSDocumentationEntryGUI.php");

use ILIAS\UI\Implementation\Crawler as Crawler;

/**
 *
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$*
 */
class ilKSDocumentationGUI
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
     * @var ilObjStyleSettingsGUI
     */
    protected $parent;

    const KS_ROOT_FACTORY_PATH = "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/data/abstractDataFactory.php";
    const KS_DATA_DIRECTORY = "./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/data";
    const KS_DATA_FILE = "data.json";
    public static $KS_DATA_PATH;

    public function __construct(ilObjStyleSettingsGUI  $parent) {
        global $ilCtrl, $tpl;

        $this->ctrl = $ilCtrl;
        $this->tpl = $tpl;

        self::$KS_DATA_PATH= self::KS_DATA_DIRECTORY."/".self::KS_DATA_FILE;
        $this->setParent($parent);
    }

    public function entries($cmd){
        if($cmd == "parseEntries"){
            $entries = $this->parseEntries();
        }else{
            $entries = $this->readEntries();
        }
        $toolbar = new ilToolbarGUI();
        $reload_btn = ilLinkButton::getInstance();
        $reload_btn->setCaption('Refresh Entries Test',false);
        if($_GET["node_id"]){
            $this->ctrl->saveParameter($this->getParent(),"node_id");
        }
        $reload_btn->setUrl($this->ctrl->getLinkTarget($this->getParent(), 'parseEntries'));
        $toolbar->addButtonInstance($reload_btn);
        $explorer = new ilKSDocumentationExplorerGUI($this->parent, "entries", $entries, $_GET["node_id"]);
        $this->tpl->setLeftNavContent($explorer->getHTML());
        $entry_gui = new ilKSDocumentationEntryGUI($this,$explorer->getCurrentOpenedNode(), $entries);
        $this->tpl->setContent($toolbar->getHTML().$entry_gui->renderEntry());
    }

    protected function parseEntries(){
        $crawler = new Crawler\FactoriesCrawler();
        $entries = $crawler->crawlFactory(self::KS_ROOT_FACTORY_PATH);
        file_put_contents(self::$KS_DATA_PATH, json_encode($entries));
        ilUtil::sendSuccess("All Entries have been reloaded",true);
        return $entries;
    }

    protected function readEntries(){
        $entries_array = json_decode(file_get_contents(self::$KS_DATA_PATH),true);

        $entries = new Crawler\Entry\ComponentEntries();
        foreach($entries_array as $entry_array){
            $entry = new Crawler\Entry\ComponentEntry($entry_array);
            $entries->addEntry($entry);
        }

        return $entries;
    }

    /**
     * @return KitchenSinkEntry
     */
    public function getEntry()
    {
        return $this->entry;
    }

    /**
     * @param KitchenSinkEntry $entry
     */
    public function setEntry($entry)
    {
        $this->entry = $entry;
    }

    /**
     * @return KitchenSinkTree
     */
    public function getTree()
    {
        return $this->tree;
    }

    /**
     * @param KitchenSinkTree $tree
     */
    public function setTree($tree)
    {
        $this->tree = $tree;
    }

    /**
     * @return ilKitchenSinkMainGUI
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param ilKitchenSinkMainGUI $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }
}
?>
