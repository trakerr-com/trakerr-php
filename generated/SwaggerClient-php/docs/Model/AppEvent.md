# AppEvent

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**api_key** | **string** | API key generated for the application | 
**log_level** | **string** | (optional) Logging level, one of &#39;debug&#39;,&#39;info&#39;,&#39;warning&#39;,&#39;error&#39;, &#39;fatal&#39;, defaults to &#39;error&#39; | [optional] 
**classification** | **string** | (optional) one of &#39;issue&#39; or a custom string for non-issues, defaults to &#39;issue&#39; | 
**event_type** | **string** | type of the event or error (eg. NullPointerException) | 
**event_message** | **string** | message containing details of the event or error | 
**event_time** | **int** | (optional) event time in ms since epoch | [optional] 
**event_stacktrace** | [**\trakerr\client\model\Stacktrace**](Stacktrace.md) |  | [optional] 
**event_user** | **string** | (optional) event user identifying a user | [optional] 
**event_session** | **string** | (optional) session identification | [optional] 
**context_app_version** | **string** | (optional) application version information | [optional] 
**deployment_stage** | **string** | (optional) deployment stage, one of &#39;development&#39;,&#39;staging&#39;,&#39;production&#39; or a custom string | [optional] 
**context_env_name** | **string** | (optional) environment name (like &#39;cpython&#39; or &#39;ironpython&#39; etc.) | [optional] 
**context_env_language** | **string** | (optional) language (like &#39;python&#39; or &#39;c#&#39; etc.) | [optional] 
**context_env_version** | **string** | (optional) version of environment | [optional] 
**context_env_hostname** | **string** | (optional) hostname or ID of environment | [optional] 
**context_app_browser** | **string** | (optional) browser name if running in a browser (eg. Chrome) | [optional] 
**context_app_browser_version** | **string** | (optional) browser version if running in a browser | [optional] 
**context_app_os** | **string** | (optional) OS the application is running on | [optional] 
**context_app_os_version** | **string** | (optional) OS version the application is running on | [optional] 
**context_data_center** | **string** | (optional) Data center the application is running on or connected to | [optional] 
**context_data_center_region** | **string** | (optional) Data center region | [optional] 
**context_tags** | **string[]** |  | [optional] 
**context_url** | **string** | (optional) The full URL when running in a browser when the event was generated. | [optional] 
**context_operation_time_millis** | **int** | (optional) duration that this event took to occur in millis. Example - database call time in millis. | [optional] 
**context_cpu_percentage** | **int** | (optional) CPU utilization as a percent when event occured | [optional] 
**context_memory_percentage** | **int** | (optional) Memory utilization as a percent when event occured | [optional] 
**context_cross_app_correlation_id** | **string** | (optional) Cross application correlation ID | [optional] 
**context_device** | **string** | (optional) Device information | [optional] 
**context_app_sku** | **string** | (optional) Application SKU | [optional] 
**custom_properties** | [**\trakerr\client\model\CustomData**](CustomData.md) |  | [optional] 
**custom_segments** | [**\trakerr\client\model\CustomData**](CustomData.md) |  | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


