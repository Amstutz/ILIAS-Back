<?php
include_once "Services/Form/classes/class.ilPropertyFormGUI.php";
include_once("Services/Style/System/classes/Utilities/class.ilSystemStyleSkinContainer.php");
include_once("Services/Style/System/classes/Less/class.ilSystemStyleLessFile.php");


/**
 *
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$*
 */
class ilSystemStyleLessGUI
{
    /**
     * @var ilCtrl
     */
    protected $ctrl;

    /**
     * @var ilLanguage
     */
    protected $lng;

    /**
     * @var ilTemplate
     */
    protected $tpl;

    /**
     * @var ilSystemStyleSkinContainer
     */
    protected $style_container;


    /**
     * @var ilSystemStyleLessFile
     */
    protected $less_file;
    /**
     * Constructor
     */
    function __construct($skin_id = "",$style_id = "")
    {
        global $DIC;

        $this->ctrl = $DIC->ctrl();
        $this->lng = $DIC->language();
        $this->tpl = $DIC["tpl"];

        if($skin_id == ""){
            $skin_id = $_GET["skin_id"];
        }
        if($style_id == ""){
            $style_id = $_GET["style_id"];
        }

        try{
            $this->setStyleContainer(ilSystemStyleSkinContainer::generateFromId($skin_id));
            $less_file = new ilSystemStyleLessFile($this->getStyleContainer()->getLessVariablesFilePath($style_id));
            $this->setLessFile($less_file);
        }catch(ilSystemStyleException $e){
            ilUtil::sendFailure($e->getMessage());
            $this->ctrl->setCmd("handleNoVariables");
        }


    }

    /**
     * Execute command
     */
    function executeCommand()
    {
        $cmd = $this->ctrl->getCmd();

        switch ($cmd)
        {
            case "save":
            case "edit":
                $this->$cmd();
                break;
            case "lessUpdatedVariables":
                $this->update();
                break;
            case "handleNoVariables":
                $this->handleNoVariables();
                break;
            default:
                $this->edit();
                break;
        }
    }

    protected function handleNoVariables(){

    }

    protected function edit(){
        $form = $this->initSystemStyleLessForm();
        $this->getVariablesValues($form);
        $this->tpl->setContent($form->getHTML());

    }

    public function initSystemStyleLessForm()
    {
        $form = new ilPropertyFormGUI();

        $form->setTitle("Adapt Less Variables");

        $focus_variable = $_GET['id_less_variable'];
        if($focus_variable){
            $this->tpl->addOnLoadCode("setTimeout(function() { $('#".$focus_variable."').focus();}, 100);");
        }

        foreach($this->getLessFile()->getCategories() as $category){
            $section = new ilFormSectionHeaderGUI();
            $section->setTitle($category->getName());
            $section->setInfo($category->getComment());
            $section->setSectionAnchor($category->getName());
            $form->addItem($section);
            foreach($this->getLessFile()->getVariablesPerCategory($category->getName()) as $variable){
                $input = new ilTextInputGUI($variable->getName(), $variable->getName());
                $input->setRequired(true);
                $input->setInfo($variable->getComment());
                $form->addItem($input);

            }
        }

        $form->addCommandButton("lessResetVariables", "Reset Variables");
        $form->addCommandButton("lessUpdatedVariables", "Update Variables");

        $form->setFormAction($this->ctrl->getFormAction($this));

        return $form;
    }


    /**
     * @param ilPropertyFormGUI $form
     */
    function getVariablesValues(ilPropertyFormGUI $form)
    {
        $values = array();
        foreach($this->getLessFile()->getCategories() as $category){
            foreach($this->getLessFile()->getVariablesPerCategory($category->getName()) as $variable){
                $values[$variable->getName()] = $variable->getValue();
            }
        }

        $form->setValuesByArray($values);
    }
    public function resetLess()
    {
        //$this->getSkin()->resetLess();
        $this->initLessVariablesForm();
        $this->ctrl->redirect($this->getParent(), "less");
    }

    public function update()
    {
        $form = $this->initSystemStyleLessForm();
        if ($form->checkInput())
        {
            foreach($this->getLessFile()->getCategories() as $category){
                foreach($this->getLessFile()->getVariablesPerCategory($category->getName()) as $variable){
                    $variable->setValue($form->getInput($variable->getName()));
                }
            }
            $this->getLessFile()->write();
            $this->getStyleContainer()->compileLess($_GET["style_id"]);
            ilUtil::sendSuccess($this->lng->txt("less_file_updated"));
        }

        $form->setValuesByPost();
        $this->tpl->setContent($form->getHtml());

    }

    /**
     * @return html
     */
    public function renderLess(){
        $this->skin->readLessVariables();
        $this->initLessVariablesForm();
        $this->getLessValues();
    }

    /**
     * @return ilSystemStyleSkinContainer
     */
    public function getStyleContainer()
    {
        return $this->style_container;
    }

    /**
     * @param ilSystemStyleSkinContainer $style_container
     */
    public function setStyleContainer($style_container)
    {
        $this->style_container = $style_container;
    }

    /**
     * @return ilSystemStyleLessFile
     */
    public function getLessFile()
    {
        return $this->less_file;
    }

    /**
     * @param ilSystemStyleLessFile $less_file
     */
    public function setLessFile($less_file)
    {
        $this->less_file = $less_file;
    }



}