<?php
declare(strict_types=1);

namespace JoeFallon\PhpFlash;

use JoeFallon\PhpSession\Session;

/**
 * FlashMessages
 *
 * Small utility to store and retrieve transient ("flash") messages that should
 * survive a single request -> redirect -> request cycle. Messages can be stored
 * locally (in-memory for current request) or persisted to session for retrieval
 * on the next request.
 *
 * Design goals and notes:
 * - Simplicity: single responsibility and small API surface.
 * - Testability: accepts a Session instance via constructor (dependency injection).
 * - Security: this class does not perform HTML escaping or sanitisation. Rendering
 *   code should escape or sanitize message content according to the output
 *   context (HTML, JSON, CLI, etc.).
 * - API stability: method names are intentionally kept descriptive and stable.
 *
 * Usage example:
 *   $flash = new FlashMessages($session); // $session is JoeFallon\PhpSession\Session
 *   $flash->storeSuccessMessage("Saved successfully"); // persisted to session
 *   // In the next request:
 *   $flash->loadMessagesFromSession();
 *   $messages = $flash->retrieveSuccessMessages();
 *
 * @package JoeFallon\PhpFlash
 */
final class FlashMessages
{
    public const INFO_SESSION_KEY    = 'flash_information_messages';
    public const SUCCESS_SESSION_KEY = 'flash_success_messages';
    public const WARNING_SESSION_KEY = 'flash_warning_messages';
    public const ERROR_SESSION_KEY   = 'flash_error_messages';

    /**
     * Session instance used to persist flash messages between requests.
     * Injecting the dependency makes the class easier to test.
     */
    private Session $session;

    /** @var string[] Messages stored for the current request */
    private array $infoMessages = [];

    /** @var string[] Messages stored for the current request */
    private array $successMessages = [];

    /** @var string[] Messages stored for the current request */
    private array $warningMessages = [];

    /** @var string[] Messages stored for the current request */
    private array $errorMessages = [];

    /**
     * Constructor.
     *
     * @param Session|null $session If omitted, a concrete Session will be created.
     */
    public function __construct(?Session $session = null)
    {
        $this->session = $session ?? new Session();
    }

    /**
     * Load messages from the session and clear them from the session store.
     * Messages loaded from the session are prepended to any messages already
     * present in memory so that session messages appear first when retrieved.
     */
    public function loadMessagesFromSession(): void
    {
        $this->infoMessages = array_merge(
            $this->retrieveAndClearSessionMessages(self::INFO_SESSION_KEY),
            $this->infoMessages
        );

        $this->successMessages = array_merge(
            $this->retrieveAndClearSessionMessages(self::SUCCESS_SESSION_KEY),
            $this->successMessages
        );

        $this->warningMessages = array_merge(
            $this->retrieveAndClearSessionMessages(self::WARNING_SESSION_KEY),
            $this->warningMessages
        );

        $this->errorMessages = array_merge(
            $this->retrieveAndClearSessionMessages(self::ERROR_SESSION_KEY),
            $this->errorMessages
        );
    }

    /**
     * Store an informational message.
     *
     * @param string $message Message text. Should not be escaped here; escape at render time.
     * @param bool   $storeInSession If true, message is persisted to session for next request.
     *
     * @throws \JsonException
     */
    public function storeInfoMessage(string $message, bool $storeInSession = true): void
    {
        if($storeInSession)
        {
            $this->storeMessageInSession($message, self::INFO_SESSION_KEY);

            return;
        }

        $this->infoMessages[] = $message;
    }

    /**
     * Retrieve informational messages currently stored in memory.
     * Call {@see loadMessagesFromSession()} first if you also want messages that were
     * persisted to the session.
     *
     * @return string[]
     */
    public function retrieveInfoMessages(): array
    {
        return $this->infoMessages;
    }

    /**
     * Store a success message.
     *
     * @param string $message
     * @param bool   $storeInSession
     *
     * @throws \JsonException
     */
    public function storeSuccessMessage(string $message, bool $storeInSession = true): void
    {
        if($storeInSession)
        {
            $this->storeMessageInSession($message, self::SUCCESS_SESSION_KEY);

            return;
        }

        $this->successMessages[] = $message;
    }

    /**
     * Retrieve success messages currently stored in memory.
     *
     * @return string[]
     */
    public function retrieveSuccessMessages(): array
    {
        return $this->successMessages;
    }

    /**
     * Store a warning message.
     *
     * @param string $message
     * @param bool   $storeInSession
     *
     * @throws \JsonException
     */
    public function storeWarningMessage(string $message, bool $storeInSession = true): void
    {
        if($storeInSession)
        {
            $this->storeMessageInSession($message, self::WARNING_SESSION_KEY);

            return;
        }

        $this->warningMessages[] = $message;
    }

    /**
     * Retrieve warning messages currently stored in memory.
     *
     * @return string[]
     */
    public function retrieveWarningMessages(): array
    {
        return $this->warningMessages;
    }

    /**
     * Store an error message.
     *
     * @param string $message
     * @param bool   $storeInSession
     *
     * @throws \JsonException
     */
    public function storeErrorMessage(string $message, bool $storeInSession = true): void
    {
        if($storeInSession)
        {
            $this->storeMessageInSession($message, self::ERROR_SESSION_KEY);

            return;
        }

        $this->errorMessages[] = $message;
    }

    /**
     * Retrieve error messages currently stored in memory.
     *
     * @return string[]
     */
    public function retrieveErrorMessages(): array
    {
        return $this->errorMessages;
    }

    /**
     * Read messages for a message type from session and clear them from the session.
     *
     * @param string $messageType Session key to read
     *
     * @return string[] Array of messages. Always returns an array.
     */
    private function retrieveAndClearSessionMessages(string $messageType): array
    {
        $sessionMessages = $this->session->read($messageType);
        $this->session->unsetSessionValue($messageType);

        if($sessionMessages === null)
        {
            return [];
        }

        // Session stores strings. We encoded arrays as JSON strings; decode here.
        if(is_string($sessionMessages))
        {
            $decoded = json_decode($sessionMessages, true);
            if(is_array($decoded))
            {
                return $decoded;
            }

            // If decoding fails but the stored string looks like a single message, return it.
            return [$sessionMessages];
        }

        // For backward-compatibility: if a different type was stored, try to coerce to array.
        if(is_array($sessionMessages))
        {
            return $sessionMessages;
        }

        return [];
    }

    /**
     * Append a message to the array stored in session under the provided key.
     *
     * @param string $message
     * @param string $messageType
     *
     * @throws \JsonException
     */
    private function storeMessageInSession(string $message, string $messageType): void
    {
        $messagesRaw = $this->session->read($messageType);

        $messages = [];

        if(is_string($messagesRaw))
        {
            $decoded = json_decode($messagesRaw, true);
            if(is_array($decoded))
            {
                $messages = $decoded;
            }
            else
            {
                // If a plain string was stored previously, keep it as a single element
                $messages = [$messagesRaw];
            }
        }
        elseif(is_array($messagesRaw))
        {
            // back-compat: if session implementation returns arrays, accept them
            $messages = $messagesRaw;
        }

        $messages[] = $message;

        // Session::write expects a string; store JSON encoded array
        $this->session->write($messageType, json_encode($messages, JSON_THROW_ON_ERROR));
    }
}
