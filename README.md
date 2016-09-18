# phpflash

By [Joe Fallon](http://blog.joefallon.net) 

The flash is a special part of the session which is cleared with each request. 
This means that values stored there will only be available in the next request. 
This is useful for passing error/success/warning/info messages to the user.

This library has the following features:

*   Fully unit tested.
*   Allows flash message to be stored in either the session or in a temporary
    variable.
*   They following types of flash messages can be stored: info, warning, 
    success, and error.

## Installation

The easiest way to install PHP Flash is with
[Composer](https://getcomposer.org/). Create the following `composer.json` file
and run the `php composer.phar install` command to install it.

```json
{
    "require": {
        "joefallon/phpflash": "*"
    }
}
```

## Usage

### Types of Messages

```
info    - Some event occurred that the user should be aware of.
warning - Something not good happened, but it isn't an error.
success - Whatever was attempted did, in fact, succeed.
error   - Some sort of program error occurred.
```

### Working with Messages in the Session

To load and clear the messages that are in the session call the
following:

```php
loadMessagesFromSession()
```

Calling #loadMessagesFromSession is required because once PHP 
begins sending the page data back to the client, the session 
can no longer be accessed.

### Info Messages

```php
storeInfoMessage($message, $storeInSession = true)
retrieveInfoMessages()
```

### Success Messages

```php
storeSuccessMessage($message, $storeInSession = true)
retrieveSuccessMessages()
```

### Warning Messages

```php
storeWarningMessage($message, $storeInSession = true)
retrieveWarningMessages()
```

### Error Messages

```php
storeErrorMessage($message, $storeInSession = true)
retrieveErrorMessages()
```

### Example Usage on Two Separate Page Requests

```php
$flash1 = new FlashMessages();
$flash1->storeInfoMessage('my info message', true);
```

later on the next request...

```php
$flash2 = new FlashMessages();
$flash2->loadMessagesFromSession();
$infoMessages = $flash2->retrieveInfoMessages();
```

### Example Usage on the Same Page Request

```php
$flash = new FlashMessages();
$flash->storeInfoMessage('my info message', false);
```

later on in the same request...

```php
$flash = new FlashMessages();
$infoMessages = $flash->retrieveInfoMessages();
```
