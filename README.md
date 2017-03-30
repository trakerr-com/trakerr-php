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
    require_once(__DIR__ . '/../autoload.php');
```

## Getting Started

Please follow the [installation procedure](#installation--usage) and then run the following:

### Create the Trakerr client first

```php
    // initialize the client
    $trakerrClient = new \trakerr\TrakerrClient("<REPLACE WITH API KEY>", Null);
```

### Option-1: Registering global error handlers

```php
    // Option-1: register global exception handlers (optional)
    $trakerrClient->registerErrorHandlers();

    throw new Exception("Not enough math");
```

### Option-2: Sent event programmatically

```php
    // Option-2: catch and send error to Trakerr programmatically
    try {
        throw new Exception("test exception");
    } catch (Exception $e) {
        $trakerrClient->sendError($e, "fatal");
    }
```

### Option-3: Send event programmatically but with custom properties

```php
    // Option-3: catch and send error to Trakerr with some custom data programmatically
    use trakerr\client\model\CustomData;
    use trakerr\client\model\CustomStringData;

    try {
        throw new Exception("Too much math");
    } catch (Exception $e) {
        $appEvent2 = $trakerrClient->createAppEventFromException($e, "Error");

        // set some custom data
        $customProperties = new CustomData();
        $customStringData = new CustomStringData();
        $customStringData->setCustomData1("Some custom data");
        $customProperties->setStringData($customStringData);
        $appEvent2->setCustomProperties($customProperties);

        $trakerrClient->sendEvent($appEvent2);
    }
```

### Option-4: Create an event (eg. non-exception) and send it to Trakerr

```php
     // Option-4: send any event programmatically
    $appEvent = $trakerrClient->createAppEvent("warning", "type warn", "TestType", "Test message from php");

    $trakerrClient->sendEvent($appEvent);
```

## About TrakerrClient's properties
The `TrakerrClient` class above can be constructed to take aditional data, rather than using the configured defaults. The constructor signature is:

```php
public function __construct($apiKey = Null, $contextAppVersion = "1.0",
$contextDeploymentStage = "development")
```
The TrakerrClient class however has a lot of exposed properties. The benefit to setting these immediately after after you create the TrakerrClient is that AppEvent will default it's values against the TrakerClient that created it. This way if there is a value that all your AppEvents uses, and the constructor default value currently doesn't suit you; it may be easier to change it in TrakerrClient as it will become the default value for all AppEvents created after. A lot of these are populated by default value by the constructor, but you can populate them with whatever string data you want. The following table provides an in depth look at each of those.

Name | Type | Description | Notes
------------ | ------------- | -------------  | -------------
**apiKey** | **string**  | API Key for your application. |
**contextAppVersion** | **string** | Provide the application version. | Default Value: "1.0"
**contextDevelopmentStage** | **string** | One of development, staging, production; or a custom string. | Default Value: "development"
**contextEnvLanguage** | **string** | Constant string representing the language the application is in. | Default value: "php".
**contextEnvName** | **string** | Name of the CLR the program is running on | Defaults value: "php"
**contextEnvVersion** | **string** | Provide an environment version. | Defaults Value: `PHP_VERSION_ID`.
**contextEnvHostname** | **string** | Provide the current hostname. | Defaults Value: `gethostname()`.
**contextAppOS** | **string** | Provide an operating system name. | Defaults Value: `php_uname("s")`.
**contextAppOSVersion** | **string** | Provide an operating system version. | Default Value: `php_uname("v")`.
**contextAppOSBrowser** | **string** | An optional string browser name the application is running on. | Defaults to `Null`
**contextAppOSBrowserVersion** | **string** | An optional string browser version the application is running on. | Defaults to `Null`
**contextDataCenter** | **string** | Data center the application is running on or connected to. | Defaults to `Null`
**contextDataCenterRegion** | **string** | Data center region. | Defaults to `Null`


## Documentation For Models
 - [AppEvent](https://github.com/trakerr-io/trakerr-php/blob/master/generated/SwaggerClient-php/docs/Model/AppEvent.md)




