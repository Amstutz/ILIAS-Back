<?php
include_once("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/classes/Models/Less/class.KitchenSinkLessFile.php");
include_once("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/classes/Models/Less/class.KitchenSinkLessCategory.php");
include_once("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/classes/Models/Less/class.KitchenSinkLessComment.php");
include_once("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/classes/Models/Less/class.KitchenSinkLessVariable.php");
include_once("./Services/Xml/classes/class.ilXmlWriter.php");
include_once("./Services/Form/classes/class.ilPropertyFormGUI.php");

/**
 *
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$*
 */
class ilKitchenSinkLessGUI2
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
     * @var ilSystemStyleGUI
     */
    protected $parent;

    /**
     * @var KitchenSinkSkin
     */
    protected $skin;

    /**
     * @var string
     */
    protected $form_elements_prefix = "ks-";

    /**
     * ilKitchenSinkLessGUI constructor.
     * @param ilSystemStyleGUI $parent
     * @param KitchenSinkSkin $skin
     */
    public function __construct(ilSystemStyleGUI $parent, KitchenSinkSkin $skin) {
        /**
         * @var ilObjUser $ilUser
         */
        global $ilCtrl, $ilUser, $tpl;

        $this->user = $ilUser;
        $this->setParent($parent);
        $this->ctrl = $ilCtrl;
        $this->tpl = $tpl;
        $this->setSkin($skin);
    }


    public function initLessVariablesForm()
    {
        $this->form = new ilPropertyFormGUI();

        $this->form->setTitle("Adapt Less Variables");

        $focus_variable = $_GET['variable'];
        if($focus_variable){
            $this->tpl->addOnLoadCode("setTimeout(function() { $('#".$this->getFormElementsPrefix().$focus_variable."').focus();}, 100);");
        }

        foreach($this->getSkin()->getLessFile()->getCategories() as $category){
            $section = new ilFormSectionHeaderGUI();
            $section->setTitle($category->getName());
            $section->setInfo($category->getComment());
            $section->setSectionAnchor($category->getName());
            $this->form->addItem($section);
            foreach($this->getSkin()->getLessFile()->getVariablesPerCategory($category->getName()) as $variable){
                $input = new ilTextInputGUI($variable->getName(), $this->getFormElementsPrefix().$variable->getName());
                $input->setRequired(true);
                $input->setInfo($variable->getComment());
                $this->form->addItem($input);

            }
        }

        $this->form->addCommandButton("lessResetVariables", "Reset Variables");
        $this->form->addCommandButton("lessUpdatedVariables", "Update Variables");

        $this->form->setFormAction($this->ctrl->getFormAction($this->getParent()));
    }


    function getLessValues()
    {
        $values = array();
        foreach($this->getSkin()->getLessFile()->getCategories() as $category){
            foreach($this->getSkin()->getLessFile()->getVariablesPerCategory($category->getName()) as $variable){
                $values[$this->form_elements_prefix.$variable->getName()] = $variable->getValue();
            }
        }

        $this->form->setValuesByArray($values);
    }
    public function resetLess()
    {
        $this->getSkin()->resetLess();
        $this->initLessVariablesForm();
        $this->ctrl->redirect($this->getParent(), "less");
    }

    public function updateLess()
    {
        $this->skin->readLessVariables();
        $this->initLessVariablesForm();
        if ($this->form->checkInput())
        {
            foreach($this->getSkin()->getLessFile()->getCategories() as $category){
                foreach($this->getSkin()->getLessFile()->getVariablesPerCategory($category->getName()) as $variable){
                    $variable->setValue($this->form->getInput($this->form_elements_prefix.$variable->getName()));
                }
            }
            $this->skin->updateLess();
            $this->ctrl->redirect($this->getParent(), "less");
        }

        $this->form->setValuesByPost();
        return $this->form->getHtml();
    }

    /**
     * @return html
     */
    public function renderLess(){
        $this->skin->readLessVariables();
        $this->initLessVariablesForm();
        $this->getLessValues();
        return $this->form->getHtml();
    }

    /**
     * @return ilTemplate
     */
    public function getTpl()
    {
        return $this->tpl;
    }

    /**
     * @param ilTemplate $tpl
     */
    public function setTpl($tpl)
    {
        $this->tpl = $tpl;
    }

    /**
     * @return ilCtrl
     */
    public function getCtrl()
    {
        return $this->ctrl;
    }

    /**
     * @param ilCtrl $ctrl
     */
    public function setCtrl($ctrl)
    {
        $this->ctrl = $ctrl;
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

    /**
     * @return KitchenSinkSkin
     */
    public function getSkin()
    {
        return $this->skin;
    }

    /**
     * @param KitchenSinkSkin $skin
     */
    public function setSkin($skin)
    {
        $this->skin = $skin;
    }

    /**
     * @return string
     */
    public function getFormElementsPrefix()
    {
        return $this->form_elements_prefix;
    }

    /**
     * @param string $form_elements_prefix
     */
    public function setFormElementsPrefix($form_elements_prefix)
    {
        $this->form_elements_prefix = $form_elements_prefix;
    }


}
?>