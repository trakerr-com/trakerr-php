<?php

/**
 * TrakerrClientsAPI
 * PHP version 5
 *
 * @category Class
 * @package  trakerr
 * @author   dev@trakerr.io
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link     https://github.com/trakerr-io/trakerr-php
 */

/**
 * Trakerr Client API
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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

    /**
     * ErrorHelper constructor.
     *
     * @param $trakerrClient TrakerrClient being used by the class.
     */
    public function __construct(\trakerr\TrakerrClient $trakerrClient)
    {
        $this->trakerrClient = $trakerrClient;
    }

    /**
     *Creates the AppEvent and parses the stacktrace.
     *
     * @param $classification String representation the level of the error.
     * @param $exc Exception to be parsed.
     * @return AppEvent instance which has the StackTrace, classification, name, and message set.
     */
    public function createAppEvent(Exception $exc, $log_level="error", $classification="issue")
    {
        $appEvent = $this->trakerrClient->createAppEvent($log_level, $classification, get_class($exc), $exc->getMessage());
        $appEvent->setEventStacktrace($this->createStacktrace(array(), $exc));
        return $appEvent;
    }

    /**
     *Creates and returns a serializable representation of the error's stacktrace.
     *
     * @param $stacktrace StackTrace object to populate.
     * @param $exc Exception to be parsed.
     * @return An array of InnerStackTrace objects (a StackTrace object).
     */
    private function createStacktrace($stacktrace, Exception $exc)
    {

        $innerStacktrace = new InnerStackTrace();
        $innerStacktrace->setMessage($exc->getMessage());
        $innerStacktrace->setType(get_class($exc));

        if (!$exc->getTrace()) {
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

            foreach ($exc->getTrace() as $item) {
                $stacktraceLine = new StackTraceLine();
                $function = isset($item["class"]) ? $item["class"] . "->" : "";
                $function = $function . $item["function"];
                $stacktraceLine->setFunction($function);
                if (isset($item["line"])) {
                    $stacktraceLine->setLine($item["line"]);
                }
                if (isset($item["file"])) {
                    $stacktraceLine->setFile($item["file"]);
                }
                $stacktraceLines[] = $stacktraceLine;
            }

            $innerStacktrace->setTraceLines($stacktraceLines);

            $stacktrace[] = $innerStacktrace;

            if ($exc->getPrevious()) {
                $stacktrace = $this->createStacktrace($stacktrace, $exc->getPrevious());
            }
        }

        return $stacktrace;
    }

    /**
     *Error handler that gets called during a runtime interrupt.
     *
     * @param $code Error level enum.
     * @param $message Message of the exception.
     * @param $file Codefile that threw the error.
     * @param $line Line number of the throwing code.
     */
    public function onError($code, $message, $file, $line)
    {
        $exc = new Exception($message);

        switch ($code) {
            case E_NOTICE:
            case E_USER_NOTICE:
                $stage = "Info";
                break;
            case E_WARNING:
            case E_USER_WARNING:
                $stage = "Warning";
                break;
            case E_ERROR:
            case E_CORE_ERROR:
            case E_RECOVERABLE_ERROR:
                $stage = "Error";
                break;
            case E_USER_ERROR:
            default:
                $stage = "Fatal";
                break;
        }

        $appEvent = $this->createAppEvent($exc, $stage);
        $this->trakerrClient->sendEvent($appEvent);
    }

     /**
     *Error handler that gets called during an exception interrupt.
     *
     * @param $exc Exception that triggered the event.
     */
    public function onException($exc)
    {
        $appEvent = $this->createAppEvent($exc, "Error");
        $this->trakerrClient->sendEvent($appEvent);
    }

    /**
     *Error handler that gets called during program shutdown.
     */
    public function onShutdown()
    {
        $error = error_get_last();
        if ($error === null) {
            return;
        }
        if ($error['type'] & error_reporting() === 0) {
            return;
        }

        $appEvent = $this->createAppEvent(new Exception($error['message']), "Fatal");
        $this->trakerrClient->sendEvent($appEvent);
    }

     /**
     *Registers 'on' fuctions to their proper capture states with the program.
     */
    public function register()
    {
        set_error_handler([$this, 'onError'], error_reporting());
        set_exception_handler([$this, 'onException']);
        register_shutdown_function([$this, 'onShutdown']);
    }
}
