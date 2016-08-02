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
     * @var ilSystemStyleIconFolder
     */
    protected $icon_folder = null;

    /**
     * ilSystemStyleIconsGUI constructor.
     * @param string $skin_id
     * @param string $style_id
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
        $this->setIconFolder(new ilSystemStyleIconFolder($this->getStyleContainer()->getImagesSkinPath($style_id)));
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
            case "update":
            case "reset":
                $this->$cmd();
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

    /**
     * @return ilPropertyFormGUI
     */
    public function initIconsForm()
    {
        $form = new ilPropertyFormGUI();

        $form->setTitle("Adapt Icon Colors");

        foreach($this->getIconFolder()->getColorSet()->getColorsSorted() as $type => $colors){
            $section = new ilFormSectionHeaderGUI();

            if($type == ilSystemStyleIconColor::GREY){
                $title = $this->lng->txt("grey_color");
                $section->setTitle($this->lng->txt("grey_colors"));
                $section->setInfo($this->lng->txt("grey_colors_description"));
                $section->setSectionAnchor($this->lng->txt("grey_colors"));
            }
            if($type == ilSystemStyleIconColor::RED){
                $title = $this->lng->txt("red_color");
                $section->setTitle($this->lng->txt("red_colors"));
                $section->setInfo($this->lng->txt("red_colors_description"));
                $section->setSectionAnchor($this->lng->txt("red_colors"));
            }
            if($type == ilSystemStyleIconColor::GREEN){
                $title = $this->lng->txt("green_color");
                $section->setTitle($this->lng->txt("green_colors"));
                $section->setInfo($this->lng->txt("green_colors_description"));
                $section->setSectionAnchor($this->lng->txt("green_colors"));
            }
            if($type == ilSystemStyleIconColor::BLUE){
                $title = $this->lng->txt("blue_color");
                $section->setTitle($this->lng->txt("blue_colors"));
                $section->setInfo($this->lng->txt("blue_colors_description"));
                $section->setSectionAnchor($this->lng->txt("blue_colors"));
            }
            $form->addItem($section);

            foreach($colors as $id => $color){
                $input = new ilColorPickerInputGUI($title." ".($id+1),$color->getId());
                $input->setRequired(true);
                $input->setInfo("Usages: ".$this->getIconFolder()->getUsagesOfColorAsString($color->getId()));
                $form->addItem($input);
            }
        }

        $form->addCommandButton("reset", $this->lng->txt("reset_icons"));
        $form->addCommandButton("update", $this->lng->txt("update_colors"));

        $form->setFormAction($this->ctrl->getFormAction($this));

        return $form;
    }


    /**
     * @param ilPropertyFormGUI $form
     */
    function getIconsValues(ilPropertyFormGUI $form)
    {
        $values = [];
        $colors = $this->getIconFolder()->getColorSet()->getColors();
        foreach($colors as $color){
            $id = $color->getId();
            if($colors[$color->getId()]){
                $values[$id] = $colors[$color->getId()]->getColor();
            }else{
                $values[$id] = $color->getColor();
            }
        }

        $form->setValuesByArray($values);
    }

    public function reset()
    {
        $style = $this->getStyleContainer()->getSkin()->getStyle($_GET["style_id"]);
        $this->getStyleContainer()->resetImages($style);
        $this->setIconFolder(new ilSystemStyleIconFolder($this->getStyleContainer()->getImagesSkinPath($style->getId())));
        $this->edit();
    }

    public function update()
    {
        $form = $this->initIconsForm();
        if ($form->checkInput())
        {
            $message_stack = new ilSystemStyleMessageStack();

            $color_changes = [];
            foreach($this->getIconFolder()->getColorSet()->getColors() as $old_color){
                $new_color = $form->getInput($old_color->getId());
                if(!preg_match("/[\dabcdef]{6}/i",$new_color)){
                    $message_stack->addMessage(new ilSystemStyleMessage($this->lng->txt("invalid_color").$new_color,
                        ilSystemStyleMessage::TYPE_ERROR));
                }else if($new_color != $old_color->getId()){
                    $color_changes[$old_color->getId()] = $new_color;
                    $message_stack->addMessage(new ilSystemStyleMessage($this->lng->txt("color_changed").$old_color->getId(),
                        ilSystemStyleMessage::TYPE_SUCCESS));
                }
            }
            $this->getIconFolder()->changeIconColors($color_changes);
            $this->setIconFolder(new ilSystemStyleIconFolder($this->getStyleContainer()->getImagesSkinPath($_GET["style_id"])));
            $message_stack->sendMessages(false);
            $form = $this->initIconsForm();
        }
        $form->setValuesByPost();
        $this->tpl->setContent($form->getHTML().$this->renderIconsPreviews());
    }


    /**
     * @return string
     */
    protected function renderIconsPreviews(){
        global $DIC;

        $f = $DIC->ui()->factory();

        $cards = [];

        foreach($this->getIconFolder()->getIcons() as $id => $icon){
            $icon_image = $f->image()->standard($icon->getPath(),$icon->getName());
            $cards[] = $f->card(
                $icon->getName(),
                $icon_image
            )->withSections(array(
                $f->listing()->descriptive(array($this->lng->txt("used_colors")=>$icon->getColorSet()->asString()))
            ));
        }
        $deck = $f->deck($cards);

        return $DIC->ui()->renderer()->render($deck);
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