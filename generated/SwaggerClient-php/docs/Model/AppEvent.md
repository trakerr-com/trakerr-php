# AppEvent

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**api_key** | **string** | API key generated for the application | 
**classification** | **string** | one of &#39;debug&#39;,&#39;info&#39;,&#39;warning&#39;,&#39;error&#39; or a custom string | 
**event_type** | **string** | type or event or error (eg. NullPointerException) | 
**event_message** | **string** | message containing details of the event or error | 
**event_time** | **int** | (optional) event time in ms since epoch | [optional] 
**event_stacktrace** | [**\trakerr\client\model\Stacktrace**](Stacktrace.md) |  | [optional]
**event_user** | **string** | (optional) event user identifying a user | [optional] 
**event_session** | **string** | (optional) session identification | [optional] 
**context_app_version** | **string** | (optional) application version information | [optional] 
**context_env_name** | **string** | (optional) one of &#39;development&#39;,&#39;staging&#39;,&#39;production&#39; or a custom string | [optional] 
**context_env_version** | **string** | (optional) version of environment | [optional] 
**context_env_hostname** | **string** | (optional) hostname or ID of environment | [optional] 
**context_app_browser** | **string** | (optional) browser name if running in a browser (eg. Chrome) | [optional] 
**context_app_browser_version** | **string** | (optional) browser version if running in a browser | [optional] 
**context_app_os** | **string** | (optional) OS the application is running on | [optional] 
**context_app_os_version** | **string** | (optional) OS version the application is running on | [optional] 
**context_data_center** | **string** | (optional) Data center the application is running on or connected to | [optional] 
**context_data_center_region** | **string** | (optional) Data center region | [optional] 
**custom_properties** | [**\trakerr\client\model\CustomData**](CustomData.md) |  | [optional]
**custom_segments** | [**\trakerr\client\model\CustomData**](CustomData.md) |  | [optional]

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


