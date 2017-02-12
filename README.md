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

### Create the Trakerr client first

```php
    // initialize the client
    $trakerrClient = new \trakerr\TrakerrClient("<REPLACE WITH API KEY>", null);
```

### Option-1: Registering global error handlers

```php
    // Option-1: register global exception handlers (optional)
    $trakerrClient->registerErrorHandlers();
```

### Option-2: Sent event programmatically

```php
    // Option-2: catch and send error to Trakerr programmatically
    try {
        throw new Exception("test exception");
    } catch(Exception $e) {
        $trakerr_client->sendError("Error", $e);
    }
```

### Option-3: Send event programmatically but with custom properties

```php
    // Option-3: catch and send error to Trakerr with some custom data programmatically
    use trakerr\client\model\CustomData;
    use trakerr\client\model\CustomStringData;

    try {
    } catch(Exception $e) {
        // this is just an example
        // declare and initialize this client in your code and re-use it for multiple events
        // do not create it at the time you want to send the event
        $trakerr_client = new \trakerr\TrakerrClient("<REPLACE WITH API KEY>", null);
        $appEvent = $trakerr_client->createAppEventFromException("Error", $e);

        // set some custom data
        $customProperties = new CustomData();
        $customStringData = new CustomStringData();
        $customStringData->setCustomData1("Some custom data");
        $customProperties->setStringData($customStringData);
        $appEvent->setCustomProperties($customProperties);

        $trakerr_client->sendEvent($appEvent);
    }
```

### Option-4: Create an event (eg. non-exception) and send it to Trakerr

```php
    // Option-4: send any event programmatically
    $appEvent = $trakerr_client->createAppEvent("Error", "TestType", "Test message from php");

    $trakerr_client->sendEvent($appEvent);
```

## About the php constructor
The `TrakerrClient` class above can be constructed to take aditional data, rather than using the configured defaults. The constructor signature is:

```php
public function __construct($apiKey = null, $url = null, $contextAppVersion = "1.0",
$contextEnvName = "development", $contextEnvVersion = null, $contextEnvHostname = null, $contextAppOS = null,
$contextAppOSVersion = null, $contextDataCenter = null, $contextDataCenterRegion = null)
```

Some of the arguments have default values when passed in null. Below is a list of the arguments, and what Trakerr expects so you can pass in custom data.

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**apiKey** | **str** | API Key for the application. | [Required]
**url** | **str** | (optional) URL to Trakerr, specify null to use default. | [Optional if set to `null`] Defaults to reading url property under appSettings from the configuration.php.
**contextAppVersion** | **str** | (optional) Application version, defaults to 1.0. | [Optional if set to `null`] Defaults to "1.0".
**contextEnvName** | **str** | (optional) Environment name like "development", "staging", "production" or a custom string. | [Optional if set to `null`] Defaults to "develoment".
**contextEnvVersion** | **str** | (optional) Environment version. | [Optional if set to `null`] Defaults to `null`. 
**contextEnvHostname** | **str** | (optional) Environment hostname. | [Optional if set to `null`] Defaults to `null`.
**contextAppOS** | **str** | (optional) Operating system. | [Optional if set to `null`] Defaults to `php_uname("s")`
**contextAppOSVersion** | **str** | (optional)  Operating system version. | [Optional if set to `null`] Defaults to `php_uname("v")`.
**contextDataCenter** | **str** | (optional) Provide a datacenter name. | [Optional if set to `null`] Defaults to `null`.
**contextDataCenterRegion** | **str** | (optional) Provide a datacenter region. | [Optional if set to `null`] Defaults to `null`.

If you want to use a default value in a custom call, simply pass in `null` to the argument, and it will be filled with the default value.

## Documentation For Models

 - [AppEvent](https://github.com/trakerr-io/trakerr-php/blob/master/generated/SwaggerClient-php/docs/Model/AppEvent.md)




