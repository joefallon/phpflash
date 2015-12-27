<?php
namespace JoeFallon\PhpFlash;

use JoeFallon\PhpSession\Session;

class FlashMessages
{
    const INFO_SESSION_KEY    = 'flash_information_messages';
    const SUCCESS_SESSION_KEY = 'flash_success_messages';
    const WARNING_SESSION_KEY = 'flash_warning_messages';
    const ERROR_SESSION_KEY   = 'flash_error_messages';

    /** @var  array */
    private $_infoMessages;
    /** @var  array */
    private $_warningMessages;
    /** @var  array */
    private $_successMessages;
    /** @var  array */
    private $_errorMessages;

    /**
     * Class Constructor
     */
    public function __construct()
    {
        $this->_infoMessages = [];
        $this->_warningMessages = [];
        $this->_successMessages = [];
        $this->_errorMessages = [];
    }

    /**
     * This function stores the informational flash message. If $storeInSession is set to true,
     * then the message is stored in the session. Otherwise, it is stored locally.
     *
     * @param string $message
     * @param bool   $storeInSession
     */
    public function storeInfoMessage($message, $storeInSession = true)
    {
        if($storeInSession == false)
        {
            $this->_infoMessages[] = $message;
        }
        else
        {
            $messageType = self::INFO_SESSION_KEY;
            $this->storeMessageInSession($message, $messageType);
        }
    }

    /**
     * @param $message
     * @param $messageType
     */
    private function storeMessageInSession($message, $messageType)
    {
        $session = new Session();
        $messages = $session->read($messageType);

        if(is_array($messages) == false)
        {
            $messages = [];
        }

        $messages[] = $message;
        $session->write($messageType, $messages);
    }

    /**
     * @return array Returns an array of the "info" messages.
     */
    public function retrieveInfoMessages()
    {
        $localMessages = $this->retrieveAndClearLocalInfoMessages();
        $sessionMessages = $this->retrieveAndClearSessionMessages(self::INFO_SESSION_KEY);
        $infoMessages = array_merge($localMessages, $sessionMessages);

        return $infoMessages;
    }

    /**
     * @return array
     */
    private function retrieveAndClearLocalInfoMessages()
    {
        $localMessages = $this->_infoMessages;
        $this->_infoMessages = [];

        return $localMessages;
    }

    /**
     * @param string $messageType
     *
     * @return array
     */
    private function retrieveAndClearSessionMessages($messageType)
    {
        $session = new Session();
        $sessionMessages = $session->read($messageType);
        $session->unsetSessionValue($messageType);

        if(is_array($sessionMessages) == false)
        {
            $sessionMessages = [];
        }

        return $sessionMessages;
    }

    /**
     * This function stores a success flash message. If $storeInSession is set to true, then
     * the message is stored in the session. Otherwise, it is stored locally.
     *
     * @param string $message
     * @param bool   $storeInSession
     */
    public function storeSuccessMessage($message, $storeInSession = true)
    {
        if($storeInSession == false)
        {
            $this->_successMessages[] = $message;
        }
        else
        {
            $messageType = self::SUCCESS_SESSION_KEY;
            $this->storeMessageInSession($message, $messageType);
        }
    }

    /**
     * @return array Returns an array of the "success" messages.
     */
    public function retrieveSuccessMessages()
    {
        $localMessages = $this->retrieveLocalSuccessMessages();
        $sessionMessages = $this->retrieveAndClearSessionMessages(self::SUCCESS_SESSION_KEY);
        $messages = array_merge($localMessages, $sessionMessages);

        return $messages;
    }

    /**
     * @return array
     */
    private function retrieveLocalSuccessMessages()
    {
        $localMessages = $this->_successMessages;
        $this->_successMessages = array();

        return $localMessages;
    }

    /**
     * This function stores the "warning" flash message. If $storeInSession is set to true, then
     * the message is stored in the session. Otherwise, it is stored locally.
     *
     * @param string $message
     * @param bool   $storeInSession
     */
    public function storeWarningMessage($message, $storeInSession = true)
    {
        if($storeInSession == false)
        {
            $this->_warningMessages[] = $message;
        }
        else
        {
            $this->storeMessageInSession($message, self::WARNING_SESSION_KEY);
        }
    }

    /**
     * @return array Returns an array of the "warning" messages.
     */
    public function retrieveWarningMessages()
    {
        $localMessages = $this->retrieveLocalWarningMessages();
        $sessionMessages = $this->retrieveAndClearSessionMessages(self::WARNING_SESSION_KEY);
        $messages = array_merge($localMessages, $sessionMessages);

        return $messages;
    }

    /**
     * @return array
     */
    private function retrieveLocalWarningMessages()
    {
        $localMessages = $this->_warningMessages;
        $this->_warningMessages = array();

        return $localMessages;
    }

    /**
     * This function stores the "error" flash message. If $storeInSession is set to true, then
     * the message is stored in the session. Otherwise, it is stored locally.
     *
     * @param string $message
     * @param bool   $storeInSession
     */
    public function storeErrorMessage($message, $storeInSession = true)
    {
        if($storeInSession == false)
        {
            $this->_errorMessages[] = $message;
        }
        else
        {
            $this->storeMessageInSession($message, self::ERROR_SESSION_KEY);
        }
    }

    /**
     * @return array Returns an array of all "error" messages.
     */
    public function retrieveErrorMessages()
    {
        $localMessages = $this->retrieveAndClearLocalErrorMessages();
        $sessionMessages = $this->retrieveAndClearSessionMessages(self::ERROR_SESSION_KEY);
        $messages = array_merge($localMessages, $sessionMessages);

        return $messages;
    }

    /**
     * @return array
     */
    private function retrieveAndClearLocalErrorMessages()
    {
        $localMessages = $this->_errorMessages;
        $this->_errorMessages = array();

        return $localMessages;
    }
}
