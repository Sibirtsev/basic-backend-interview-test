<?php

namespace NeoBundle\Tests\Service;

use NeoBundle\Service\NasaNeoService;
use PHPUnit\Framework\TestCase;


class NasaNeoServiceTest extends TestCase
{
    public function testCreate()
    {
        $mockHttp = $this->getGuzzleMock(
            'get',
            [],
            200,
            'response_full.json'
        );

        $service = new NasaNeoService($mockHttp, 'test', 'test');
        $this->assertTrue(is_object($service));
        $this->assertTrue($service instanceof NasaNeoService);
    }

    public function testFullAnswer()
    {
        $testCallArg = [
            'test',
            [
                'query' => [
                    'api_key' => 'test',
                    'start_date' => '2017-11-19',
                    'end_date' => '2017-11-21',
                    'detailed' => 'false',
                ]
            ]
        ];

        $mockHttp = $this->getGuzzleMock(
            'get',
            $testCallArg,
            200,
            'response_full.json'
        );

        $service = new NasaNeoService($mockHttp,'test', 'test');
        $result = $service->fetchData('2017-11-19', '2017-11-21');

        $this->assertTrue(is_array($result));
        $this->assertCount(34, $result);

        // Check fields are exists
        $this->assertArrayHasKey('date', $result[0]);
        $this->assertArrayHasKey('reference', $result[0]);
        $this->assertArrayHasKey('name', $result[0]);
        $this->assertArrayHasKey('speed', $result[0]);
        $this->assertArrayHasKey('is_hazardous', $result[0]);

        // Check fields
        $this->assertTrue(is_string($result[0]['date']));
        $this->assertEquals('2017-11-19', $result[0]['date']);

        $this->assertTrue(is_int($result[0]['reference']));
        $this->assertEquals(3734648, $result[0]['reference']);

        $this->assertTrue(is_string($result[0]['name']));
        $this->assertEquals('(2015 VF106)', $result[0]['name']);

        $this->assertTrue(is_float($result[0]['speed']));
        $this->assertEquals(35557.5333370378, $result[0]['speed']);

        $this->assertTrue(is_bool($result[0]['is_hazardous']));
        $this->assertEquals(false, $result[0]['is_hazardous']);
    }

    public function testMalformedAnswer()
    {
        $testCallArg = [
            'test',
            [
                'query' => [
                    'api_key' => 'test',
                    'start_date' => '2017-11-20',
                    'end_date' => '2017-11-20',
                    'detailed' => 'false',
                ]
            ]
        ];

        $mockHttp = $this->getGuzzleMock(
            'get',
            $testCallArg,
            200,
            'response_malformed.json'
        );

        $service = new NasaNeoService($mockHttp,'test', 'test');
        $result = $service->fetchData('2017-11-20', '2017-11-20');

        $this->assertTrue(is_array($result));
        $this->assertCount(1, $result);

        // Check fields are exists
        $this->assertArrayHasKey('date', $result[0]);
        $this->assertArrayHasKey('reference', $result[0]);
        $this->assertArrayHasKey('name', $result[0]);
        $this->assertArrayHasKey('speed', $result[0]);
        $this->assertArrayHasKey('is_hazardous', $result[0]);

        // Check fields
        $this->assertTrue(is_string($result[0]['date']));
        $this->assertEquals('2017-11-20', $result[0]['date']);

        $this->assertTrue(is_int($result[0]['reference']));
        $this->assertEquals(3469637, $result[0]['reference']);

        $this->assertTrue(is_string($result[0]['name']));
        $this->assertEquals('(2009 SP171)', $result[0]['name']);

        $this->assertTrue(is_float($result[0]['speed']));
        $this->assertEquals(49746.4091509666, $result[0]['speed']);

        $this->assertTrue(is_bool($result[0]['is_hazardous']));
        $this->assertEquals(false, $result[0]['is_hazardous']);
    }

    public function testWrongDates()
    {
        $mockHttp = $this->getGuzzleMock(
            'get',
            [],
            200,
            'response_full.json'
        );

        $service = new NasaNeoService($mockHttp, 'test', 'test');
        $result = $service->fetchData('2017-11-21', '2017-11-19');
        $this->assertTrue(is_array($result));
        $this->assertCount(0, $result);
        $this->assertEmpty($result);
    }


    public function testBadAnswer()
    {
        $testCallArg = [
            'test',
            [
                'query' => [
                    'api_key' => 'test',
                    'start_date' => '2017-11-19',
                    'end_date' => '2017-11-21',
                    'detailed' => 'false',
                ]
            ]
        ];

        $mockHttp = $this->getGuzzleMock(
            'get',
            $testCallArg,
            404,
            'response_full.json'
        );

        $service = new NasaNeoService($mockHttp,'test', 'test');

        $result = $service->fetchData('2017-11-19', '2017-11-21');
        $this->assertTrue(is_array($result));
        $this->assertCount(0, $result);
        $this->assertEmpty($result);
    }


    public function testEmptyAnswer()
    {
        $testCallArg = [
            'test',
            [
                'query' => [
                    'api_key' => 'test',
                    'start_date' => '2017-11-19',
                    'end_date' => '2017-11-21',
                    'detailed' => 'false',
                ]
            ]
        ];

        $mockHttp = $this->getGuzzleMock(
            'get',
            $testCallArg,
            200,
            'response_empty.json'
        );

        $service = new NasaNeoService($mockHttp, 'test', 'test');
        $result = $service->fetchData('2017-11-19', '2017-11-21');
        $this->assertTrue(is_array($result));
        $this->assertCount(0, $result);
        $this->assertEmpty($result);
    }

    public function testThreeDaysMethod()
    {
        $endDay = new \DateTimeImmutable('yesterday');
        $startDay = $endDay->sub(new \DateInterval('P2D'));

        $mockHttp = $this->getGuzzleMock(
            'get',
            [],
            200,
            'response_full.json'
        );

        $mock = $this->getMockBuilder('NeoBundle\Service\NasaNeoService')
            ->setConstructorArgs([$mockHttp, 'test', 'test'])
            ->setMethods(['fetchData'])
            ->getMock();

        $mock->expects($this->once())
            ->method('fetchData')
            ->with($startDay->format('Y-m-d'), $endDay->format('Y-m-d'));

        $mock->fetchLastThreeDays();
    }

    /**
     * @param string $requestMethod
     * @param array  $requestArguments
     * @param int    $responseStatusCode
     * @param string $responseBodyFilename
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getGuzzleMock(
        string $requestMethod,
        array $requestArguments,
        int $responseStatusCode,
        string $responseBodyFilename
    ): \PHPUnit_Framework_MockObject_MockObject
    {
        $mockHttp = $this->getMockBuilder('\GuzzleHttp\Client')
            ->disableOriginalConstructor()
            ->getMock();

        $responseMock = $this->getMockBuilder('\GuzzleHttp\Psr7\Response')
            ->getMock();
        $responseMock->expects($this->any())->method('getStatusCode')
            ->willReturn($responseStatusCode);
        $responseMock->expects($this->any())->method('getBody')
            ->willReturn(file_get_contents($responseBodyFilename, true));

        $mockHttp->expects($this->any())->method('__call')
            ->with($requestMethod, $requestArguments)
            ->willReturn($responseMock);
        return $mockHttp;
    }
}
