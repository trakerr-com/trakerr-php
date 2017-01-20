# trakerr-php API client
Get your application events and errors to Trakerr via the *Trakerr Client*.

- API version: 1.0.0

## Requirements

PHP 5.4.0 and later

## Installation & Usage
### Composer

To install the bindings via [Composer](http://getcomposer.org/), add the following to `composer.json`:

```
{
  "repositories": [
    {
      "type": "git",
      "url": "https://github.com/trakerr-io/trakerr-php.git"
    }
  ],
  "require": {
    "trakerr/trakerr-php": "*@dev"
  }
}
```

Then run `composer install`

### Manual Installation

Download the files and include `autoload.php`:

```php
    require_once(__DIR__ . '/vendor/autoload.php');
```

## Getting Started

Please follow the [installation procedure](#installation--usage) and then run the following:

```php
<?php
    require_once(__DIR__ . '/vendor/autoload.php');

    // initialize the client
    $trakerrClient = new \trakerr\TrakerrClient("<REPLACE WITH API KEY>", null);

    // Option-1: register global exception handlers (optional)
    $trakerrClient->registerErrorHandlers();
    
    // Option-2: catch and send error to Trakerr programmatically
    try {
        throw new Exception("test exception");
    } catch(Exception $e) {
        $trakerr_client->sendError("Error", $e);
    }

    // Option-3: send any event programmatically
    $appEvent = $trakerr_client->createAppEvent("Error", "TestType", "Test message from php");
    $trakerr_client->sendEvent($appEvent);
?>
```

## Documentation For Models

 - [AppEvent](generated/SwaggerClient-php/docs/Model/AppEvent.md)




