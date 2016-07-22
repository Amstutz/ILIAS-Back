<?php
include_once "Services/Form/classes/class.ilPropertyFormGUI.php";
include_once("Services/Style/System/classes/Utilities/class.ilSkinXML.php");
include_once("Services/Style/System/classes/Utilities/class.ilSystemStyleSkinContainer.php");
include_once("Services/Style/System/classes/class.ilStyleDefinition.php");
include_once("Services/Style/System/classes/class.ilSystemStyleSettings.php");
include_once("Services/Style/System/classes/Exceptions/class.ilSystemStyleException.php");
include_once("Services/Style/System/classes/Utilities/class.ilSystemStyleMessageStack.php");
include_once("Services/Style/System/classes/Utilities/class.ilSystemStyleMessage.php");


/**
 *
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$*
 */
class ilSystemStyleSettingsGUI
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
     * Constructor
     */
    function __construct()
    {
        global $DIC;

        $this->ctrl = $DIC->ctrl();
        $this->lng = $DIC->language();
        $this->tpl = $DIC["tpl"];
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
            default:
                $this->edit();
                break;
        }
    }

    protected function edit(){
        $form = $this->editSystemStyleForm();
        $this->getPropertiesValues($form);
        $this->tpl->setContent($form->getHTML());

    }

    /**
     * Get values for edit properties form
     */
    function getPropertiesValues($form)
    {
        global $DIC;

        if(!$_GET["skin_id"]){
            throw new ilSystemStyleException(ilSystemStyleException::NO_SKIN_ID);
        }
        if(!$_GET["style_id"]){
            throw new ilSystemStyleException(ilSystemStyleException::NO_STYLE_ID);
        }
        $skin = ilSkinXML::parseFromXML(ilStyleDefinition::CUSTOMIZING_SKINS_PATH.$_GET["skin_id"]."/template.xml");
        $style = $skin->getStyle($_GET["style_id"]);
        $values["skin_id"] = $skin->getId();
        $values["skin_name"] = $skin->getName();
        $values["style_id"] = $style->getId();
        $values["style_name"] = $style->getName();
        $values["image_dir"] = $style->getImageDirectory();
        $values["font_dir"] = $style->getFontDirectory();
        $values["sound_dir"] = $style->getSoundDirectory();

        $values["active"] = ilSystemStyleSettings::_lookupActivatedStyle($skin->getId(),$style->getId());
        $is_personal_style = $DIC->user()->getPref("skin")==$skin->getId() && $DIC->user()->getPref("style") == $style->getId();
        $values["personal"] =  $is_personal_style;
        $is_default_style = ilSystemStyleSettings::getCurrentDefaultSkin()==$skin->getId()  && ilSystemStyleSettings::getCurrentDefaultStyle() == $style->getId();
        $values["default"] = $is_default_style;



        $form->setValuesByArray($values);
    }

    protected function save(){
        global $DIC;


        $form = $this->editSystemStyleForm();

        if ($form->checkInput() )
        {

            try{
                $message_stack = new ilSystemStyleMessageStack();

                $new_skin = new ilSkinXML($_POST["skin_id"],$_POST["skin_name"]);
                $new_style = new ilSkinStyleXML(
                    $_POST["style_id"],
                    $_POST["style_name"],
                    $_POST["css_file"],
                    $_POST["image_dir"],
                    $_POST["font_dir"],
                    $_POST["sound_dir"]
                );
                $new_skin->addStyle($new_style);
                $container = new ilSystemStyleSkinContainer($new_skin, $message_stack);
                $old_skin = ilSkinXML::parseFromXML(ilStyleDefinition::CUSTOMIZING_SKINS_PATH.$new_skin->getId()."/template.xml");
                $container->update($old_skin,$_GET["style_id"]);


                if($_POST["active"] == 1){
                    ilSystemStyleSettings::_activateStyle($new_skin->getId(),$new_style->getId());
                    if($_POST["personal"]==1){
                        ilSystemStyleSettings::setCurrentUserPrefStyle($new_skin->getId(),$new_style->getId());
                    }
                    if($_POST["default"]==1){
                        ilSystemStyleSettings::setCurrentDefaultStyle($new_skin->getId(),$new_style->getId());
                    }
                }else{
                    ilSystemStyleSettings::_deactivateStyle($new_skin->getId(),$new_style->getId());
                    $_POST["personal"] = 0;
                    $_POST["default"] =0;
                }

                if(!$_POST["personal"] && $DIC->user()->getPref("skin") == $new_skin->getId()){
                    ilSystemStyleSettings::setCurrentUserPrefStyle(
                        ilStyleDefinition::DEFAULT_SKIN_ID,ilStyleDefinition::DEFAULT_STYLE_ID
                    );
                }
                if(!$_POST["default"] && ilSystemStyleSettings::getCurrentDefaultSkin() == $new_skin->getId()){
                    ilSystemStyleSettings::setCurrentDefaultStyle(
                        ilStyleDefinition::DEFAULT_SKIN_ID,ilStyleDefinition::DEFAULT_STYLE_ID
                    );
                }

                $message_stack->prependMessage(new ilSystemStyleMessage($this->lng->txt("msg_sys_style_update"),ilSystemStyleMessage::TYPE_SUCCESS));
                $message_stack->sendMessages(true);
                $this->ctrl->setParameterByClass('ilSystemStyleSettingsGUI','skin_id',$new_skin->getId());
                $this->ctrl->setParameterByClass('ilSystemStyleSettingsGUI','style_id',$new_style->getId());
                $this->ctrl->redirectByClass("ilSystemStyleSettingsGUI");
            }catch(ilSystemStyleException $e){
                ilUtil::sendFailure($e->getMessage(), true);
            }
        }

        $form->setValuesByPost();
        $this->tpl->setContent($form->getHTML());
    }

    protected function editSystemStyleForm(){
        $form = new ilPropertyFormGUI();

        //Todo bad here, redo
        $skin = ilSkinXML::parseFromXML(ilStyleDefinition::CUSTOMIZING_SKINS_PATH.$_GET["skin_id"]."/template.xml");
        $style = $skin->getStyle($_GET["style_id"]);


        if(!$style->isSubstyle()){
            $ti = new ilTextInputGUI($this->lng->txt("skin_id"), "skin_id");
            $ti->setMaxLength(128);
            $ti->setSize(40);
            $ti->setRequired(true);
            $form->addItem($ti);

            $ti = new ilTextInputGUI($this->lng->txt("skin_name"), "skin_name");
            $ti->setMaxLength(128);
            $ti->setSize(40);
            $ti->setRequired(true);
            $form->addItem($ti);
        }
        $form->setFormAction($this->ctrl->getFormActionByClass("ilsystemstylesettingsgui"));
        $form->setTitle($this->lng->txt("skin"));



        $section = new ilFormSectionHeaderGUI();
        $section->setTitle($this->lng->txt("style"));
        $form->addItem($section);

        $ti = new ilTextInputGUI($this->lng->txt("style_id"), "style_id");
        $ti->setMaxLength(128);
        $ti->setSize(40);
        $ti->setRequired(true);
        $form->addItem($ti);

        $ti = new ilTextInputGUI($this->lng->txt("style_name"), "style_name");
        $ti->setMaxLength(128);
        $ti->setSize(40);
        $ti->setRequired(true);
        $form->addItem($ti);

        $ti = new ilTextInputGUI($this->lng->txt("image_dir"), "image_dir");
        $ti->setMaxLength(128);
        $ti->setSize(40);
        $form->addItem($ti);

        $ti = new ilTextInputGUI($this->lng->txt("font_dir"), "font_dir");
        $ti->setMaxLength(128);
        $ti->setSize(40);
        $form->addItem($ti);

        $ti = new ilTextInputGUI($this->lng->txt("sound_dir"), "sound_dir");
        $ti->setMaxLength(128);
        $ti->setSize(40);
        $form->addItem($ti);

        $section = new ilFormSectionHeaderGUI();
        $section->setTitle($this->lng->txt("system_style_activation"));
        $form->addItem($section);

        $active = new ilCheckboxInputGUI($this->lng->txt("system_style_activation"), "active");

        $set_default = new ilCheckboxInputGUI($this->lng->txt("default"), "default");
        $active->addSubItem($set_default);

        $set_personal = new ilCheckboxInputGUI($this->lng->txt("personal"), "personal");
        $active->addSubItem($set_personal);


        $form->addItem($active);

        $form->addCommandButton("save", $this->lng->txt("save"));
        $form->addCommandButton("cancel", $this->lng->txt("cancel"));

        return $form;
    }
}