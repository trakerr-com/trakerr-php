# Trakerr - PHP API client
Get your application events and errors to Trakerr via the *Trakerr Client*.

## Requirements
PHP 5.4.0 and later

## 3-minute Integration Guide

Install the bindings via [Composer](http://getcomposer.org/), add the following to `composer.json`:

```json
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

Then run `composer install`. This should make it easy to keep the API up to date.

If you are not using composer download all the files and include `autoload.php`:

```php
require_once(__DIR__ . '/../autoload.php');
```

Finally, in your code call: 

```php
$trakerrClient = new \trakerr\TrakerrClient("<api-key>", "App version here", "Deployment stage here");
$trakerrClient->registerErrorHandlers();
```

That should activate a global error handler for you to use.
## Detailed Integration Guide
###Installation
Please follow the [three minute guide](#3-minute-Integration-Guide) for supported installation instructions.

### Option-1: Register global event handler
This example was also covered above, in the [three minute guide](#3-minute-Integration-guide)

### Option-2: Sent event programmatically
Send an event to trackerr within a try catch to handle an event while sending it to trakerr. Simply call send error from catch statement, and pass in an error and the loglevel and classification.

```php
$trakerrClient = new \trakerr\TrakerrClient("<api-key>", "App version here", "Deployment stage here");
    // Option-2: catch and send error to Trakerr programmatically
try {
    throw new Exception("test exception");
} catch (Exception $e) {
    $trakerrClient->sendError($e, "fatal");
}
```

### Option-3: Send event programmatically but with custom properties
You can send custom properties from an event which is being handled. Create a new AppEvent through the APIand populate the instance with the custom data that you want. Be sure to send the event to trakerr after you are done!

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
Trakerr accepts non errors and application events. We suggest that you send the user and session, along with setting the event name, message, at the least before sending it to Trakerr.

```php
     // Option-4: send any event programmatically
    $appEvent = $trakerrClient->createAppEvent("warning", "type warn", "TestType", "Test message from php");

    $trakerrClient->sendEvent($appEvent);
```

## About TrakerrClient's properties
The `TrakerrClient` class above can be constructed to take aditional data, rather than using the configured defaults. The constructor signature is:

```php
public function __construct($apiKey, $contextAppVersion = "1.0",
$contextDeploymentStage = "development")
```
The TrakerrClient class however has a lot of exposed properties. The benefit to setting these immediately after after you create the TrakerrClient is that AppEvent will default it's values against the TrakerClient that created it. This way if there is a value that all your AppEvents uses, and the constructor default value currently doesn't suit you; it may be easier to change it in TrakerrClient as it will become the default value for all AppEvents created after. A lot of these are populated by default value by the constructor, but you can populate them with whatever string data you want. The following table provides an in depth look at each of those.

Name | Type | Description | Notes
------------ | ------------- | -------------  | -------------
**apiKey** | **string**  | API Key for your application. |
**contextAppVersion** | **string** | Provide the application version. | Default Value: `1.0`
**contextDevelopmentStage** | **string** | One of development, staging, production; or a custom string. | Default Value: `development`
**contextEnvLanguage** | **string** | Constant string representing the language the application is in. | Default value: `php`.
**contextEnvName** | **string** | Name of the CLR the program is running on | Defaults value: `php`
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




