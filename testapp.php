<?php

require_once(__DIR__ . '/autoload.php');

$api_key = "your api key here";

if ($argc > 1 && $api_key === "your api key here"){
    $api_key = $argv[1];
}

// initialize the client
$trakerrClient = new \trakerr\TrakerrClient($api_key, "1.0", "development");

// Option-2: catch and send error to Trakerr programmatically
try {
    throw new Exception("test exception");
} catch (Exception $e) {
    $trakerrClient->sendError($e, "fatal");
}

 // Option-4: send any event programmatically
$appEvent = $trakerrClient->createAppEvent("warning", "type warn", "TestType", "Test message from php");

$trakerrClient->sendEvent($appEvent);

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
    $appEvent2->setContextOperationTimeMillis(1000);
    $appEvent2->setContextCpuPercentage(60);
    $appEvent2->setContextMemoryPercentage(80);
    $appEvent2->setContextDevice("pc");
    $appEvent2->setContextAppSku("mobile");
    $appEvent2->setContextTags(["client, frontend"]);

    $trakerrClient->sendEvent($appEvent2);
}

// Option-1: register global exception handlers (optional)
$trakerrClient->registerErrorHandlers();

throw new Exception("Not enough math");

