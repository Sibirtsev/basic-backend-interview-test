<?php

namespace NeoBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
        $response = $client->getResponse()->getContent();

        $this->assertJson($response);
        $this->assertJsonStringEqualsJsonString('{"hello":"world"}', $response);
    }

    public function testHazardous()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/neo/hazardous');
        $response = $client->getResponse()->getContent();

        $this->assertJson($response);
        $this->assertJsonStringNotEqualsJsonString('{"hello":"world"}', $response);
        $this->assertNotEmpty($response);

        $data = json_decode($response, true);

        $this->assertCount(7, $data);

        foreach ($data as $row) {
            $this->assertArrayHasKey('date', $row);
            $this->assertArrayHasKey('reference', $row);
            $this->assertArrayHasKey('name', $row);
            $this->assertArrayHasKey('speed', $row);
            $this->assertArrayHasKey('is_hazardous', $row);

            $this->assertTrue($row['is_hazardous']);
        }
    }


    public function testFastestNonHazardous()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/neo/fastest');
        $responseWithoutParam = $client->getResponse()->getContent();

        $crawler = $client->request('GET', '/neo/fastest?hazardous=false');
        $responseWithParam = $client->getResponse()->getContent();

        $crawler = $client->request('GET', '/neo/fastest?hazardous=hello');
        $responseWithWrongParam = $client->getResponse()->getContent();

        $this->assertEquals($responseWithoutParam, $responseWithParam);
        $this->assertEquals($responseWithoutParam, $responseWithWrongParam);

        $this->assertNotEmpty($responseWithoutParam);
        $this->assertJson($responseWithoutParam);

        $data = json_decode($responseWithoutParam, true);

        $this->assertArrayHasKey('date', $data);
        $this->assertArrayHasKey('reference', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('speed', $data);
        $this->assertArrayHasKey('is_hazardous', $data);

        $this->assertEquals('2017-11-20', (new \DateTime($data['date']))->format('Y-m-d'));
        $this->assertEquals(14, $data['reference']);
        $this->assertEquals('14', $data['name']);
        $this->assertEquals(38., $data['speed']);
        $this->assertFalse($data['is_hazardous']);
    }


    public function testFastestHazardous()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/neo/fastest');
        $responseWithoutParam = $client->getResponse()->getContent();

        $crawler = $client->request('GET', '/neo/fastest?hazardous=true');
        $responseWithParam = $client->getResponse()->getContent();

        $this->assertNotEquals($responseWithoutParam, $responseWithParam);

        $this->assertNotEmpty($responseWithParam);
        $this->assertJson($responseWithParam);

        $data = json_decode($responseWithParam, true);

        $this->assertArrayHasKey('date', $data);
        $this->assertArrayHasKey('reference', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('speed', $data);
        $this->assertArrayHasKey('is_hazardous', $data);

        $this->assertEquals('2017-03-20', (new \DateTime($data['date']))->format('Y-m-d'));
        $this->assertEquals(9, $data['reference']);
        $this->assertEquals('9', $data['name']);
        $this->assertEquals(51., $data['speed']);
        $this->assertTrue($data['is_hazardous']);
    }
}
