<?php


include_once("./Services/UIComponent/Panel/classes/class.ilPanelGUI.php");
include_once("class.ilKSDocumentationEntryGUI.php");
include_once("class.ilKitchenSinkLessGUI.php");
include_once("class.ilKitchenSinkIconsGUI.php");

require_once "Services/UIComponent/Button/classes/class.ilLinkButton.php";

/**
 *
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$*
 */
class ilKitchenSinkMainGUI2
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
     * @var ilTabsGUI
     */
    protected $tabs_gui;
    /**
     * @var xbgLngVarPrefixer
     */
    protected $lng_xbg;

    /**
     * @var ilKitchenSinkEntryExplorerGUI
     */
    protected $explorer = null;

    /**
     * @var ILIAS\UI\Factory
     */
    protected $ui_factory = null;

    /**
     * @var ilKitchenSinkEntryGUI
     */
    protected $entryGUI = null;


    public function __construct() {
        global $ilCtrl, $tpl, $ilTabs;
        $this->ctrl = $ilCtrl;

        $this->tpl = $tpl;
        $tpl->getStandardTemplate();

        $this->tabs_gui = &$ilTabs;

        $this->tpl->setTitle('Kitchen Sink');
        //$this->setUiFactory(new KSFactory());
    }


    /**
     * @return bool
     */
    public function executeCommand() {




        $this->setTabs();

        // determine next class in the call structure
        $next_class = $this->ctrl->getNextClass($this);
        switch ($next_class) {
            default:
                $cmd = ($this->ctrl->getCmd()) ? $this->ctrl->getCmd() : $this->getStandardCommand();

                switch ($cmd) {
                    case 'entries':
                    case 'parseEntries':
                        $this->tabs_gui->activateTab('entries');
                        include_once("class.ilKSDocumentationGUI.php");
                        $documentation = new ilKSDocumentationGUI($this);
                        $documentation->entries($cmd);
                        break;
                    case 'icons':
                        $this->tabs_gui->activateTab('icons');
                        $this->icons();
                        break;
                    case 'updateIcons':
                        $this->tabs_gui->activateTab('icons');
                        $this->updateIcons();
                        break;
                    case 'resetIcons':
                        $this->tabs_gui->activateTab('icons');
                        $this->resetIcons();
                        break;
                    case 'less':
                        $this->tabs_gui->activateTab('less');
                        $this->less();
                        break;
                    case 'lessUpdatedVariables':
                        $this->tabs_gui->activateTab('less');
                        $this->updateLess();
                        break;
                    case 'lessResetVariables':
                        $this->tabs_gui->activateTab('less');
                        $this->resetLess();
                        break;
                }
        }
        return TRUE;
    }

    protected function setTabs() {
        $this->tabs_gui->addTab('entries', 'Entries', $this->ctrl->getLinkTarget($this, 'entries'));
        $this->tabs_gui->addTab('less', 'Less', $this->ctrl->getLinkTarget($this, 'less'));
        $this->tabs_gui->addTab('icons', 'Icons', $this->ctrl->getLinkTarget($this, 'icons'));
    }

    /**
     * @return string
     */
    protected function getStandardCommand() {
        return 'entries';
    }


    protected function less(){

        $less = new ilKitchenSinkLessGUI($this,new KitchenSinkSkin());
        $this->tpl->setContent($less->renderLess());
        $this->tpl->show();
    }
    protected function updateLess(){
        $less = new ilKitchenSinkLessGUI($this,new KitchenSinkSkin());
        $this->tpl->setContent($less->updateLess());
        $this->tpl->show();
    }
    protected function resetLess(){
        $less = new ilKitchenSinkLessGUI($this,new KitchenSinkSkin());
        $less->resetLess();
    }

    protected function icons(){
        $icons = new ilKitchenSinkIconsGUI($this,new KitchenSinkSkin(), $this->getUiFactory());
        $this->tpl->setContent($icons->renderIcons());
        $this->tpl->show();
    }
    protected function updateIcons(){
        $icons = new ilKitchenSinkIconsGUI($this,new KitchenSinkSkin(), $this->getUiFactory());
        $this->tpl->setContent($icons->updateIcons());
        $this->tpl->show();
    }
    protected function resetIcons(){
        $icons = new ilKitchenSinkIconsGUI($this,new KitchenSinkSkin(), $this->getUiFactory());
        $this->tpl->setContent($icons->resetIcons());
        $this->tpl->show();
    }

}
?>
