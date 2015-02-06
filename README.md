# phpflash

By [Joe Fallon](http://blog.joefallon.net) 

This library has the following features:

*   Fully unit tested.
*   Allows flash message to be stored in either the session or in a temporary
    variable.
*   They following types of flash messages can be stored: info, warning, 
    success, and error

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

```
storeInfoMessage($key, $msg, $storeInSession = false)
retrieveInfoMessages()
retrieveInfoMessage($key)

storeSuccessMessage($key, $msg, $storeInSession = false)
retrieveSuccessMessages()
retrieveSuccessMessage($key)

storeWarningMessage($key, $msg, $storeInSession = false)
retrieveWarningMessages()
retrieveWarningMessage($key)

storeErrorMessage($key, $msg, $storeInSession = false)
retrieveErrorMessages()
retrieveErrorMessage($key)
```
