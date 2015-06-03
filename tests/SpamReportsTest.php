<?php

namespace Tests\iDimensionz\SendGridWebApi;

use iDimensionz\SendGridWebApi\SpamReports;

require_once 'AbstractTestBase.php';

class SpamReportsTest extends AbstractTestBase
{
    /**
     * @var SpamReports
     */
    private $spamReports;

    public function setUp()
    {
        parent::setUp();
        $this->spamReports = new SpamReports($this->validApiUserName, $this->validApiPassword, $this->validBaseUrl);
        $this->validModule = 'spamreports';
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function testConstructor()
    {
        $expectedModule = 'spamreports';
        $this->assertInstanceOf('\iDimensionz\SendGridWebApi\SpamReports', $this->spamReports);
        $this->assertEquals($this->validApiUserName, $this->spamReports->getApiUserName());
        $this->assertEquals($this->validApiPassword, $this->spamReports->getApiPassword());
        $this->assertEquals($this->validBaseUrl, $this->spamReports->getApiBaseUrl());
        $this->assertEquals($expectedModule, $this->spamReports->getModule());

    }

    public function testGetWithNoParametersSpecified()
    {
        $this->validAction = 'get';
        $expectedUrl = $this->getExpectedUrl($this->validBaseUrl, '&date=1');
        $this->hasMockHttpClient($expectedUrl);
        $this->spamReports->setHttpClient($this->mockHttpClient);
        $actualResponse = $this->spamReports->get();
        \Phake::verify($this->mockHttpClient, \Phake::times(2))->get($expectedUrl);
        $this->assertInstanceOf('\Guzzle\Http\Message\Response', $actualResponse);
        $this->assertEquals(200, $actualResponse->getStatusCode());
    }

    public function testGetWithAllParametersSpecified()
    {
        $this->validAction = 'get';
        $expectedUrl = $this->getExpectedUrl(
            $this->validBaseUrl,
            '&date=1&days=20&start_date=2015-06-01&end_date=2015-06-15&limit=10&offset=5&email=me@test.com'
        );
        $this->hasMockHttpClient($expectedUrl);
        $this->spamReports->setHttpClient($this->mockHttpClient);
        $actualResponse = $this->spamReports->get(1, 20, '2015-06-01', '2015-06-15', 10, 5, 'me@test.com');
        \Phake::verify($this->mockHttpClient, \Phake::times(2))->get($expectedUrl);
        $this->assertInstanceOf('\Guzzle\Http\Message\Response', $actualResponse);
        $this->assertEquals(200, $actualResponse->getStatusCode());
    }

    public function testCount()
    {
        $this->validAction = 'count';
        $expectedUrl = $this->getExpectedUrl($this->validBaseUrl, '');
        $this->hasMockHttpClient($expectedUrl);
        $this->spamReports->setHttpClient($this->mockHttpClient);
        $actualResponse = $this->spamReports->count();
        \Phake::verify($this->mockHttpClient, \Phake::times(2))->get($expectedUrl);
        $this->assertInstanceOf('\Guzzle\Http\Message\Response', $actualResponse);
        $this->assertEquals(200, $actualResponse->getStatusCode());
    }
}
 