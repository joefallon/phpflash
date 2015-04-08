# phpflash

By [Joe Fallon](http://blog.joefallon.net) 

The flash is a special part of the session which is cleared with each request. 
This means that values stored there will only be available in the next request, 
which is useful for passing error messages, etc.

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
error   - Some sort of prorgram error occured.
```

### Info Messages

```php
storeInfoMessage($key, $msg, $storeInSession = false)
retrieveInfoMessages()
retrieveInfoMessage($key)
```

### Success Messages

```php
storeSuccessMessage($key, $msg, $storeInSession = false)
retrieveSuccessMessages()
retrieveSuccessMessage($key)
```

### Warning Messages

```php
storeWarningMessage($key, $msg, $storeInSession = false)
retrieveWarningMessages()
retrieveWarningMessage($key)
```

### Error Messages

```php
storeErrorMessage($key, $msg, $storeInSession = false)
retrieveErrorMessages()
retrieveErrorMessage($key)
```
