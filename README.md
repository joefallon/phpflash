# PhpFlash — Tiny flash message helper for PHP

Simple, focused, and framework-agnostic helper for storing one-request flash
messages (informational, success, warning, error). If you need a tiny,
well-documented tool to show messages after redirects, this library is for you.

Why use PhpFlash?
- Minimal and focused: one responsibility so it’s easy to understand and audit.
- Framework-agnostic: works in plain PHP or inside frameworks (Laravel, Symfony,
etc.).
- Testable: dependency-inject the `Session` helper for easier testing.
- Secure-by-design guidance: stores raw text and leaves escaping to the
  renderer so output can be escaped correctly for the context.

Quick facts
- Package: joefallon/phpflash
- PHP: 7.4+
- License: MIT

Install

The recommended way to install is via Composer:

```bash
composer require joefallon/phpflash
```

If you are developing or running tests locally, install dev dependencies too:

```bash
composer install
```

Basic usage (quick start)

This example uses the bundled `JoeFallon\PhpSession\Session` session helper
(also installed as a dependency). The common pattern is:

1. Write a flash message before redirecting (it will be available on the next request).
2. In the next request, load messages from the session and render them.

Store a message (usually before a redirect):

```php
use JoeFallon\PhpSession\Session;
use JoeFallon\PhpFlash\FlashMessages;

$session = new Session();
$flash = new FlashMessages($session);

// Persist a success message for the next request
$flash->storeSuccessMessage('Saved successfully');

// You can also store messages in memory for the same request
$flash->storeInfoMessage('Local info', false);
```

Retrieve and render messages (next request):

```php
use JoeFallon\PhpSession\Session;
use JoeFallon\PhpFlash\FlashMessages;

$flash = new FlashMessages(new Session());
$flash->loadMessagesFromSession(); // load and clear messages persisted to session

$successMessages = $flash->retrieveSuccessMessages();
foreach ($successMessages as $message) {
    // Escape for HTML output!
    echo '<div class="alert alert-success">' . htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</div>';
}
```

API reference (essential methods)

- __construct(?JoeFallon\PhpSession\Session $session = null)
  - Accepts an optional `Session` instance. If omitted, a new Session is
    created internally.

- storeInfoMessage(string $message, bool $storeInSession = true): void
- storeSuccessMessage(string $message, bool $storeInSession = true): void
- storeWarningMessage(string $message, bool $storeInSession = true): void
- storeErrorMessage(string $message, bool $storeInSession = true): void
  - Stores a message. By default messages are serialized to the session and
    available on the next request. Pass `false` to keep the message in-memory
    for the current request only.

- loadMessagesFromSession(): void
  - Loads messages persisted in the session into memory and clears them from
    the session. Call this early in the request where you plan to render
    messages.

- retrieveInfoMessages(): array
- retrieveSuccessMessages(): array
- retrieveWarningMessages(): array
- retrieveErrorMessages(): array
  - Returns an array of strings for the corresponding type. These return only
    in-memory messages; call `loadMessagesFromSession()` first to include
    messages persisted to the session.

Rendering best practices and security notes

- PhpFlash intentionally stores raw strings. It does NOT perform HTML
  escaping or sanitisation. This is deliberate: escaping should happen at
  render time and must match the output context (HTML, JavaScript, JSON,
  CLI, etc.).

- When rendering HTML, always escape using `htmlspecialchars()` with
  `ENT_QUOTES | ENT_SUBSTITUTE` and an explicit UTF-8 charset. Example:

```php
echo '<p>' . htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</p>';
```

- Avoid placing sensitive data (passwords, personal data) into flash
  messages.

- The library uses JSON encoding to persist arrays of messages in the session.
  Ensure the `ext-json` PHP extension is available on your environment (it is
  enabled by default in modern PHP builds).

Example: Bootstrap-ready helper

If you use Bootstrap alerts, you can convert flash messages into HTML quickly
(remember to escape content):

```php
function renderFlashAlerts(FlashMessages $flash): void
{
    $flash->loadMessagesFromSession();

    $map = [
        'success' => $flash->retrieveSuccessMessages(),
        'info'    => $flash->retrieveInfoMessages(),
        'warning' => $flash->retrieveWarningMessages(),
        'danger'  => $flash->retrieveErrorMessages(),
    ];

    foreach ($map as $class => $messages) {
        foreach ($messages as $message) {
            $escaped = htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            echo "<div class=\"alert alert-{$class}\">{$escaped}</div>\n";
        }
    }
}
```

Testing

The project includes PHPUnit tests. To run them locally:

```bash
composer install
./vendor/bin/phpunit -c phpunit.xml.dist
```

Development notes

- The library targets small, well-scoped behaviour. If you plan enhancements,
  prefer small, focused pull requests and include tests.
- If your project already has a session manager, inject it into `FlashMessages`
  to avoid creating multiple session helpers.

Troubleshooting

- No messages displayed? Ensure you call `store{Type}Message()` before the
  redirect and `loadMessagesFromSession()` early in the request that follows.
- Session not persisting? Verify your PHP session configuration and that
  headers are not already sent before sessions start. The included `Session`
  helper falls back to an emulated session when headers are already sent.

Contributing and support

Contributions and bug reports are welcome. Please open issues or PRs on the
project repository and include tests for new behaviour.

License

MIT — see the `LICENSE` file for details.

Thank you for considering PhpFlash. If this library helped you ship faster,
please star the repository so others can find it.
