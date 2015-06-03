<?php

namespace Tests\iDimensionz\SendGridWebApi;

use iDimensionz\SendGridWebApi\SendGridWebApiAbstract;
use Guzzle\Http\Message\Response;

abstract class AbstractTestBase extends \PHPUnit_Framework_TestCase
{
    protected $validApiUserName;
    protected $validApiPassword;
    protected $validBaseUrl;
    protected $validModule;
    protected $validAction;
    protected $validFormat;
    protected $validHttpResponse;
    protected $mockHttpClient;

    public function setUp()
    {
        parent::setup();
        $this->validApiUserName = 'validApiUser';
        $this->validApiPassword = 'validApiPassword';
        $this->validBaseUrl = 'https://valid.base.url/';
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
        unset($this->validBaseUrl);
        unset($this->validApiPassword);
        unset($this->validApiUserName);
        parent::tearDown();
    }

    /**
     * @param string $expectedUrl
     */
    protected function hasMockHttpClient($expectedUrl)
    {
        $this->mockHttpClient = \Phake::mock('\GuzzleHttp\Client');
        \Phake::when($this->mockHttpClient)->get($expectedUrl)
            ->thenReturn($this->validHttpResponse);
        // Test it
        $actualResponse = $this->mockHttpClient->get($expectedUrl);
        $this->assertEquals($this->validHttpResponse, $actualResponse);
    }

    /**
     * @param string $baseUrl
     * @param string $parameters
     * @return string
     */
    protected function getExpectedUrl($baseUrl, $parameters)
    {
        return $baseUrl . $this->validModule . '.' . $this->validAction . '.' . $this->validFormat . '?api_user=' .
        $this->validApiUserName . '&api_key=' . $this->validApiPassword . $parameters;
    }
}
 