<?php
require_once(ILIAS_ABSOLUTE_PATH."/Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/classes/Exceptions/class.ilKitchenSinkException.php");

/**
 * Class to display Kitchen Sink Entries
 *
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$
 *
 */
class KitchenSinkEntryDescription
{
    /**
     * @var string
     */
    protected $purpose = "";

    /**
     * @var string
     */
    protected $composition = "";

    /**
     * @var string
     */
    protected $effect = "";

    /**
     * @var array
     */
    protected $rival_elements = array();

    /**
     * KitchenSinkEntry constructor.
     * @param bool|false $json
     * @param string $dummyId
     */
    public function __construct($json = false) {
        if($json) {
            $this->setPurpose($json->purpose);
            $this->setComposition($json->composition);
            $this->setEffect($json->effect);
            $this->setRivalElements($json->rivals);
        }
    }

    /**
     * @return string
     */
    public function getPurpose()
    {
        return $this->purpose;
    }

    /**
     * @param string $purpose
     */
    public function setPurpose($purpose)
    {
        $this->purpose = $purpose;
    }

    /**
     * @return string
     */
    public function getComposition()
    {
        return $this->composition;
    }

    /**
     * @param string $composition
     */
    public function setComposition($composition)
    {
        $this->composition = $composition;
    }

    /**
     * @return string
     */
    public function getEffect()
    {
        return $this->effect;
    }

    /**
     * @param string $effect
     */
    public function setEffect($effect)
    {
        $this->effect = $effect;
    }

    /**
     * @return array
     */
    public function getRivalElements()
    {
        return $this->rival_elements;
    }

    /**
     * @param array $rival_elements
     */
    public function setRivalElements($rival_elements)
    {
        if(is_array($rival_elements)) {
            foreach ($rival_elements as $element) {
                $this->rival_elements[$element->name] = $element->name.": ".$element->description;
            }
        }
    }
}
?>