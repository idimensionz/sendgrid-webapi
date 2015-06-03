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

    public function testDateSetterAndGetter()
    {
        $this->spamReports->setDate(1);
        $this->assertEquals(1, $this->spamReports->getDate());
        $this->spamReports->setDate(null);
        $this->assertEquals(null, $this->spamReports->getDate());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetDateThrowsExceptionWhenDateIsNotOne()
    {
        $this->spamReports->setDate(0);
    }

    public function testDaysSetterAndGetter()
    {
        $expectedDays = 5;
        $this->spamReports->setDays($expectedDays);
        $this->assertEquals($expectedDays, $this->spamReports->getDays());
        $expectedDays = null;
        $this->spamReports->setDays($expectedDays);
        $this->assertEquals($expectedDays, $this->spamReports->getDays());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetDaysThrowsExceptionWhenDateIsNotPositiveInt()
    {
        $invalidDays = 0;
        $this->spamReports->setDays($invalidDays);
    }

    public function testStartDateSetterAndGetter()
    {
        $validStartDate = '2015-06-01';
        $this->spamReports->setStartDate($validStartDate);
        $this->assertEquals($validStartDate, $this->spamReports->getStartDate());
        $validStartDate = null;
        $this->spamReports->setStartDate($validStartDate);
        $this->assertEquals($validStartDate, $this->spamReports->getStartDate());
    }

    /**
     * @expectedException \Exception
     */
    public function testSetStartDateThrowsExceptionWhenStartDateIsInvalid()
    {
        $invalidStartDate = 'invalid';
        $this->spamReports->setStartDate($invalidStartDate);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetStartDateThrowsExceptionWhenStartDateHasInvalidFormat()
    {
        $invalidStartDate = '2015-06-01 12:34:56';
        $this->spamReports->setStartDate($invalidStartDate);
    }

    public function testEndDateSetterAndGetter()
    {
        $validEndDate = '2015-06-01';
        $this->spamReports->setEndDate($validEndDate);
        $this->assertEquals($validEndDate, $this->spamReports->getEndDate());
        $validEndDate = null;
        $this->spamReports->setEndDate($validEndDate);
        $this->assertEquals($validEndDate, $this->spamReports->getEndDate());
    }

    /**
     * @expectedException \Exception
     */
    public function testSetEndDateThrowsExceptionWhenEndDateIsInvalid()
    {
        $invalidEndDate = 'invalid';
        $this->spamReports->setEndDate($invalidEndDate);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetEndDateThrowsExceptionWhenEndDateHasInvalidFormat()
    {
        $invalidEndDate = '2015-06-01 12:34:56';
        $this->spamReports->setEndDate($invalidEndDate);
    }

    public function testLimitSetterAndGetter()
    {
        $validLimit = 10;
        $this->spamReports->setLimit($validLimit);
        $this->assertEquals($validLimit, $this->spamReports->getLimit());
        $validLimit = null;
        $this->spamReports->setLimit($validLimit);
        $this->assertEquals($validLimit, $this->spamReports->getLimit());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetLimitThrowsExceptionWhenLimitIsNotPositiveInt()
    {
        $invalidLimit = -10;
        $this->spamReports->setLimit($invalidLimit);
    }

    public function testOffsetSetterAndGetter()
    {
        $validOffset = 10;
        $this->spamReports->setOffset($validOffset);
        $this->assertEquals($validOffset, $this->spamReports->getOffset());
        $validOffset = null;
        $this->spamReports->setOffset($validOffset);
        $this->assertEquals($validOffset, $this->spamReports->getOffset());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetOffsetThrowsExceptionWhenOffsetIsNotPositiveInteger()
    {
        $invalidOffset = -10;
        $this->spamReports->setOffset($invalidOffset);
    }

    public function testEmailSetterAndGetter()
    {
        $validEmail = 'me@test.com';
        $this->spamReports->setEmail($validEmail);
        $this->assertEquals($validEmail, $this->spamReports->getEmail());
    }
}
 