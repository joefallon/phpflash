<?php
declare(strict_types=1);

namespace Tests\JoeFallon\PhpFlash;

use PHPUnit\Framework\TestCase;
use JoeFallon\PhpFlash\FlashMessages;
use JoeFallon\PhpSession\Session;

final class FlashMessagesTest extends TestCase
{
    protected function setUp(): void
    {
        $session = new Session();
        $session->destroy();
    }

    public function test_initialization(): void
    {
        $flash           = new FlashMessages();
        $errorMessages   = $flash->retrieveErrorMessages();
        $infoMessages    = $flash->retrieveInfoMessages();
        $successMessages = $flash->retrieveSuccessMessages();
        $warningMessages = $flash->retrieveWarningMessages();

        $this->assertIsArray($errorMessages);
        $this->assertIsArray($infoMessages);
        $this->assertIsArray($successMessages);
        $this->assertIsArray($warningMessages);

        $this->assertCount(0, $errorMessages);
        $this->assertCount(0, $infoMessages);
        $this->assertCount(0, $successMessages);
        $this->assertCount(0, $warningMessages);
    }

    public function test_storeInfoMessage_and_retrieveInfoMessages_in_memory(): void
    {
        $flash = new FlashMessages();
        $flash->storeInfoMessage('info message', false);
        $messages = $flash->retrieveInfoMessages();

        $this->assertCount(1, $messages);
        $this->assertSame('info message', $messages[0]);
    }

    public function test_storeSuccessMessage_and_retrieveSuccessMessages_in_memory(): void
    {
        $flash = new FlashMessages();
        $flash->storeSuccessMessage('success message', false);
        $messages = $flash->retrieveSuccessMessages();

        $this->assertCount(1, $messages);
        $this->assertSame('success message', $messages[0]);
    }

    public function test_storeWarningMessage_and_retrieveWarningMessages_in_memory(): void
    {
        $flash = new FlashMessages();
        $flash->storeWarningMessage('warning message', false);
        $messages = $flash->retrieveWarningMessages();

        $this->assertCount(1, $messages);
        $this->assertSame('warning message', $messages[0]);
    }

    public function test_storeErrorMessage_and_retrieveErrorMessages_in_memory(): void
    {
        $flash = new FlashMessages();
        $flash->storeErrorMessage('error message', false);
        $messages = $flash->retrieveErrorMessages();

        $this->assertCount(1, $messages);
        $this->assertSame('error message', $messages[0]);
    }

    public function test_loadMessagesFromSession(): void
    {
        $flash1 = new FlashMessages();
        $flash1->storeInfoMessage('session info message',       true);
        $flash1->storeSuccessMessage('session success message', true);
        $flash1->storeWarningMessage('session warning message', true);
        $flash1->storeErrorMessage('session error message',     true);

        $flash2 = new FlashMessages();
        $this->assertCount(0, $flash2->retrieveErrorMessages());
        $this->assertCount(0, $flash2->retrieveWarningMessages());
        $this->assertCount(0, $flash2->retrieveSuccessMessages());
        $this->assertCount(0, $flash2->retrieveInfoMessages());

        $flash2->loadMessagesFromSession();

        $infoMessages    = $flash2->retrieveInfoMessages();
        $successMessages = $flash2->retrieveSuccessMessages();
        $warningMessages = $flash2->retrieveWarningMessages();
        $errorMessages   = $flash2->retrieveErrorMessages();

        $this->assertCount(1, $infoMessages);
        $this->assertCount(1, $successMessages);
        $this->assertCount(1, $warningMessages);
        $this->assertCount(1, $errorMessages);

        $this->assertSame('session info message',    $infoMessages[0]);
        $this->assertSame('session success message', $successMessages[0]);
        $this->assertSame('session warning message', $warningMessages[0]);
        $this->assertSame('session error message',   $errorMessages[0]);
    }
}

