<?php
/* Copyright (c) 2016 Tomasz Kolonko <thomas.kolonko@ilub.unibe.ch> Extended GPL, see docs/LICENSE */

include_once("Services/Style/System/classes/Utilities/class.ilSystemStyleMessage.php");
include_once("Services/Style/System/classes/Utilities/class.ilSystemStyleMessageStack.php");

/**
 *
 * @author            Tomasz Kolonko <thomas.kolonko@ilub.unibe.ch>
 * @version           $Id$*
 */
class SystemStyleMessageStackTest extends PHPUnit_Framework_TestCase {

    /*
     *
    const TYPE_INFO = 0;
    const TYPE_SUCCESS = 1;
    const TYPE_ERROR = 2;
     */

    /**
     * @var ilSystemStyleMessage
     */
    protected $ilSystemStyleMessage;

    /**
     * @var messageStringOne
     */
    protected $messageStringOne = "This is a message";

    /**
     * @var messageStringTwo
     */
    protected $messageStringTwo = "Godzilla has taken over the world.";

    /**
     * @var messageStringThree
     */
    protected $messageStringThree = "A small, cute cat destroyed Godzilla.";

    /**
     * @var ilSystemStyleMessage[]
     */
    protected $messages = array();

    /**
     * @var ilSystemStyleMessageStack
     */
    protected $ilSystemStyleMessageStack;

    public function testPrependMessage() {
        $this->ilSystemStyleMessage = new ilSystemStyleMessage($this->messageStringOne, ilSystemStyleMessage::TYPE_INFO);
        $this->ilSystemStyleMessageStack = new ilSystemStyleMessageStack();

        $this->ilSystemStyleMessageStack->prependMessage($this->ilSystemStyleMessage);

        $this->ilSystemStyleMessage = new ilSystemStyleMessage($this->messageStringTwo, ilSystemStyleMessage::TYPE_SUCCESS);
        $this->ilSystemStyleMessageStack->prependMessage($this->ilSystemStyleMessage);

        $this->ilSystemStyleMessage = new ilSystemStyleMessage($this->messageStringThree, ilSystemStyleMessage::TYPE_ERROR);
        $this->ilSystemStyleMessageStack->prependMessage($this->ilSystemStyleMessage);
        $this->messages = $this->ilSystemStyleMessageStack->getMessages();

        $this->assertTrue($this->messages[0]->getMessage() === $this->messageStringThree);
        $this->assertTrue($this->messages[0]->getTypeId() === ilSystemStyleMessage::TYPE_ERROR);

        $this->assertTrue($this->messages[1]->getMessage() === $this->messageStringTwo);
        $this->assertTrue($this->messages[1]->getTypeId() === ilSystemStyleMessage::TYPE_SUCCESS);

        $this->assertTrue($this->messages[2]->getMessage() === $this->messageStringOne);
        $this->assertTrue($this->messages[2]->getTypeId() === ilSystemStyleMessage::TYPE_INFO);
    }

    public function testAddMessage() {
        $this->ilSystemStyleMessage = new ilSystemStyleMessage($this->messageStringOne, ilSystemStyleMessage::TYPE_INFO);
        $this->ilSystemStyleMessageStack = new ilSystemStyleMessageStack();

        $this->ilSystemStyleMessageStack->addMessage($this->ilSystemStyleMessage);

        $this->ilSystemStyleMessage = new ilSystemStyleMessage($this->messageStringTwo, ilSystemStyleMessage::TYPE_SUCCESS);
        $this->ilSystemStyleMessageStack->addMessage($this->ilSystemStyleMessage);

        $this->ilSystemStyleMessage = new ilSystemStyleMessage($this->messageStringThree, ilSystemStyleMessage::TYPE_ERROR);
        $this->ilSystemStyleMessageStack->addMessage($this->ilSystemStyleMessage);
        $this->messages = $this->ilSystemStyleMessageStack->getMessages();

        $this->assertTrue($this->messages[2]->getMessage() === $this->messageStringThree);
        $this->assertTrue($this->messages[2]->getTypeId() === ilSystemStyleMessage::TYPE_ERROR);

        $this->assertTrue($this->messages[1]->getMessage() === $this->messageStringTwo);
        $this->assertTrue($this->messages[1]->getTypeId() === ilSystemStyleMessage::TYPE_SUCCESS);

        $this->assertTrue($this->messages[0]->getMessage() === $this->messageStringOne);
        $this->assertTrue($this->messages[0]->getTypeId() === ilSystemStyleMessage::TYPE_INFO);
    }

    public function testJoinedMessages() {
        $this->ilSystemStyleMessage = new ilSystemStyleMessage($this->messageStringOne, ilSystemStyleMessage::TYPE_INFO);
        $this->ilSystemStyleMessageStack = new ilSystemStyleMessageStack();

        $this->ilSystemStyleMessageStack->addMessage($this->ilSystemStyleMessage);

        $this->ilSystemStyleMessage = new ilSystemStyleMessage($this->messageStringTwo, ilSystemStyleMessage::TYPE_SUCCESS);
        $this->ilSystemStyleMessageStack->addMessage($this->ilSystemStyleMessage);

        $this->ilSystemStyleMessage = new ilSystemStyleMessage("Another SUCCESS message", ilSystemStyleMessage::TYPE_SUCCESS);
        $this->ilSystemStyleMessageStack->addMessage($this->ilSystemStyleMessage);

        $this->ilSystemStyleMessage = new ilSystemStyleMessage($this->messageStringThree, ilSystemStyleMessage::TYPE_ERROR);
        $this->ilSystemStyleMessageStack->addMessage($this->ilSystemStyleMessage);

        $this->ilSystemStyleMessage = new ilSystemStyleMessage("Another ERROR message", ilSystemStyleMessage::TYPE_ERROR);
        $this->ilSystemStyleMessageStack->addMessage($this->ilSystemStyleMessage);

        $this->ilSystemStyleMessage = new ilSystemStyleMessage("YET another ERROR message", ilSystemStyleMessage::TYPE_ERROR);
        $this->ilSystemStyleMessageStack->addMessage($this->ilSystemStyleMessage);

        $this->assertTrue(count($this->ilSystemStyleMessageStack->getJoinedMessages()) === 3);
        $this->assertTrue($this->ilSystemStyleMessageStack->getJoinedMessages()[0] === $this->messageStringOne . "</br>");
        $this->assertTrue($this->ilSystemStyleMessageStack->getJoinedMessages()[1] === $this->messageStringTwo .
            "</br>" . "Another SUCCESS message" . "</br>");
        $this->assertTrue($this->ilSystemStyleMessageStack->getJoinedMessages()[2] === $this->messageStringThree .
            "</br>" . "Another ERROR message" . "</br>" . "YET another ERROR message" . "</br>");
    }


}