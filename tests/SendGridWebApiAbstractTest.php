<?php

namespace Tests\iDimensionz\SendGridWebApi;

use GuzzleHttp\Message\Response;
use iDimensionz\SendGridWebApi\SendGridWebApiAbstract;
//use Tests\iDimensionz\SendGridWebApi\ConcreteTestSendGridWebApi;
// @todo Fix autoloading so "use" can replace "require_once"
require_once 'ConcreteTestSendGridWebApi.php';

class SendGridWebApiAbstractTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ConcreteTestSendGridWebApi
     */
    private $sendGridWebApiAbstract;
    private $validApiUserName;
    private $validApiPassword;
    private $validBaseUrl;
    private $validModule;
    private $validAction;
    private $validFormat;
    private $validHttpResponse;
    private $mockHttpClient;

    public function setUp()
    {
        parent::setup();
        $this->validApiUserName = 'validApiUser';
        $this->validApiPassword = 'validApiPassword';
        $this->validBaseUrl = 'https://valid.base.url';
        $this->sendGridWebApiAbstract = $this->sendGridWebApiAbstract = new ConcreteTestSendGridWebApi(
            $this->validApiUserName,
            $this->validApiPassword,
            $this->validBaseUrl
        );
        $this->validModule = 'validmodule';
        $this->validAction = 'validaction';
        $this->validFormat = SendGridWebApiAbstract::SENDGRID_RESPONSE_FORMAT_JSON;
        $this->validHttpResponse = new Response(200);
    }

    public function tearDown()
    {
        unset($this->mockHttpClient);
        unset($this->validHttpResponse);
        unset($this->validFormat);
        unset($this->validAction);
        unset($this->validModule);
        unset($this->sendGridWebApiAbstract);
        unset($this->validBaseUrl);
        unset($this->validApiPassword);
        unset($this->validApiUserName);
        parent::tearDown();
    }

    public function testBaseUrlConstant()
    {
        $this->assertEquals(
            'https://api.sendgrid.com/api/',
            SendGridWebApiAbstract::SENDGRID_PRODUCTION_WEB_API_BASE_URL
        );
    }

    public function testConstructWhenBaseUrlIsSpecified()
    {
        $this->assertInstanceOf('iDimensionz\SendGridWebApi\SendGridWebApiAbstract', $this->sendGridWebApiAbstract);
        $this->assertProperties($this->validApiUserName, $this->validApiPassword, $this->validBaseUrl);
    }

    public function testConstructWhenBaseUrlIsDefault()
    {
        $this->sendGridWebApiAbstract = new ConcreteTestSendGridWebApi(
            $this->validApiUserName,
            $this->validApiPassword
        );
        $this->assertProperties(
            $this->validApiUserName,
            $this->validApiPassword,
            SendGridWebApiAbstract::SENDGRID_PRODUCTION_WEB_API_BASE_URL
        );
    }

    public function testCallApiFunctionWithDefaultFormatAndOneParameter()
    {
        $expectedUrl = $this->getExpectedUrl('&date=1');
        $this->hasMockHttpClient($expectedUrl);
        $this->hasSendGridWebApi();
        $this->sendGridWebApiAbstract->addParameter('date', 1);
        $actualResponse = $this->sendGridWebApiAbstract->callApiFunction();
        \Phake::verify($this->mockHttpClient, \Phake::times(1))->get($expectedUrl);
        $this->assertEquals($this->validHttpResponse, $actualResponse);
    }

    public function testCallApiFunctionWithDefaultFormatAndMultipleParameters()
    {
        $expectedUrl = $this->getExpectedUrl('&date=1&foo=bar');
        $this->hasMockHttpClient($expectedUrl);
        $this->hasSendGridWebApi();
        $this->sendGridWebApiAbstract->addParameter('date', 1);
        $this->sendGridWebApiAbstract->addParameter('foo', 'bar');
        $actualResponse = $this->sendGridWebApiAbstract->callApiFunction();
        \Phake::verify($this->mockHttpClient, \Phake::times(1))->get($expectedUrl);
        $this->assertEquals($this->validHttpResponse, $actualResponse);
    }

    private function assertProperties(
        $expectedUserName,
        $expectedPassword,
        $expectedBaseUrl,
        $expectedModule = '',
        $expectedFunction = '',
        $expectedFormat = SendGridWebApiAbstract::SENDGRID_RESPONSE_FORMAT_JSON
    ) {
        $this->assertEquals($expectedUserName, $this->sendGridWebApiAbstract->getApiUserName());
        $this->assertEquals($expectedPassword, $this->sendGridWebApiAbstract->getApiPassword());
        $this->assertEquals($expectedBaseUrl, $this->sendGridWebApiAbstract->getApiBaseUrl());
        $this->assertEquals($expectedModule, $this->sendGridWebApiAbstract->getModule());
        $this->assertEquals($expectedFunction, $this->sendGridWebApiAbstract->getAction());
        $this->assertEquals($expectedFormat, $this->sendGridWebApiAbstract->getFormat());
        $this->assertInstanceOf('\GuzzleHttp\Client', $this->sendGridWebApiAbstract->getHttpClient());
    }

    /**
     * @param $parameters
     * @return string
     */
    private function getExpectedUrl($parameters)
    {
        return SendGridWebApiAbstract::SENDGRID_PRODUCTION_WEB_API_BASE_URL .
        $this->validModule . '.' . $this->validAction . $this->validFormat . '?api_user=' . $this->validApiUserName .
        '&api_key=' . $this->validApiPassword . $parameters;
    }

    /**
     * @param string $expectedUrl
     */
    private function hasMockHttpClient($expectedUrl)
    {
        $this->mockHttpClient = \Phake::mock('\GuzzleHttp\Client');
        \Phake::when($this->mockHttpClient)->get($expectedUrl)
            ->thenReturn($this->validHttpResponse);
    }

    private function hasSendGridWebApi()
    {
        $this->sendGridWebApiAbstract = new ConcreteTestSendGridWebApi(
            $this->validApiUserName,
            $this->validApiPassword
        );
        $this->sendGridWebApiAbstract->setHttpClient($this->mockHttpClient);
        $this->sendGridWebApiAbstract->setModule($this->validModule);
        $this->sendGridWebApiAbstract->setAction($this->validAction);
    }
}
 