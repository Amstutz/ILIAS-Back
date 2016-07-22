<?php

/**
 * Used to stack messages to be shown to the user. Mostly used in ilUtil-Classes to present via ilUtil::sendMessage()
 *
 * @author            Timon Amstutz <timon.amstutz@ilub.unibe.ch>
 * @version           $Id$*
 */
class ilSystemStyleMessageStack{

    /**
     * @var ilSystemStyleMessage[]
     */
    protected $messages = array();

    public function prependMessage(ilSystemStyleMessage $message){
        array_unshift($this->messages , $message);
    }

    public function addMessage(ilSystemStyleMessage $message){
        $this->messages[] = $message;
    }

    public function sendMessages($keep = false){
        foreach($this->getJoinedMessages() as $type => $joined_message){
            switch($type){
                case ilSystemStyleMessage::TYPE_SUCCESS:
                    ilUtil::sendSuccess($joined_message,$keep);
                    break;
                case ilSystemStyleMessage::TYPE_INFO:
                    ilUtil::sendInfo($joined_message,$keep);
                    break;
                case ilSystemStyleMessage::TYPE_ERROR:
                    ilUtil::sendFailure($joined_message,$keep);
                    break;
            }
        }
    }

    public function getJoinedMessages(){
        $joined_messages = array();
        foreach($this->getMessages() as $message){
            if(!is_string($joined_messages[$message->getTypeId()])){
                $joined_messages[$message->getTypeId()] = "";
            }
            $joined_messages[$message->getTypeId()] .= $message->getMessageOutput();
        }
        return $joined_messages;
    }
    /**
     * @return ilSystemStyleMessage[]
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param ilSystemStyleMessage[] $messages
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;
    }

}