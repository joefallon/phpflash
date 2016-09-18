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
        $this->_infoMessages    = array();
        $this->_warningMessages = array();
        $this->_successMessages = array();
        $this->_errorMessages   = array();
    }

    /**
     * Load and clear all flash messages in the session and store them in memory.
     */
    public function loadMessagesFromSession()
    {
        $sessionInfoMessages    = $this->retrieveAndClearSessionMessages(self::INFO_SESSION_KEY);
        $sessionSuccessMessages = $this->retrieveAndClearSessionMessages(self::SUCCESS_SESSION_KEY);
        $sessionWarningMessages = $this->retrieveAndClearSessionMessages(self::WARNING_SESSION_KEY);
        $sessionErrorMessages   = $this->retrieveAndClearSessionMessages(self::ERROR_SESSION_KEY);

        $this->_infoMessages    = array_merge($sessionInfoMessages,    $this->_infoMessages);
        $this->_successMessages = array_merge($sessionSuccessMessages, $this->_successMessages);
        $this->_warningMessages = array_merge($sessionWarningMessages, $this->_warningMessages);
        $this->_errorMessages   = array_merge($sessionErrorMessages,   $this->_errorMessages);
    }

    /**
     * This function stores the "info" flash message. If $storeInSession is set to true,
     * then the message is stored in the session. Otherwise, it is stored locally.
     *
     * @param string $message
     * @param bool   $storeInSession
     */
    public function storeInfoMessage($message, $storeInSession = true)
    {
        if($storeInSession)
        {
            $this->storeMessageInSession($message, self::INFO_SESSION_KEY);
        }
        else
        {
            $this->_infoMessages[] = $message;
        }
    }

    /**
     * This function returns the array of "info" messages. To get the messages that are stored
     * in the session as well, call #loadMessagesFromSession first.
     *
     * @return array
     */
    public function retrieveInfoMessages()
    {
        return $this->_infoMessages;
    }

    /**
     * This function stores the "success" flash message. If $storeInSession is set to true,
     * then the message is stored in the session. Otherwise, it is stored locally.
     *
     * @param string $message
     * @param bool   $storeInSession
     */
    public function storeSuccessMessage($message, $storeInSession = true)
    {
        if($storeInSession)
        {
            $this->storeMessageInSession($message, self::SUCCESS_SESSION_KEY);
        }
        else
        {
            $this->_successMessages[] = $message;
        }
    }

    /**
     * This function returns the array of "success" messages. To get the messages that are stored
     * in the session as well, call #loadMessagesFromSession first.
     *
     * @return array
     */
    public function retrieveSuccessMessages()
    {
        return $this->_successMessages;
    }

    /**
     * This function stores the "warning" flash message. If $storeInSession is set to true,
     * then the message is stored in the session. Otherwise, it is stored locally.
     *
     * @param string $message
     * @param bool   $storeInSession
     */
    public function storeWarningMessage($message, $storeInSession = true)
    {
        if($storeInSession)
        {
            $this->storeMessageInSession($message, self::WARNING_SESSION_KEY);
        }
        else
        {
            $this->_warningMessages[] = $message;
        }
    }

    /**
     * This function returns the array of "warning" messages. To get the messages that are stored
     * in the session as well, call #loadMessagesFromSession first.
     *
     * @return array
     */
    public function retrieveWarningMessages()
    {
        return $this->_warningMessages;
    }

    /**
     * This function stores the "error" flash message. If $storeInSession is set to true,
     * then the message is stored in the session. Otherwise, it is stored locally.
     *
     * @param string $message
     * @param bool   $storeInSession
     */
    public function storeErrorMessage($message, $storeInSession = true)
    {
        if($storeInSession)
        {
            $this->storeMessageInSession($message, self::ERROR_SESSION_KEY);
        }
        else
        {
            $this->_errorMessages[] = $message;
        }
    }

    /**
     * This function returns the array of "error" messages. To get the messages that are stored
     * in the session as well, call #loadMessagesFromSession first.
     *
     * @return array
     */
    public function retrieveErrorMessages()
    {
        return $this->_errorMessages;
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
            $sessionMessages = array();
        }

        return $sessionMessages;
    }

    /**
     * @param $message
     * @param $messageType
     */
    private function storeMessageInSession($message, $messageType)
    {
        $session  = new Session();
        $messages = $session->read($messageType);

        if(is_array($messages) == false)
        {
            $messages = array();
        }

        $messages[] = $message;
        $session->write($messageType, $messages);
    }
}
