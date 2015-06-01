<?php

namespace Tests\iDimensionz\SendGridWebApi;

use iDimensionz\SendGridWebApi\SendGridWebApiAbstract;
//use Tests\iDimensionz\SendGridWebApi\ConcreteTestSendGridWebApi;
// @todo Fix autoloading so "use" can replace "require_once"
require_once 'ConcreteTestSendGridWebApi.php';

class SendGridWebApiAbstractTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SendGridWebApiAbstract
     */
    private $sendGridWebApiAbstract;
    private $validApiUserName;
    private $validApiPassword;
    private $validBaseUrl;

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
    }

    public function tearDown()
    {
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
        $this->assertInstanceOf('GuzzleHttp\Client', $this->sendGridWebApiAbstract->getHttpClient());
    }
}
 