<?php
namespace tests\JoeFallon\PhpFlash;

use JoeFallon\KissTest\UnitTest;
use JoeFallon\PhpFlash\FlashMessages;

class FlashMessagesTests extends UnitTest
{
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

    public function test_retrieveErrorMessages_retrieves_messages_and_deletes()
    {
        $flash = new FlashMessages();
        $flash->storeErrorMessage('test error message1');
        $flash->storeErrorMessage('test error message2', true);

        $out1 = $flash->retrieveErrorMessages();
        $this->assertEqual(count($out1), 2);

        $out2 = $flash->retrieveErrorMessages();
        $this->assertEqual(count($out2), 0);
    }

    public function test_retrieveInfoMessages_retrieves_messages_and_deletes()
    {
        $flash = new FlashMessages();
        $flash->storeInfoMessage('test info message1');
        $flash->storeInfoMessage('test info message2', true);

        $out1 = $flash->retrieveInfoMessages();
        $this->assertEqual(count($out1), 2);

        $out2 = $flash->retrieveInfoMessages();
        $this->assertEqual(count($out2), 0);
    }

    public function test_retrieveSuccessMessages_retrieves_messages_and_deletes()
    {
        $flash = new FlashMessages();
        $flash->storeSuccessMessage('test success message1');
        $flash->storeSuccessMessage('test success message2', true);

        $out1 = $flash->retrieveSuccessMessages();
        $this->assertEqual(count($out1), 2);

        $out2 = $flash->retrieveSuccessMessages();
        $this->assertEqual(count($out2), 0);
    }

    public function test_retrieveWarningMessages_retrieves_messages_and_deletes()
    {
        $flash = new FlashMessages();
        $flash->storeWarningMessage('test warn message1');
        $flash->storeWarningMessage('test warn message2', true);

        $out1 = $flash->retrieveWarningMessages();
        $this->assertEqual(count($out1), 2, 'count');

        $out2 = $flash->retrieveWarningMessages();
        $this->assertEqual(count($out2), 0);;
    }
}
