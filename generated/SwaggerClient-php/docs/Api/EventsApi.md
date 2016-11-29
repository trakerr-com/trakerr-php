# trakerr\client\EventsApi

All URIs are relative to *https://www.trakerr.io/api/v1*

Method | HTTP request | Description
------------- | ------------- | -------------
[**eventsPost**](EventsApi.md#eventsPost) | **POST** /events | Submit an application event or error to Trakerr


# **eventsPost**
> eventsPost($data)

Submit an application event or error to Trakerr

The events endpoint submits an application event or an application error / exception with an optional stacktrace field to Trakerr.  ##### Sample POST request body: ``` {  \"apiKey\": \"a9a2807a2e8fd4602adae9e8f819790a267213234083\",  \"classification\": \"Error\",  \"eventType\": \"System.Exception\",  \"eventMessage\": \"This is a test exception.\",  \"eventTime\": 1479477482291,  \"eventStacktrace\": [    {      \"type\": \"System.Exception\",      \"message\": \"This is a test exception.\",      \"traceLines\": [        {          \"function\": \"Main\",          \"line\": 19,          \"file\": \"TrakerrSampleApp\\\\Program.cs\"        }      ]    }  ],  \"contextAppVersion\": \"1.0\",  \"contextEnvName\": \"development\",  \"contextEnvHostname\": \"trakerr.io\",  \"contextAppOS\": \"Win32NT Service Pack 1\",  \"contextAppOSVersion\": \"6.1.7601.65536\" } ``` ##### Sample POST response body (200 OK): ``` { } ```

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

$api_instance = new trakerr\client\Api\EventsApi();
$data = new \trakerr\client\model\AppEvent(); // \trakerr\client\model\AppEvent | Event to submit

try {
    $api_instance->eventsPost($data);
} catch (Exception $e) {
    echo 'Exception when calling EventsApi->eventsPost: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **data** | [**\trakerr\client\model\AppEvent**](../Model/\trakerr\client\model\AppEvent.md)| Event to submit |

### Return type

void (empty response body)

### Authorization

No authorization required

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

