<?php
namespace tests\JoeFallon\PhpFlash;

use JoeFallon\KissTest\UnitTest;
use JoeFallon\PhpFlash\FlashMessages;
use JoeFallon\PhpSession\Session;

class FlashMessagesTests extends UnitTest
{
    public function __construct()
    {
        $session = new Session();
        $session->destroy();

        parent::__construct();
    }

    public function test_initialization()
    {
        $flash           = new FlashMessages();
        $errorMessages   = $flash->retrieveErrorMessages();
        $infoMessages    = $flash->retrieveInfoMessages();
        $successMessages = $flash->retrieveSuccessMessages();
        $warningMessages = $flash->retrieveWarningMessages();

        $this->assertTrue(is_array($errorMessages));
        $this->assertTrue(is_array($infoMessages));
        $this->assertTrue(is_array($successMessages));
        $this->assertTrue(is_array($warningMessages));

        $this->assertEqual(count($errorMessages),   0, '$errorMessages');
        $this->assertEqual(count($infoMessages),    0, '$infoMessages');
        $this->assertEqual(count($successMessages), 0, '$successMessages');
        $this->assertEqual(count($warningMessages), 0, '$warningMessages');
    }

    public function test_storeInfoMessage_and_retrieveInfoMessages_in_memory()
    {
        $flash = new FlashMessages();
        $flash->storeInfoMessage('info message', false);
        $messages = $flash->retrieveInfoMessages();

        $this->assertEqual(count($messages), 1);
        $message = $messages[0];
        $this->assertEqual($message, 'info message');
    }

    public function test_storeSuccessMessage_and_retrieveSuccessMessages_in_memory()
    {
        $flash = new FlashMessages();
        $flash->storeSuccessMessage('success message', false);
        $messages = $flash->retrieveSuccessMessages();

        $this->assertEqual(count($messages), 1);
        $message = $messages[0];
        $this->assertEqual($message, 'success message');
    }

    public function test_storeWarningMessage_and_retrieveWarningMessages_in_memory()
    {
        $flash = new FlashMessages();
        $flash->storeWarningMessage('warning message', false);
        $messages = $flash->retrieveWarningMessages();

        $this->assertEqual(count($messages), 1);
        $message = $messages[0];
        $this->assertEqual($message, 'warning message');
    }

    public function test_storeErrorMessage_and_retrieveErrorMessages_in_memory()
    {
        $flash = new FlashMessages();
        $flash->storeErrorMessage('error message', false);
        $messages = $flash->retrieveErrorMessages();

        $this->assertEqual(count($messages), 1);
        $message = $messages[0];
        $this->assertEqual($message, 'error message');
    }

    public function test_loadMessagesFromSession()
    {
        $flash1 = new FlashMessages();
        $flash1->storeInfoMessage('session info message',       true);
        $flash1->storeSuccessMessage('session success message', true);
        $flash1->storeWarningMessage('session warning message', true);
        $flash1->storeErrorMessage('session error message',     true);

        $flash2 = new FlashMessages();
        $this->assertEqual(count($flash2->retrieveErrorMessages()),   0);
        $this->assertEqual(count($flash2->retrieveWarningMessages()), 0);
        $this->assertEqual(count($flash2->retrieveSuccessMessages()), 0);
        $this->assertEqual(count($flash2->retrieveInfoMessages()),    0);

        $flash2->loadMessagesFromSession();

        $infoMessages    = $flash2->retrieveInfoMessages();
        $successMessages = $flash2->retrieveSuccessMessages();
        $warningMessages = $flash2->retrieveWarningMessages();
        $errorMessages   = $flash2->retrieveErrorMessages();

        $this->assertEqual(count($infoMessages),    1, 'info count');
        $this->assertEqual(count($successMessages), 1, 'success count');
        $this->assertEqual(count($warningMessages), 1, 'warning count');
        $this->assertEqual(count($errorMessages),   1, 'error count');

        $this->assertEqual($infoMessages[0],    'session info message');
        $this->assertEqual($successMessages[0], 'session success message');
        $this->assertEqual($warningMessages[0], 'session warning message');
        $this->assertEqual($errorMessages[0],   'session error message');
    }
}
