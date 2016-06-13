<?php
/**
 *
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$*
 */
class ilSystemStyleGUI
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


    public function __construct(ilObjStyleSettingsGUI  $parent,$parent_object) {
        global $ilCtrl, $tpl;

        $this->ctrl = $ilCtrl;
        $this->tpl = $tpl;
        $this->setParent($parent);

        $this->editSystemStylesObject($parent_object);

    }
    /**
     * edit system styles
     */
    function editSystemStylesObject($parent_object)
    {
        global $rbacsystem, $ilias, $styleDefinition, $ilToolbar, $ilCtrl, $lng, $tpl;


        if (!$rbacsystem->checkAccess("visible,read",$parent_object->getRefId()))
        {
            $this->ilias->raiseError($this->lng->txt("permission_denied"),$this->ilias->error_obj->MESSAGE);
        }

        // toolbar

        // default skin/style
        if ($rbacsystem->checkAccess("write",$parent_object->getRefId()))
        {
            include_once("./Services/Form/classes/class.ilSelectInputGUI.php");

            $options = array();
            foreach (ilStyleDefinition::getAllSkinStyles() as $st)
            {
                $options[$st["id"]] = $st["title"];
            }

            // from styles selector
            $si = new ilSelectInputGUI($lng->txt("sty_move_user_styles").": ".$lng->txt("sty_from"), "from_style");
            $si->setOptions($options + array("other" => $lng->txt("other")));
            $ilToolbar->addInputItem($si, true);

            // from styles selector
            $si = new ilSelectInputGUI($lng->txt("sty_to"), "to_style");
            $si->setOptions($options);
            $ilToolbar->addInputItem($si, true);
            $ilToolbar->addFormButton($lng->txt("sty_move_style"), "moveUserStyles");

            $ilToolbar->setFormAction($ilCtrl->getFormAction($this->getParent()));
        }

        include_once("./Services/Style/System/classes/class.ilSystemStylesTableGUI.php");
        $tab = new ilSystemStylesTableGUI($this->getParent(), "editSystemStyles");
        $tpl->setContent($tab->getHTML());

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
