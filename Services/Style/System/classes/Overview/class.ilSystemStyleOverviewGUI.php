<?php
include_once "Services/Form/classes/class.ilPropertyFormGUI.php";

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
     * @var ilTree
     */
    protected $tree;

    /**
     * Constructor
     */
    function __construct()
    {
        global $DIC, $ilIliasIniFile;

        $this->ilias = $DIC["ilias"];
        $this->dic = $DIC;
        $this->ctrl = $DIC->ctrl();
        $this->rbacsystem = $DIC->rbac()->system();
        $this->toolbar = $DIC->toolbar();
        $this->lng = $DIC->language();
        $this->tpl = $DIC["tpl"];
        $this->tree = $DIC["tree"];

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
            case "saveNewSystemStyle":
            case "edit":
            case "moveUserStyles":
            case "saveStyleSettings":
            case "assignStylesToCats":
            case "addStyleCatAssignment":
            case "saveStyleCatAssignment":
            case "deleteSysStyleCatAssignments":
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



            // Add Button for adding System Styles

            $reload_btn = ilLinkButton::getInstance();
            $reload_btn->setCaption($this->lng->txt("add_system_stlye"),false);
            $reload_btn->setUrl($this->ctrl->getLinkTarget($this, 'addSystemStyle'));
            $this->toolbar->addButtonInstance($reload_btn);

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
                if (ilStyleDefinition::doesStyleExist($style))
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

            if ($this->ilias->ini->readVariable("layout","style") != $sknst[1] ||
                $this->ilias->ini->readVariable("layout","skin") != $sknst[0])
            {
                $this->ilias->ini->setVariable("layout","skin", $sknst[0]);
                $this->ilias->ini->setVariable("layout","style",$sknst[1]);
            }

            $this->ilias->ini->write();
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
                    include_once("./Services/Style/System/classes/class.ilSystemStyleSettings.php");
                    ilSystemStyleSettings::_deactivateStyle($st["template_id"], $st["style_id"]);
                }
            }
            else
            {
                include_once("./Services/Style/System/classes/class.ilSystemStyleSettings.php");
                ilSystemStyleSettings::_activateStyle($st["template_id"], $st["style_id"]);
            }
        }

        ilUtil::sendSuccess($this->lng->txt("msg_obj_modified"), true);
        $this->ctrl->redirect($this , "edit");
    }

    ////
    //// Substyles
    ////

    /**
     * Assign styles to categories
     *
     * @param
     * @return
     */
    function assignStylesToCats()
    {
        $this->ctrl->setParameter($this, "style_id", urlencode($_GET["style_id"]));

        $this->checkPermission("sty_write_system");

        $all_styles = ilStyleDefinition::getAllSkinStyles();
        $sel_style = $all_styles[$_GET["style_id"]];

        $options = array();
        if (is_array($sel_style["substyle"]))
        {
            foreach ($sel_style["substyle"] as $subst)
            {
                $options[$subst["id"]] = $subst["name"];
            }
        }

        // substyle
        include_once("./Services/Form/classes/class.ilSelectInputGUI.php");
        $si = new ilSelectInputGUI($this->lng->txt("sty_substyle"), "substyle");
        $si->setOptions($options);
        $this->toolbar->addInputItem($si, true);

        $this->toolbar->addFormButton($this->lng->txt("sty_add_assignment"), "addStyleCatAssignment");
        $this->toolbar->setFormAction($this->ctrl->getFormAction($this));

        include_once("./Services/Style/System/classes/Overview/class.ilSysStyleCatAssignmentTableGUI.php");
        $tab = new ilSysStyleCatAssignmentTableGUI($this, "assignStylesToCats");

        $this->tpl->setContent($tab->getHTML());
    }


    /**
     * Add style category assignment
     *
     * @param
     * @return
     */
    function addStyleCatAssignment()
    {
        $this->checkPermission("sty_write_system");

        $this->ctrl->setParameter($this, "style_id", urlencode($_GET["style_id"]));
        $this->ctrl->setParameter($this, "substyle", urlencode($_REQUEST["substyle"]));

        include_once 'Services/Search/classes/class.ilSearchRootSelector.php';
        $exp = new ilSearchRootSelector(
            $this->ctrl->getLinkTarget($this,'addStyleCatAssignment'));
        $exp->setExpand($_GET["search_root_expand"] ? $_GET["search_root_expand"] : $this->tree->readRootId());
        $exp->setExpandTarget($this->ctrl->getLinkTarget($this,'addStyleCatAssignment'));
        $exp->setTargetClass(get_class($this));
        $exp->setCmd('saveStyleCatAssignment');
        $exp->setClickableTypes(array("cat"));

        // build html-output
        $exp->setOutput(0);
        $this->tpl->setContent($exp->getOutput());
    }


    /**
     * Save style category assignment
     *
     * @param
     * @return
     */
    function saveStyleCatAssignment()
    {
        $this->checkPermission("sty_write_system");

        $this->ctrl->setParameter($this, "style_id", urlencode($_GET["style_id"]));

        $style_arr = explode(":", $_GET["style_id"]);
        ilStyleDefinition::writeSystemStyleCategoryAssignment($style_arr[0], $style_arr[1],
            $_GET["substyle"], $_GET["root_id"]);
        ilUtil::sendSuccess($this->lng->txt("msg_obj_modified"), true);

        $this->ctrl->redirect($this, "assignStylesToCats");
    }

    /**
     * Delete system style to category assignments
     */
    function deleteSysStyleCatAssignments()
    {
        $this->checkPermission("sty_write_system");

        $this->ctrl->setParameter($this, "style_id", urlencode($_GET["style_id"]));
        $style_arr = explode(":", $_GET["style_id"]);
        if (is_array($_POST["id"]))
        {
            foreach ($_POST["id"] as $id)
            {
                $id_arr = explode(":", $id);
                ilStyleDefinition::deleteSystemStyleCategoryAssignment($style_arr[0], $style_arr[1],
                    $id_arr[0], $id_arr[1]);
            }
            ilUtil::sendSuccess($this->lng->txt("msg_obj_modified"), true);
        }

        $this->ctrl->redirect($this, "assignStylesToCats");
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
            if(ilStyleDefinition::doesSkinExist($_POST["skin_id"])){
                ilUtil::sendFailure($this->lng->txt("skin_id_exists"));
            }else{
                include_once("Services/Style/System/classes/Utilities/class.ilSkinXML.php");
                include_once("Services/Style/System/classes/Utilities/class.ilSystemStyleSkinContainer.php");
                $skin = new ilSkinXML($_POST["skin_id"],$_POST["skin_name"]);
                $skin->addStyle(new ilSkinStyleXML($_POST["style_id"],$_POST["style_name"]));
                $container = new ilSystemStyleSkinContainer($skin);
                $container->create();
                $this->ctrl->setParameterByClass('ilSystemStyleSettingsGUI','skin_id',$_POST["skin_id"]);
                $this->ctrl->setParameterByClass('ilSystemStyleSettingsGUI','style_id',$_POST["style_id"]);
                ilUtil::sendSuccess($this->lng->txt("msg_sys_style_created"), true);
                $this->ctrl->redirectByClass("ilSystemStyleSettingsGUI");
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
        $ti = new ilFileInputGUI($this->lng->txt("import_file"), "importfile");
        $ti->setRequired(true);
        $form->addItem($ti);

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
        //$ti->setOptions();
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

}