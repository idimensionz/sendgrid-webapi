<?php

namespace Tests\iDimensionz\SendGridWebApi;

use iDimensionz\SendGridWebApi\SendGridWebApiAbstract;

//use Tests\iDimensionz\SendGridWebApi\ConcreteTestSendGridWebApi;
// @todo Fix autoloading so "use" can replace "require_once"
require_once 'ConcreteTestSendGridWebApi.php';
require_once 'AbstractTestBase.php';

class SendGridWebApiAbstractTest extends AbstractTestBase
{
    /**
     * @var ConcreteTestSendGridWebApi
     */
    private $sendGridWebApiAbstract;

    public function setUp()
    {
        parent::setup();
        $this->sendGridWebApiAbstract = $this->sendGridWebApiAbstract = new ConcreteTestSendGridWebApi(
            $this->validApiUserName,
            $this->validApiPassword,
            $this->validBaseUrl
        );
    }

    public function tearDown()
    {
        unset($this->sendGridWebApiAbstract);
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
        $expectedUrl = $this->getExpectedUrl(
            $this->validBaseUrl,
            '&date=1'
        );
        $this->hasMockHttpClient($expectedUrl);
        $this->hasSendGridWebApi();
        $this->sendGridWebApiAbstract->addParameter('date', 1);
        $actualResponse = $this->sendGridWebApiAbstract->callApiFunction();
        \Phake::verify($this->mockHttpClient, \Phake::times(2))->get($expectedUrl);
        $this->assertEquals($this->validHttpResponse, $actualResponse);
    }

    public function testCallApiFunctionWithDefaultFormatAndMultipleParameters()
    {
        $expectedUrl = $this->getExpectedUrl(
            $this->validBaseUrl,
            '&date=1&foo=bar'
        );
        $this->hasMockHttpClient($expectedUrl);
        $this->hasSendGridWebApi();
        $this->sendGridWebApiAbstract->addParameter('date', 1);
        $this->sendGridWebApiAbstract->addParameter('foo', 'bar');
        $actualResponse = $this->sendGridWebApiAbstract->callApiFunction();
        \Phake::verify($this->mockHttpClient, \Phake::times(2))->get($expectedUrl);
        $this->assertEquals($this->validHttpResponse, $actualResponse);
    }

    public function testCallApiFunctionwithDefaultFromatAndMultipleParametersSomeNull()
    {
        $expectedUrl = $this->getExpectedUrl(
            $this->validBaseUrl,
            '&date=1'
        );
        $this->hasMockHttpClient($expectedUrl);
        $this->hasSendGridWebApi();
        $this->sendGridWebApiAbstract->addParameter('date', 1);
        $this->sendGridWebApiAbstract->addParameter('foo', 'bar');
        $this->sendGridWebApiAbstract->addParameter('foo', null);
        $actualResponse = $this->sendGridWebApiAbstract->callApiFunction();
        \Phake::verify($this->mockHttpClient, \Phake::times(2))->get($expectedUrl);
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

    private function hasSendGridWebApi()
    {
        $this->sendGridWebApiAbstract = new ConcreteTestSendGridWebApi(
            $this->validApiUserName,
            $this->validApiPassword,
            $this->validBaseUrl
        );
        $this->sendGridWebApiAbstract->setHttpClient($this->mockHttpClient);
        $this->sendGridWebApiAbstract->setModule($this->validModule);
        $this->sendGridWebApiAbstract->setAction($this->validAction);
    }
}
 