<?php
include_once "Services/Form/classes/class.ilPropertyFormGUI.php";
include_once("Services/Style/System/classes/Utilities/class.ilSystemStyleSkinContainer.php");
include_once("Services/Style/System/classes/Icons/class.ilSystemStyleIconColorSet.php");
include_once("Services/Style/System/classes/Icons/class.ilSystemStyleIconFolder.php");

/**
 *
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$*
 */
class ilSystemStyleIconsGUI
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
     * @var ilSystemStyleIconColorSet
     */
    protected $icon_color_set = null;

    /**
     * @var ilSystemStyleIconFolder
     */
    protected $icon_folder = null;

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

        $this->setStyleContainer(ilSystemStyleSkinContainer::generateFromId($skin_id));
        $this->setIconColorSet(new ilSystemStyleIconColorSet());
        $this->setIconFolder(new ilSystemStyleIconFolder(
            ilStyleDefinition::DEFAULT_IMAGES_PATH,
            $this->getStyleContainer()->getImagesSkinPath($style_id)
        ));
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
            case "updateIcons":
                $this->update();
                break;
            default:
                $this->edit();
                break;
        }
    }

    protected function edit(){
        $form = $this->initIconsForm();
        $this->getIconsValues($form);
        $this->tpl->setContent($form->getHTML().$this->renderIconsPreviews());
    }

    public function initIconsForm()
    {
        $form = new ilPropertyFormGUI();

        $form->setTitle("Adapt Icon Colors");

        foreach($this->getIconColorSet()->getDefaultColors() as $color){
            $input = new ilColorPickerInputGUI($color->getName(),$color->getId());
            $input->setRequired(true);
            $input->setInfo("Usages: ".$color->getUsagesAsString());
            $form->addItem($input);

        }

        $form->addCommandButton("resetIcons", "Reset Colors");
        $form->addCommandButton("updateIcons", "Update Colors");

        $form->setFormAction($this->ctrl->getFormAction($this));

        return $form;
    }


    function getIconsValues($form)
    {
        $values = array();
        $colors = $this->getIconColorSet()->getColors();
        foreach($this->getIconColorSet()->getDefaultColors() as $default_color){
            $id = $this->form_elements_prefix.$default_color->getId();
            if($colors[$default_color->getId()]){
                $values[$id] = $colors[$default_color->getId()]->getColor();
            }else{
                $values[$id] = $default_color->getColor();
            }
        }

        $form->setValuesByArray($values);
    }
    public function resetIcons()
    {
        $this->getSkin()->resetIcons();
        return $this->renderIcons();
    }

    public function update()
    {
        $form = $this->initIconsForm();
        if ($form->checkInput())
        {
            foreach($this->getIconColorSet()->getDefaultColors() as $color){
                $this->getIconColorSet()->addColor(new ilSystemStyleIconColor($color->getId(),$color->getName(),"#".$form->getInput($color->getId()),$color->getDescription()));
            }
            $this->getIconColorSet()->writeColorSetToFile($this->getStyleContainer()->getImagesSkinPath($_GET['style_id']));
            $this->getIconFolder()->changeIconColors($this->getIconColorSet());
        }

        $form->setValuesByPost();
        $this->tpl->setContent($form->getHtml().$this->renderIconsPreviews());
    }


    protected function renderIconsPreviews(){
        $icons_per_row = 6;
        $i=1;


        $html = "";
        $html .= "<row>";
        foreach($this->getIconFolder()->getIcons() as $icon){
            if(($i % $icons_per_row ) == 0){
                $html .= "</row>";
                $i = 0;
            }
            $i++;

            $path = $icon->getSkinPath();
            $name = $icon->getName();
            $content = $icon->getName();
            $thumbnail = "
                 <div class=\"col-sm-2 col-md-2\">
                    <div class=\"thumbnail\">
                      <img src=\"$path\" alt=\"...\">
                      <div class=\"caption\">
                        <h3>$name</h3>
                        <p>$content</p>
                      </div>
                    </div>
                  </div>";

            $html .= $thumbnail;
        }
        $html .= "</row>";

        return $html;
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
     * @return ilSystemStyleIconColorSet
     */
    public function getIconColorSet()
    {
        return $this->icon_color_set;
    }

    /**
     * @param ilSystemStyleIconColorSet $icon_color_set
     */
    public function setIconColorSet($icon_color_set)
    {
        $this->icon_color_set = $icon_color_set;
    }

    /**
     * @return ilSystemStyleIconFolder
     */
    public function getIconFolder()
    {
        return $this->icon_folder;
    }

    /**
     * @param ilSystemStyleIconFolder $icon_folder
     */
    public function setIconFolder($icon_folder)
    {
        $this->icon_folder = $icon_folder;
    }
}