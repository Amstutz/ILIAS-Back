<?php
require_once(ILIAS_ABSOLUTE_PATH."/Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/KitchenSink/classes/Exceptions/class.ilKitchenSinkException.php");


/**
 * Class to display KitchenSinkCollection
 *
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$
 *
 */
class KitchenSinkTree
{

    /**
     * @var string
     */
    protected $root_entry_id = "";

    /**
     * @var KitchenSinkEntry[]
     */
    protected $id_to_entry_map = array();


    /**
     * @param KitchenSinkEntry $entry
     * @throws ilKitchenSinkException
     */
    public function addEntry(KitchenSinkEntry $entry){

        if($this->id_to_entry_map[$entry->getId()]){
            if($this->id_to_entry_map[$entry->getId()]->isDummy()){
                $entry->setChildrenIds($this->id_to_entry_map[$entry->getId()]->getChildrenIds());
                $entry->setMustBeUsedBy($this->id_to_entry_map[$entry->getId()]->getMustBeUsedBy());
                $entry->setMayBeUsedBy($this->id_to_entry_map[$entry->getId()]->getMayBeUsedBy());
                $this->id_to_entry_map[$entry->getId()] = $entry;
            }else{
                throw new ilKitchenSinkException(ilKitchenSinkException::DUPLICATE_ENTRY, $entry->getId());

            }
        }

        $this->id_to_entry_map[$entry->getId()] = $entry;

        if($entry->getId() == "root"){
            if($this->getRootEntryId() != "" && !$this->getRootEntry()->isDummy()){
                throw new ilKitchenSinkException(ilKitchenSinkException::DUPLICATE_ROOT_ENTRY, $entry->getId());
            }
            else($this->setRootEntryId($entry->getId()));
        }
        else {
            if(!$this->id_to_entry_map[$entry->getRelations()->isA]){
                $this->id_to_entry_map[$entry->getRelations()->isA] = new KitchenSinkEntry(false, $entry->getRelations()->isA);
            }

            $this->id_to_entry_map[$entry->getRelations()->isA]->addChildId($entry->getId());

        }

        if($entry->getRelations()->mustUse){
            foreach($entry->getRelations()->mustUse as $must_id){
                if(!$this->id_to_entry_map[$must_id]){
                    $this->id_to_entry_map[$must_id] = new KitchenSinkEntry(false, $must_id);
                }

                $this->id_to_entry_map[$must_id]->addMustBeUsedBy($entry->getId());
            }
        }

        if($entry->getRelations()->mayUse){
            foreach($entry->getRelations()->mayUse as $may_id){
                if(!$this->id_to_entry_map[$may_id]){
                    $this->id_to_entry_map[$may_id] = new KitchenSinkEntry(false, $may_id);
                }

                $this->id_to_entry_map[$may_id]->addMayBeUsedBy($entry->getId());
            }
        }
    }

    /**
     * @return string
     */
    public function getRootEntryId(){
        return $this->root_entry_id;
    }

    /**
     * @param $id
     */
    public function setRootEntryId($id)
    {
        $this->root_entry_id = $id;
    }

    /**
     * @return KitchenSinkEntry
     * @throws ilKitchenSinkException
     */
    public function getRootEntry(){
        return $this->getEntryById($this->getRootEntryId());
    }

    /**
     * @param string $id
     * @return KitchenSinkEntry
     * @throws ilKitchenSinkException
     */
    public function getEntryById($id = "")
    {
        if(array_key_exists ( $id, $this->id_to_entry_map )){
            return $this->id_to_entry_map[$id];
        }
        throw new ilKitchenSinkException(ilKitchenSinkException::INVALID_ID,$id);

    }

    /**
     * @param $id
     * @return array
     * @throws ilKitchenSinkException
     */
    public function getParentsOfEntry($id){
        $entry = $this->getEntryById($id);

        if($entry->getRelations()->isA=="root"){
            return array("root");
        }
        else{

            $parents = $this->getParentsOfEntry($entry->getRelations()->isA);
            $parents[] = $entry->getRelations()->isA;
            return $parents;
        }
    }


}
?>
