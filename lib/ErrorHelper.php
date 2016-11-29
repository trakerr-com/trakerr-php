<?php

/**
 */
namespace trakerr;

use Exception;
use trakerr\client\model\InnerStackTrace;
use trakerr\client\model\Stacktrace;
use trakerr\client\model\StackTraceLine;
use trakerr\client\model\StackTraceLines;

class ErrorHelper
{
    private $trakerrClient;

    /*
     * Constructor that takes in the trakerr client to use.
     */
    public function __construct(\trakerr\TrakerrClient $trakerrClient)
    {
        $this->trakerrClient = $trakerrClient;
    }

    public function createAppEvent($classification, Exception $exc) {
        $appEvent = $this->trakerrClient->createAppEvent($classification, get_class($exc), $exc->getMessage());
        $appEvent->setEventStacktrace($this->createStacktrace(array(), $exc));
        return $appEvent;
    }

    private function createStacktrace($stacktrace, Exception $exc) {

        $innerStacktrace = new InnerStackTrace();
        $innerStacktrace->setMessage($exc->getMessage());
        $innerStacktrace->setType(get_class($exc));

        if(!$exc->getTrace()) {
            $stacktraceLines = array();

            $stacktraceLine = new StackTraceLine();
            $stacktraceLine->setFunction("main");
            $stacktraceLine->setLine($exc->getLine());
            $stacktraceLine->setFile($exc->getFile());
            $stacktraceLines[] = $stacktraceLine;

            $innerStacktrace->setTraceLines($stacktraceLines);

            $stacktrace[] = $innerStacktrace;
        } else {
            $stacktraceLines = array();

            foreach($exc->getTrace() as $item) {
                $stacktraceLine = new StackTraceLine();
                $function = isset($item["class"]) ? $item["class"] . "->" : "";
                $function = $function . $item["function"];
                $stacktraceLine->setFunction($function);
                if(isset($item["line"])) $stacktraceLine->setLine($item["line"]);
                if(isset($item["file"])) $stacktraceLine->setFile($item["file"]);
                $stacktraceLines[] = $stacktraceLine;

            }

            $innerStacktrace->setTraceLines($stacktraceLines);

            $stacktrace[] = $innerStacktrace;

            if($exc->getPrevious()) {
                $stacktrace = $this->createStacktrace($stacktrace, $exc->getPrevious());
            }
        }

        return $stacktrace;
    }

    public function onError($code, $message, $file, $line)
    {
        $exc = new Exception($message);

        switch ($code) {
            case E_NOTICE:
            case E_USER_NOTICE:
                $classification = "Info";
                break;
            case E_WARNING:
            case E_USER_WARNING:
                $classification = "Warning";
                break;
            case E_ERROR:
            case E_CORE_ERROR:
            case E_RECOVERABLE_ERROR:
                $classification = "Error";
                break;
            case E_USER_ERROR:
            default:
                $classification = "Fatal";
                break;
        }

        $appEvent = $this->buildAppEvent($classification, $exc);
        $this->trakerrClient->sendEvent($appEvent);
    }

    public function onException($exc)
    {
        $appEvent = $this->buildAppEvent("Error", $exc);
        $this->trakerrClient->sendEvent($appEvent);
    }

    public function onShutdown()
    {
        $error = error_get_last();
        if ($error === null) {
            return;
        }
        if ($error['type'] & error_reporting() === 0) {
            return;
        }

        $appEvent = $this->buildAppEvent("Fatal", new Exception($error['message']));
        $this->trakerrClient->sendEvent($appEvent);
    }

    public function register()
    {
        set_error_handler([$this, 'onError'], error_reporting());
        set_exception_handler([$this, 'onException']);
        register_shutdown_function([$this, 'onShutdown']);
    }
}
