<?php

#require_once 'PHPUnit/Autoload.php';

class TrakerrClientTest extends \PHPUnit_Framework_TestCase
{
    protected static $tc;

    public static function setUpBeforeClass()
    {
        self::$tc = new \trakerr\TrakerrClient("898152e031aadc285c3d84aeeb3c1e386735434729425", "php", "CICD Tests");
    }

    public static function testSendException()
    {
        try {
            throw new Exception("test exception");
        } catch (Exception $e) {
            self::$tc->sendError($e, "fatal");
        }
    }

    public static function testSendEvent()
    {
        // Option-4: send any event programmatically
        $appEvent = self::$tc->createAppEvent("warning", "warning", "Warning", "Test message from php");
        $appEvent->setContextOperationTimeMillis(1000);
        $appEvent->setContextCpuPercentage(60);
        $appEvent->setContextMemoryPercentage(80);
        $appEvent->setContextDevice("pc");
        $appEvent->setContextAppSku("lenovo laptop");
        $appEvent->setContextTags(["client", "frontend"]);
        self::$tc->sendEvent($appEvent);

        try {
            throw new Exception("Too much math");
        } catch (Exception $e) {
            $appEvent2 = self::$tc->createAppEventFromException($e, "Error");

            // set some custom data
            $appEvent2->setContextOperationTimeMillis(1000);
            $appEvent2->setContextCpuPercentage(60);
            $appEvent2->setContextMemoryPercentage(80);
            $appEvent2->setContextDevice("pc");
            $appEvent2->setContextAppSku("lenovo laptop");
            $appEvent2->setContextTags(["client", "frontend"]);

            self::$tc->sendEvent($appEvent2);
        }
    }
}