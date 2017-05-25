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

    private $eventsApi;
    private $apiKey;
    private $contextAppVersion;
    private $contextDeploymentStage;
    private $contextEnvLanguage;
    private $contextEnvName;
    private $contextEnvVersion;
    private $contextEnvHostname;
    private $contextAppOS;
    private $contextAppOSVersion;
    private $contextAppBrowser;
    private $contextAppBrowserVersion;
    private $contextDataCenter;
    private $contextDataCenterRegion;
    private $errorHelper;

    /**
     * TrakerrClient constructor.
     *
     * @param null $apiKey API Key for the application
     * @param string $contextAppVersion (optional) application version, defaults to 1.0
     * @param string $contextEnvName (optional) environment name like "development", "staging", "production" or a custom string
     */
    public function __construct($apiKey, $contextAppVersion = "1.0", $contextDeploymentStage = "development")
    {
        $this->apiKey = $apiKey;
        $this->contextAppVersion = is_null($contextAppVersion) ? "1.0" : $contextAppVersion;
        $this->contextDeploymentStage = is_null($contextDeploymentStage) ? "development" : $contextDeploymentStage;
        $this->contextEnvLangugage = "php";
        $this->contextEnvName = "php";
        $this->contextEnvVersion = PHP_VERSION_ID;
        $this->contextEnvHostname = gethostname();
        $this->contextAppOS = php_uname("s");
        $this->contextAppOSVersion = php_uname("v");
        $this->contextAppBrowser = NULL;
        $this->contextAppBrowserVersion = NULL;
        $this->contextDataCenter = NULL;
        $this->contextDataCenterRegion = NULL;

        $apiClient = new ApiClient();
        $this->eventsApi = new EventsApi($apiClient);
        $this->errorHelper = new ErrorHelper($this);
    }

    /**
     * @param string $classification classification like "Error", "Debug", "Warning" or "Info" or a custom string
     * @param string $eventType event type
     * @param string $eventMessage event message
     * @return mixed
     */
    public function createAppEvent($log_level = "error", $classification = "issue", $eventType = "unknown", $eventMessage = "unknown")
    {

        return $this->fillDefaults(new AppEvent(array("log_level" => $log_level, "classification" => $classification, "event_type" => $eventType, "event_message" => $eventMessage)));
    }

    /**
     * Create an app event from the exception
     *
     * @param string $classification classification like "Error", "Debug", "Warning" or "Info" or a custom string
     * @param string $eventType event type
     * @param string $eventMessage event message
     * @return mixed
     */
    public function createAppEventFromException(Exception $exc, $log_level = "error", $classification = "issue")
    {
        $appEvent = $this->errorHelper->createAppEvent($exc, $log_level, $classification);
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
    public function sendError(Exception $exc, $log_level = "error", $classification = "issue")
    {
        $appEvent = $this->errorHelper->createAppEvent($exc, $log_level, $classification);
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
            $appEvent->setApiKey($this->getApiKey());
        }

        if (is_null($appEvent->getContextAppVersion())) {
            $appEvent->setContextAppVersion($this->getContextAppVersion());
        }

        if (is_null($appEvent->getDeploymentStage())){
            $appEvent->setDeploymentStage($this->getContextDeploymentStage());
        }

        if (is_null($appEvent->getContextEnvLanguage())){
            $appEvent->setContextEnvLanguage($this->getContextEnvLanguage());
        }

        if (is_null($appEvent->getContextEnvName())) {
            $appEvent->setContextEnvName($this->getContextEnvName());
        }

        if (is_null($appEvent->getContextEnvVersion())) {
            $appEvent->setContextEnvVersion($this->getContextEnvVersion());
        }

        if (is_null($appEvent->getContextEnvHostname())) {
            $appEvent->setContextEnvHostname($this->getContextEnvHostname());
        }

        if (is_null($appEvent->getContextAppOS())) {
            $appEvent->setContextAppOS($this->getContextAppOS());
            $appEvent->setContextAppOSVersion($this->getContextAppOSVersion());
        }

        if (is_null($appEvent->getContextAppBrowser())){
            $appEvent->setContextAppBrowser($this->getContextAppBrowser());
        }

        if (is_null($appEvent->getContextAppBrowserVersion())){
            $appEvent->setContextAppBrowserVersion($this->getContextAppBrowserVersion());
        }

        if (is_null($appEvent->getContextDataCenter())) {
            $appEvent->setContextDataCenter($this->getContextDataCenter());
        }
        if (is_null($appEvent->getContextDataCenterRegion())) {
            $appEvent->setContextDataCenterRegion($this->getContextDataCenterRegion());
        }

        if (is_null($appEvent->getEventTime())) {
            $appEvent->setEventTime((int)$this->millitime());
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

    public function setApiKey($apikey)
    {
        if (!is_string($apikey)) {
            throw new \InvalidArgumentException('tripleInteger function only accepts integers.');
        }
        $this->apiKey = $apikey;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function setContextAppVersion($contextappversion)
    {
        if (!is_string($contextappversion)) {
            throw new \InvalidArgumentException('Function only accepts strings.');
        }
        $this->contextAppVersion = $contextappversion;
    }

    public function setContextDeploymentStage($contextdeploymentstage)
    {
        if (!is_string($contextdeploymentstage)) {
            throw new \InvalidArgumentException('Function only accepts strings.');
        }
        $this->contextDeploymentStage = $contextdeploymentstage;
    }

    public function getContextDeploymentStage()
    {
        return $this->contextDeploymentStage;
    }

    public function getContextAppVersion()
    {
        return $this->contextAppVersion;
    }

    public function setContextEnvLanguage($contextenvlanguage)
    {
        if (!is_string($contextenvlanguage)) {
            throw new \InvalidArgumentException('Function only accepts strings.');
        }
        $this->contextEnvLanguage = $contextenvlanguage;
    }

    public function getContextEnvLanguage()
    {
        return $this->contextEnvLanguage;
    }

    public function setContextEnvName($contextenvname)
    {
        if (!is_string($contextenvname)) {
            throw new \InvalidArgumentException('Function only accepts strings.');
        }
        $this->contextEnvName = $contextenvname;
    }

    public function getContextEnvName()
    {
        return $this->contextEnvName;
    }

    public function setContextEnvVersion($contextenvversion)
    {
        if (!is_string($contextenvversion)) {
            throw new \InvalidArgumentException('Function only accepts strings.');
        }
        $this->contextEnvVersion = $contextenvversion;
    }

    public function getContextEnvVersion()
    {
        return $this->contextEnvVersion;
    }

    public function setContextEnvHostname($contextenvhostname)
    {
        if (!is_string($contextenvhostname)) {
            throw new \InvalidArgumentException('Function only accepts strings.');
        }
        $this->contextEnvHostname = $contextenvhostname;
    }

    public function getContextEnvHostname()
    {
        return $this->contextEnvHostname;
    }

    public function setContextAppOS($contextappos)
    {
        if (!is_string($contextappos)) {
            throw new \InvalidArgumentException('Function only accepts strings.');
        }
        $this->contextAppOS = $contextappos;
    }

    public function getContextAppOS()
    {
        return $this->contextAppOS;
    }

    public function setContextAppOSVersion($contextapposversion)
    {
        if (!is_string($contextapposversion)) {
            throw new \InvalidArgumentException('Function only accepts strings.');
        }
        $this->contextAppOSVersion = $contextapposversion;
    }

    public function getContextAppOSVersion()
    {
        return $this->contextAppOSVersion;
    }

    public function setContextAppBrowser($contextappbrowser)
    {
        if (!is_string($contextappbrowser)) {
            throw new \InvalidArgumentException('Function only accepts strings.');
        }
        $this->contextAppBrowser = $contextappbrowser;
    }

    public function getContextAppBrowser()
    {
        return $this->contextAppBrowser;
    }

    public function setContextAppBrowserVersion($contextappbrowserversion)
    {
        if (!is_string($contextappbrowserversion)) {
            throw new \InvalidArgumentException('Function only accepts strings.');
        }
        $this->contextAppBrowserVersion = $contextappbrowserversion;
    }

    public function getContextAppBrowserVersion()
    {
        return $this->contextAppBrowserVersion;
    }

    public function setContextDataCenter($contextdatacenter)
    {
        if (!is_string($contextdatacenter)) {
            throw new \InvalidArgumentException('Function only accepts strings.');
        }
        $this->contextDataCenter = $contextdatacenter;
    }

    public function getContextDataCenter()
    {
        return $this->contextDataCenter;
    }

    public function setContextDataCenterRegion($contextdatacenterregion)
    {
        if (!is_string($contextdatacenterregion)) {
            throw new \InvalidArgumentException('Function only accepts strings.');
        }
        $this->contextDataCenterRegion = $contextdatacenterregion;
    }

    public function getContextDataCenterRegion()
    {
        return $this->contextDataCenterRegion;
    }
}
