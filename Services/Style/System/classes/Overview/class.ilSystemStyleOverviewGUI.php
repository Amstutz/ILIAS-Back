<?php
include_once "Services/Form/classes/class.ilPropertyFormGUI.php";
include_once("./Services/Style/System/classes/class.ilSystemStyleSettings.php");
include_once("./Services/Style/System/classes/Overview/class.ilSystemStyleDeleteGUI.php");
include_once("Services/Style/System/classes/Utilities/class.ilSystemStyleMessageStack.php");
include_once("Services/Style/System/classes/Utilities/class.ilSystemStyleSkinContainer.php");
include_once("Services/FileDelivery/classes/class.ilFileDelivery.php");

/**
 * @author            Alex Killing <alex.killing@gmx.de>
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$*
 */
class ilSystemStyleOverviewGUI
{
    /**
     * @var ilCtrl
     */
    protected $ctrl;

    /**
     * @var ilRbacSystem
     */
    protected $rbacsystem;

    /**
     * @var ilToolbarGUI
     */
    protected $toolbar;

    /**
     * @var ilLanguage
     */
    protected $lng;

    /**
     * @var ilTemplate
     */
    protected $tpl;

    /**
     * @var ILIAS\DI\Container
     */
    protected $DIC;

    /**
     * @var int
     */
    protected $ref_id;


    /**
     * Constructor
     */
    function __construct()
    {
        global $DIC;

        $this->ilias = $DIC["ilias"];
        $this->dic = $DIC;
        $this->ctrl = $DIC->ctrl();
        $this->rbacsystem = $DIC->rbac()->system();
        $this->toolbar = $DIC->toolbar();
        $this->lng = $DIC->language();
        $this->tpl = $DIC["tpl"];

        $this->ref_id = (int) $_GET["ref_id"];
    }

    /**
     * Execute command
     */
    function executeCommand()
    {
        $cmd = $this->ctrl->getCmd();

        switch ($cmd)
        {
            case "addSystemStyle":
            case "addSubStyle":
            case "saveNewSystemStyle":
            case "saveNewSubStyle":
            case "edit":
            case "copyStyle":
            case "deleteStyle":
            case "deleteStyles":
            case "moveUserStyles":
            case "saveStyleSettings":
            case "assignStylesToCats":
            case "addStyleCatAssignment":
            case "saveStyleCatAssignment":
            case "deleteSysStyleCatAssignments":
            case "confirmDelete":
            case "export":
            case "importStyle":
                $this->$cmd();
                break;
            default:
                $this->edit();
        }
    }

    /**
     * Check permission
     *
     * @param string $a_perm permission(s)
     * @return bool
     * @throws ilObjectException
     */
    function checkPermission($a_perm, $a_throw_exc = true)
    {
        if (!$this->rbacsystem->checkAccess($a_perm, $this->ref_id))
        {
            if ($a_throw_exc)
            {
                include_once "Services/Object/exceptions/class.ilObjectException.php";
                throw new ilObjectException($this->lng->txt("permission_denied"));
            }
            return false;
        }
        return true;
    }

    /**
     * Edit
     */
    function edit()
    {
        $this->checkPermission("visible,read");

        // default skin/style
        if ($this->checkPermission("sty_write_system", false))
        {

            // Add Button for adding skins
            $add_skin_btn = ilLinkButton::getInstance();
            $add_skin_btn->setCaption($this->lng->txt("add_system_stlye"),false);
            $add_skin_btn->setUrl($this->ctrl->getLinkTarget($this, 'addSystemStyle'));
            $this->toolbar->addButtonInstance($add_skin_btn);

            // Add Button for adding skins
            $add_substyle_btn = ilLinkButton::getInstance();
            $add_substyle_btn->setCaption($this->lng->txt("add_substyle"),false);
            $add_substyle_btn->setUrl($this->ctrl->getLinkTarget($this, 'addSubStyle'));
            $this->toolbar->addButtonInstance($add_substyle_btn);

            $this->toolbar->addSeparator();

            // from styles selector
            include_once("./Services/Form/classes/class.ilSelectInputGUI.php");
            $si = new ilSelectInputGUI($this->lng->txt("sty_move_user_styles").": ".$this->lng->txt("sty_from"), "from_style");

            $options = array();
            foreach(ilStyleDefinition::getAllSkinStyles() as $id => $skin_style)
            {
                $options[$id] = $skin_style['title'];
            }
            $si->setOptions($options + array("other" => $this->lng->txt("other")));

            $this->toolbar->addInputItem($si, true);

            // from styles selector
            $si = new ilSelectInputGUI($this->lng->txt("sty_to"), "to_style");
            $si->setOptions($options);
            $this->toolbar->addInputItem($si, true);
            $this->toolbar->addFormButton($this->lng->txt("sty_move_style"), "moveUserStyles");



            $this->toolbar->setFormAction($this->ctrl->getFormAction($this));
        }

        include_once("./Services/Style/System/classes/Overview/class.ilSystemStylesTableGUI.php");
        $tab = new ilSystemStylesTableGUI($this, "editSystemStyles");
        $this->tpl->setContent($tab->getHTML());

    }

    /**
     * Move user styles
     */
    function moveUserStyles()
    {
        $this->checkPermission("sty_write_system");

        $to = explode(":", $_POST["to_style"]);

        if ($_POST["from_style"] != "other")
        {
            $from = explode(":", $_POST["from_style"]);
            ilObjUser::_moveUsersToStyle($from[0],$from[1],$to[0],$to[1]);
        }
        else
        {
            // get all user assigned styles
            $all_user_styles = ilObjUser::_getAllUserAssignedStyles();

            include_once("./Services/Style/System/classes/class.ilStyleDefinition.php");

        }


        if ($_POST["from_style"] != "other")
        {
            $from = explode(":", $_POST["from_style"]);
            ilObjUser::_moveUsersToStyle($from[0],$from[1],$to[0],$to[1]);
        }
        else
        {
            // get all user assigned styles
            $all_user_styles = ilObjUser::_getAllUserAssignedStyles();

            include_once("./Services/Style/System/classes/class.ilStyleDefinition.php");

            // move users that are not assigned to
            // currently existing style
            foreach($all_user_styles as $style)
            {
                if (ilStyleDefinition::styleExists($style))
                {
                    $style_arr = explode(":", $style);
                    ilObjUser::_moveUsersToStyle($style_arr[0],$style_arr[1],$to[0],$to[1]);
                }
            }
        }

        ilUtil::sendSuccess($this->lng->txt("msg_obj_modified"), true);
        $this->ctrl->redirect($this , "edit");
    }


    /**
     * Save skin and style settings
     */
    function saveStyleSettings()
    {
        $this->checkPermission("sty_write_system");

        // check if one style is activated
        if (count($_POST["st_act"]) < 1)
        {
            ilUtil::sendFailure($this->lng->txt("at_least_one_style"), true);
            $this->ctrl->redirect($this, "edit");
        }

        //set default skin and style
        if ($_POST["default_skin_style"] != "")
        {
            $sknst = explode(":", $_POST["default_skin_style"]);
            ilSystemStyleSettings::setCurrentDefaultStyle($sknst[0],$sknst[1]);
        }

        // check if a style should be deactivated, that still has
        // a user assigned to
        $all_styles = ilStyleDefinition::getAllSkinStyles();
        foreach ($all_styles as $st)
        {
            if (!isset($_POST["st_act"][$st["id"]]))
            {
                if (ilObjUser::_getNumberOfUsersForStyle($st["template_id"], $st["style_id"]) > 1)
                {
                    ilUtil::sendFailure($this->lng->txt("cant_deactivate_if_users_assigned"), true);
                    $this->ctrl->redirect($this, "edit");
                }
                else
                {
                    ilSystemStyleSettings::_deactivateStyle($st["template_id"], $st["style_id"]);
                }
            }
            else
            {
                ilSystemStyleSettings::_activateStyle($st["template_id"], $st["style_id"]);
            }
        }

        ilUtil::sendSuccess($this->lng->txt("msg_obj_modified"), true);
        $this->ctrl->redirect($this , "edit");
    }

    /**
     * create
     */
    protected function addSystemStyle()
    {
        $this->addSystemStyleForms();
    }


    protected function saveNewSystemStyle(){
        $form = $this->createSystemStyleForm();

        if ($form->checkInput() )
        {
            include_once("Services/Style/System/classes/class.ilStyleDefinition.php");
            if(ilStyleDefinition::skinExists($_POST["skin_id"])){
                ilUtil::sendFailure($this->lng->txt("skin_id_exists"));
            }
            else{
                include_once("Services/Style/System/classes/Utilities/class.ilSkinXML.php");
                include_once("Services/Style/System/classes/Utilities/class.ilSystemStyleSkinContainer.php");
                try{
                    $skin = new ilSkinXML($_POST["skin_id"],$_POST["skin_name"]);
                    $style = new ilSkinStyleXML($_POST["style_id"],$_POST["style_name"]);
                    $skin->addStyle($style);
                    $container = new ilSystemStyleSkinContainer($skin);
                    $container->create();
                    $this->ctrl->setParameterByClass('ilSystemStyleSettingsGUI','skin_id',$skin->getId());
                    $this->ctrl->setParameterByClass('ilSystemStyleSettingsGUI','style_id',$style->getId());
                    ilUtil::sendSuccess($this->lng->txt("msg_sys_style_created"), true);
                    $this->ctrl->redirectByClass("ilSystemStyleSettingsGUI");
                }catch(ilSystemStyleException $e){
                    ilUtil::sendFailure($e->getMessage(), true);
                }
            }
        }

        // display only this form to correct input
        $form->setValuesByPost();
        $this->tpl->setContent($form->getHTML());
    }

    /**
     * create
     */
    protected function addSystemStyleForms()
    {
        global $ilHelp, $DIC;

        $DIC->tabs()->clearTargets();

        $forms = array();

        $ilHelp->setScreenIdComponent("sty");
        $ilHelp->setDefaultScreenId(ilHelpGUI::ID_PART_SCREEN, "system_style_create");

        $forms[] = $this->createSystemStyleForm();
        $forms[] = $this->importSystemStyleForm();
        $forms[] = $this->cloneSystemStyleForm();

        $this->tpl->setContent($this->getCreationFormsHTML($forms));
    }

    protected function createSystemStyleForm(){
        $form = new ilPropertyFormGUI();
        $form->setFormAction($this->ctrl->getFormAction($this));
        $form->setTitle($this->lng->txt("sty_create_new_system_style"));

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

        $form->addCommandButton("saveNewSystemStyle", $this->lng->txt("save"));
        $form->addCommandButton("cancel", $this->lng->txt("cancel"));

        return $form;
    }

    protected function importSystemStyleForm(){
        $form = new ilPropertyFormGUI();
        $form->setFormAction($this->ctrl->getFormAction($this));
        $form->setTitle($this->lng->txt("sty_import_system_style"));

        // title
        $file_input = new ilFileInputGUI($this->lng->txt("import_file"), "importfile");
        $file_input->setRequired(true);
        $file_input->setSuffixes(array("zip"));
        $form->addItem($file_input);

        $form->addCommandButton("importStyle", $this->lng->txt("import"));
        $form->addCommandButton("cancel", $this->lng->txt("cancel"));

        return $form;
    }

    protected function cloneSystemStyleForm(){
        $form = new ilPropertyFormGUI();
        $form->setFormAction($this->ctrl->getFormAction($this));
        $form->setTitle($this->lng->txt("sty_copy_other_system_style"));

        // source
        $ti = new ilSelectInputGUI($this->lng->txt("sty_source"), "source_style");
        $ti->setRequired(true);
        $styles = ilStyleDefinition::getAllSkinStyles();
        $options = array();
        foreach($styles as $id => $style){
            if($style["skin_id"]!=ilStyleDefinition::DEFAULT_SKIN_ID){
                $options[$id] = $style['title'];
            }
        }
        $ti->setOptions($options);

        $form->addItem($ti);

        $form->addCommandButton("copyStyle", $this->lng->txt("copy"));
        $form->addCommandButton("cancel", $this->lng->txt("cancel"));

        return $form;
    }

    /**
     * @param array $a_forms
     * @return string
     */
    protected function getCreationFormsHTML(array $a_forms)
    {
        include_once("./Services/Accordion/classes/class.ilAccordionGUI.php");

        $acc = new ilAccordionGUI();
        $acc->setBehaviour(ilAccordionGUI::FIRST_OPEN);
        $cnt = 1;
        foreach ($a_forms as $form_type => $cf)
        {
            $htpl = new ilTemplate("tpl.creation_acc_head.html", true, true, "Services/Object");

            // using custom form titles (used for repository plugins)
            $form_title = "";
            if(method_exists($this, "getCreationFormTitle"))
            {
                $form_title = $this->getCreationFormTitle($form_type);
            }
            if(!$form_title)
            {
                $form_title = $cf->getTitle();
            }

            // move title from form to accordion
            $htpl->setVariable("TITLE", $this->lng->txt("option")." ".$cnt.": ".
                $form_title);
            $cf->setTitle(null);
            $cf->setTitleIcon(null);
            $cf->setTableWidth("100%");

            $acc->addItem($htpl->get(), $cf->getHTML());

            $cnt++;
        }

        return "<div class='ilCreationFormSection'>".$acc->getHTML()."</div>";
    }

    protected function copyStyle(){
        $message_stack = new ilSystemStyleMessageStack();

        $imploded_skin_style_id = explode(":", $_POST['source_style']);
        $skin_id = $imploded_skin_style_id[0];
        $style_id = $imploded_skin_style_id[1];

        try{
            $container = ilSystemStyleSkinContainer::generateFromId($skin_id,$message_stack);
            $new_container = $container->copy();
            $message_stack->prependMessage(new ilSystemStyleMessage($this->lng->txt("style_copied"),ilSystemStyleMessage::TYPE_SUCCESS));
        }catch(Exception $e){
            $message_stack->addMessage(new ilSystemStyleMessage($e->getMessage(),ilSystemStyleMessage::TYPE_ERROR));
        }

        $this->ctrl->setParameterByClass('ilSystemStyleSettingsGUI','skin_id',$new_container->getSkin()->getId());
        $this->ctrl->setParameterByClass('ilSystemStyleSettingsGUI','style_id',$new_container->getSkin()->getStyle($style_id)->getId());
        $message_stack->sendMessages(true);
        $this->ctrl->redirectByClass("ilSystemStyleSettingsGUI");
    }

    protected function deleteStyle(){
        $skin_id = $_GET["skin_id"];
        $style_id = $_GET["style_id"];

        $delete_form_table = new ilSystemStyleDeleteGUI();
        $container = ilSystemStyleSkinContainer::generateFromId($skin_id);
        $delete_form_table->addStyle($container->getSkin(),$container->getSkin()->getStyle($style_id));
        $this->tpl->setContent($delete_form_table->getDeleteStyleFormHTML());
    }
    protected function deleteStyles(){
        $delete_form_table = new ilSystemStyleDeleteGUI();

        foreach($_POST['id'] as $skin_style_id){
            $imploded_skin_style_id = explode(":", $skin_style_id);
            $skin_id = $imploded_skin_style_id[0];
            $style_id = $imploded_skin_style_id[1];
            $container = ilSystemStyleSkinContainer::generateFromId($skin_id);
            $delete_form_table->addStyle($container->getSkin(),$container->getSkin()->getStyle($style_id));
        }
        $this->tpl->setContent($delete_form_table->getDeleteStyleFormHTML());

    }
    protected function confirmDelete(){
        $message_stack = new ilSystemStyleMessageStack();

        foreach($_POST as $key => $skin_style_id){
            if(is_string($skin_style_id) && strpos($key, 'style') !== false){
                try{
                    $imploded_skin_style_id = explode(":", $skin_style_id);
                    $container = ilSystemStyleSkinContainer::generateFromId($imploded_skin_style_id[0],$message_stack);
                    $syle = $container->getSkin()->getStyle($imploded_skin_style_id[1]);
                    $container->deleteStyle($syle);
                    if(!$container->getSkin()->hasStyles()){
                        $container->delete();
                    }
                }catch(Exception $e){
                    $message_stack->addMessage(new ilSystemStyleMessage($e->getMessage(),ilSystemStyleMessage::TYPE_ERROR));
                }

            }
        }
        $message_stack->sendMessages(true);
        $this->ctrl->redirect($this);
    }
    protected function importStyle(){
        $form = $this->importSystemStyleForm();

        if ($form->checkInput() )
        {
            $message_stack = new ilSystemStyleMessageStack();
            $imported_container = ilSystemStyleSkinContainer::import($_POST['importfile']['tmp_name'],$_POST['importfile']['name'],$message_stack);
            $this->ctrl->setParameterByClass('ilSystemStyleSettingsGUI','skin_id',$imported_container->getSkin()->getId());
            $this->ctrl->setParameterByClass('ilSystemStyleSettingsGUI','style_id',$imported_container->getSkin()->getDefaultStyle()->getId());
            $message_stack->addMessage(new ilSystemStyleMessage($this->lng->txt("style_imported".$imported_container->getCustomizingSkinDirectory()),ilSystemStyleMessage::TYPE_SUCCESS));

            $message_stack->sendMessages(true);
            $this->ctrl->redirectByClass("ilSystemStyleSettingsGUI");
        }

        // display only this form to correct input
        $form->setValuesByPost();
        $this->tpl->setContent($form->getHTML());
    }
    protected function export(){
        $skin_id = $_GET["skin_id"];
        $container = ilSystemStyleSkinContainer::generateFromId($skin_id);
        $container->export();
    }


    /**
     * create
     */
    protected function addSubStyle()
    {
        global $ilHelp, $DIC;

        $DIC->tabs()->clearTargets();
        $ilHelp->setScreenIdComponent("sty");
        $ilHelp->setDefaultScreenId(ilHelpGUI::ID_PART_SCREEN, "system_style_create");

        $form = $this->addSubStyleForms();

        $this->tpl->setContent($form->getHTML());

    }

    /**
     * @return ilPropertyFormGUI
     */
    protected function addSubStyleForms( )
    {
        $form = new ilPropertyFormGUI();
        $form->setFormAction($this->ctrl->getFormAction($this));
        $form->setTitle($this->lng->txt("sty_create_new_system_style"));


        $ti = new ilTextInputGUI($this->lng->txt("sub_style_id"), "sub_style_id");
        $ti->setMaxLength(128);
        $ti->setSize(40);
        $ti->setRequired(true);
        $form->addItem($ti);

        $ti = new ilTextInputGUI($this->lng->txt("sub_style_name"), "sub_style_name");
        $ti->setMaxLength(128);
        $ti->setSize(40);
        $ti->setRequired(true);
        $form->addItem($ti);

        // source
        $ti = new ilSelectInputGUI($this->lng->txt("parent"), "parent_style");
        $ti->setRequired(true);
        $styles = ilStyleDefinition::getAllSkinStyles();
        $options = array();
        foreach($styles as $id => $style){
            if($style["skin_id"]!=ilStyleDefinition::DEFAULT_SKIN_ID && !$style["parents"]){
                $options[$id] = $style['title'];
            }
        }
        $ti->setOptions($options);

        $form->addItem($ti);
        $form->addCommandButton("saveNewSubStyle", $this->lng->txt("save"));
        $form->addCommandButton("cancel", $this->lng->txt("cancel"));

        return $form;
    }

    protected function saveNewSubStyle(){
        $form = $this->addSubStyleForms();

        if ($form->checkInput() )
        {
            include_once("Services/Style/System/classes/class.ilStyleDefinition.php");
            if(false){
                //Todo do check if style_id exists
            }
            else{
                include_once("Services/Style/System/classes/Utilities/class.ilSkinXML.php");
                include_once("Services/Style/System/classes/Utilities/class.ilSystemStyleSkinContainer.php");
                try{
                    $imploded_parent_skin_style_id = explode(":", $_POST['parent_style']);
                    $parent_skin_id = $imploded_parent_skin_style_id[0];
                    $parent_style_id = $imploded_parent_skin_style_id[1];

                    $container = ilSystemStyleSkinContainer::generateFromId($parent_skin_id);

                    $sub_style_id = $_POST['sub_style_id'];

                    $style = new ilSkinStyleXML($_POST['sub_style_id'], $_POST['sub_style_name']);
                    $style->setSubstyleOf($parent_style_id);
                    $container->addStyle($style);

                    $this->ctrl->setParameterByClass('ilSystemStyleSettingsGUI','skin_id',$parent_skin_id);
                    $this->ctrl->setParameterByClass('ilSystemStyleSettingsGUI','style_id',$sub_style_id);
                    ilUtil::sendSuccess($this->lng->txt("msg_sub_style_created"), true);
                    $this->ctrl->redirectByClass("ilSystemStyleSettingsGUI");
                }catch(ilSystemStyleException $e){
                    ilUtil::sendFailure($e->getMessage(), true);
                }
            }
        }

        // display only this form to correct input
        $form->setValuesByPost();
        $this->tpl->setContent($form->getHTML());
    }
}