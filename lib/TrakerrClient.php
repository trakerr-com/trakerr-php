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

require_once(__DIR__ . '/../autoload.php');

use Exception;
use trakerr\client\EventsApi;
use \trakerr\client\ApiClient;
use trakerr\client\model\AppEvent;
use trakerr\client\model\Error;

/**
 * EventsApi Class Doc Comment
 *
 * @category Class
 * @package  trakerr
 * @author   http://github.com/swagger-api/swagger-codegen
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link     https://github.com/swagger-api/swagger-codegen
 */
class TrakerrClient
{

    protected $eventsApi;
    protected $apiKey;
    protected $url;
    protected $contextAppVersion;
    protected $contextEnvName;
    protected $contextEnvVersion;
    protected $contextEnvHostname;
    protected $contextAppOS;
    protected $contextAppOSVersion;
    protected $contextAppBrowser;
    protected $contextAppBrowserVersion;
    protected $contextDataCenter;
    protected $contextDataCenterRegion;
    private $errorHelper;

    /**
     * TrakerrClient constructor.
     *
     * @param null $apiKey API Key for the application
     * @param string $contextAppVersion (optional) application version, defaults to 1.0
     * @param string $contextEnvName (optional) environment name like "development", "staging", "production" or a custom string
     */
    public function __construct($apiKey = null, $contextAppVersion = "1.0", $contextEnvName = "development")
    {
        $this->apiKey = $apiKey;
        $this->url = $url;
        $this->contextAppVersion = is_null($contextAppVersion) ? "1.0" : $contextAppVersion;
        $this->contextEnvName = is_null($contextEnvName) ? "development" : $contextEnvName;
        $this->contextEnvVersion = $contextEnvVersion;
        $this->contextEnvHostname = $contextEnvHostname;
        $this->contextAppOS = is_null($contextAppOS) ? php_uname("s") : $contextAppOS;
        $this->contextAppOSVersion = is_null($contextAppOSVersion) ? php_uname("v") : $contextAppOSVersion;
        $this->contextDataCenter = $contextDataCenter;
        $this->contextDataCenterRegion = $contextDataCenterRegion;

        $apiClient = new ApiClient();
        if (!is_null($url)) {
            $apiClient->getConfig()->setHost($url);
        }
        $this->eventsApi = new EventsApi($apiClient);
        $this->errorHelper = new ErrorHelper($this);
    }

    /**
     * @param string $classification classification like "Error", "Debug", "Warning" or "Info" or a custom string
     * @param string $eventType event type
     * @param string $eventMessage event message
     * @return mixed
     */
    public function createAppEvent($classification = "Error", $eventType = "unknown", $eventMessage = "unknown")
    {

        return $this->fillDefaults(new AppEvent(array("classification" => $classification, "event_type" => $eventType, "event_message" => $eventMessage)));
    }

    /**
     * Create an app event from the exception
     *
     * @param string $classification classification like "Error", "Debug", "Warning" or "Info" or a custom string
     * @param string $eventType event type
     * @param string $eventMessage event message
     * @return mixed
     */
    public function createAppEventFromException($classification = "Error", Exception $exc)
    {
        $appEvent = $this->errorHelper->createAppEvent($classification, $exc);
        return $this->fillDefaults($appEvent);
    }

    /**
     * Send the app event to Trakerr
     *
     * @param $appEvent app event to post
     */
    public function sendEvent($appEvent)
    {
        $data = $this->fillDefaults($appEvent);
        return $this->eventsApi->eventsPost($data);
    }

    /**
     * Send an exception to Trakerr
     *
     * @param $classification classification like "Error", "Warning", "Info" etc.
     * @param $exc exception
     */
    public function sendError($classification, Exception $exc)
    {
        $appEvent = $this->errorHelper->createAppEvent($classification, $exc);
        $data = $this->fillDefaults($appEvent);
        return $this->eventsApi->eventsPost($data);
    }

    /**
     * Register error handlers.
     */
    public function registerErrorHandlers()
    {
        $this->errorHelper->register();
    }

    private function fillDefaults(AppEvent $appEvent)
    {
        if (is_null($appEvent->getApiKey())) {
            $appEvent->setApiKey($this->apiKey);
        }

        if (is_null($appEvent->getContextAppVersion())) {
            $appEvent->setContextAppVersion($this->contextAppVersion);
        }

        if (is_null($appEvent->getContextEnvName())) {
            $appEvent->setContextEnvName($this->contextEnvName);
        }
        if (is_null($appEvent->getContextEnvVersion())) {
            $appEvent->setContextEnvVersion($this->contextEnvVersion);
        }
        if (is_null($appEvent->getContextEnvHostname())) {
            $appEvent->setContextEnvHostname($this->contextEnvHostname);
        }

        if (is_null($appEvent->getContextAppOS())) {
            $appEvent->setContextAppOS($this->contextAppOS);
            $appEvent->setContextAppOSVersion($this->contextAppOSVersion);
        }

        if (is_null($appEvent->getContextDataCenter())) {
            $appEvent->setContextDataCenter($this->contextDataCenter);
        }
        if (is_null($appEvent->getContextDataCenterRegion())) {
            $appEvent->setContextDataCenterRegion($this->contextDataCenterRegion);
        }

        if (is_null($appEvent->getEventTime())) {
            $appEvent->setEventTime($this->millitime());
        }
        return $appEvent;
    }

    private function millitime()
    {
        $microtime = microtime();
        $comps = explode(' ', $microtime);

        // Note: Using a string here to prevent loss of precision
        // in case of "overflow" (PHP converts it to a double)
        return sprintf('%d%03d', $comps[1], $comps[0] * 1000);
    }

    //Accessor list:

    public function set_apikey($apikey)
    {
        if (!is_string($apikey)) {
            throw new InvalidArgumentException('tripleInteger function only accepts integers.');
        }
        $this->apiKey = $apikey;
    }

    public function get_apikey()
    {
        return $this->apiKey;
    }

    public function set_contextAppVersion($contextappversion)
    {
        if (!is_string($contextappversion)) {
            throw new InvalidArgumentException('Function only accepts strings.');
        }
        $this->contextAppVersion = $contextappversion;
    }

    public function get_contextAppVersion()
    {
        return $this->contextAppVersion;
    }

    public function set_contextEnvName($contextenvname)
    {
        if (!is_string($contextenvname)) {
            throw new InvalidArgumentException('Function only accepts strings.');
        }
        $this->contextEnvName = $contextenvname;
    }

    public function get_contextEnvName()
    {
        return $this->contextEnvName;
    }

    public function set_contextEnvVersion($contextenvversion)
    {
        if (!is_string($contextenvversion)) {
            throw new InvalidArgumentException('Function only accepts strings.');
        }
        $this->contextEnvVersion = $contextenvversion;
    }

    public function get_contextEnvVersion()
    {
        return $this->contextEnvVersion;
    }

    public function set_contextEnvHostname($contextenvhostname)
    {
        if (!is_string($contextenvhostname)) {
            throw new InvalidArgumentException('Function only accepts strings.');
        }
        $this->contextEnvHostname = $contextenvhostname;
    }

    public function get_contextEnvHostname()
    {
        return $this->contextEnvHostname;
    }

    public function set_contextAppOS($contextappos)
    {
        if (!is_string($contextappos)) {
            throw new InvalidArgumentException('Function only accepts strings.');
        }
        $this->contextAppOS = $contextappos;
    }

    public function get_contextAppOS()
    {
        return $this->contextAppOS;
    }

    public function set_contextAppOSVersion($contextapposversion)
    {
        if (!is_string($contextapposversion)) {
            throw new InvalidArgumentException('Function only accepts strings.');
        }
        $this->contextAppOSVersion = $contextapposversion;
    }

    public function get_contextAppOSVersion()
    {
        return $this->contextAppOSVersion;
    }

    public function set_contextAppBrowser($contextappbrowser)
    {
        if (!is_string($contextappbrowser)) {
            throw new InvalidArgumentException('Function only accepts strings.');
        }
        $this->contextAppBrowser = $contextappbrowser;
    }

    public function get_contextAppBrowser()
    {
        return $this->contextAppBrowser;
    }

    public function set_contextAppBrowserVersion($contextappbrowserversion)
    {
        if (!is_string($contextappbrowserversion)) {
            throw new InvalidArgumentException('Function only accepts strings.');
        }
        $this->contextAppBrowserVersion = $contextappbrowserversion;
    }

    public function get_contextAppBrowserVersion()
    {
        return $this->contextAppBrowserVersion;
    }

    public function set_contextDataCenter($contextdatacenter)
    {
        if (!is_string($contextdatacenter)) {
            throw new InvalidArgumentException('Function only accepts strings.');
        }
        $this->contextDataCenter = $contextdatacenter;
    }

    public function get_contextDataCenter()
    {
        return $this->contextDataCenter;
    }

    public function set_contextDataCenterRegion($contextdatacenterregion)
    {
        if (!is_string($contextdatacenterregion)) {
            throw new InvalidArgumentException('Function only accepts strings.');
        }
        $this->contextDataCenterRegion = $contextdatacenterregion;
    }

    public function get_contextDataCenterRegion()
    {
        return $this->contextDataCenterRegion;
    }
}
