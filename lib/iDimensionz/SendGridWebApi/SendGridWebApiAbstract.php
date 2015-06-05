<?php
/*
 * This file is part of the iDimensionz/sendgrid-webapi package.
 *
 * Copyright (c) 2015 iDimensionz <info@idimensionz.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iDimensionz\SendGridWebApi;

use GuzzleHttp\Client;

abstract class SendGridWebApiAbstract
{
    const SENDGRID_PRODUCTION_WEB_API_BASE_URL = 'https://api.sendgrid.com/api/';

    const SENDGRID_RESPONSE_FORMAT_JSON = 'json';
    const SENDGRID_RESPONSE_FORMAT_XML = 'xml';

    const VALID_DATE_FORMAT = 'Y-m-d';

    /**
     * @var string
     */
    private $apiUserName;
    /**
     * @var string
     */
    private $apiPassword;
    /**
     * @var string
     */
    private $apiBaseUrl;
    /**
     * @var string  Each child class what module it is implementing.
     */
    private $module;
    /**
     * @var string  Each child class may implement several functions of the API
     */
    private $action;
    /**
     * @var string  Each function will request the response in a valid format.
     */
    private $format;
    /**
     * @var Client
     */
    private $httpClient;
    /**
     * @var array Parameters for the API call.
     */
    private $parameters;

    /**
     * @param string $apiUserName
     * @param string $apiPassword
     * @param string $webApiUrl
     */
    public function __construct($apiUserName, $apiPassword, $webApiUrl = self::SENDGRID_PRODUCTION_WEB_API_BASE_URL)
    {
        $this->setApiUserName($apiUserName);
        $this->setApiPassword($apiPassword);
        $this->setApiBaseUrl($webApiUrl);
        $this->setModule('');
        $this->setAction('');
        // Default response to JSON format
        $this->setFormat(self::SENDGRID_RESPONSE_FORMAT_JSON);
        $this->httpClient = null;
    }

    /**
     * Makes the actual call to the SendGrid Web API.
     * @return \GuzzleHttp\Message\FutureResponse|\GuzzleHttp\Message\ResponseInterface|\GuzzleHttp\Ring\Future\FutureInterface|null
     */
    public function callApiFunction()
    {
        $apiCallUrl = $this->assembleApiCall();
        $response = $this->getHttpClient()->get($apiCallUrl);

        return $response;
    }

    /**
     * @return string
     */
    private function assembleApiCall()
    {
        $url = $this->getApiBaseUrl() . $this->getModule() . '.' . $this->getAction() . '.' . $this->getFormat() . '?';
        $url .= 'api_user='.$this->getApiUserName() . '&api_key=' . $this->getApiPassword();
        $parameterStrings = [];
        if (is_array($this->parameters) && count($this->parameters) > 0) {
            foreach ($this->parameters as $key => $value) {
                $parameterStrings[] = "{$key}={$value}";
            }
            $url .= '&' . implode('&', $parameterStrings);
        }

        return $url;
    }

    /**
     * @param string $key
     * @param mixed $value  If value is null, the parameter will be unset.
     */
    protected function addParameter($key, $value)
    {
        if (!is_null($value)) {
            $this->parameters[$key] = $value;
        } else {
            unset($this->parameters[$key]);
        }
    }
    /**
     * @return string
     */
    public function getApiUserName()
    {
        return $this->apiUserName;
    }

    /**
     * @param string $apiUserName
     */
    public function setApiUserName($apiUserName)
    {
        $this->apiUserName = $apiUserName;
    }

    /**
     * @return string
     */
    public function getApiPassword()
    {
        return $this->apiPassword;
    }

    /**
     * @param string $apiPassword
     */
    public function setApiPassword($apiPassword)
    {
        $this->apiPassword = $apiPassword;
    }

    /**
     * @return string
     */
    public function getApiBaseUrl()
    {
        return $this->apiBaseUrl;
    }

    /**
     * @param string $apiUrl
     */
    public function setApiBaseUrl($apiUrl)
    {
        $this->apiBaseUrl = $apiUrl;
    }

    /**
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @param string $module
     */
    public function setModule($module)
    {
        $this->module = $module;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $function
     */
    public function setAction($function)
    {
        $this->action = $function;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * @return Client
     */
    public function getHttpClient()
    {
        if (is_null($this->httpClient)) {
            $this->setHttpClient(new Client());
        }

        return $this->httpClient;
    }

    /**
     * @param Client $httpClient
     */
    public function setHttpClient($httpClient)
    {
        $this->httpClient = $httpClient;
    }
}
 