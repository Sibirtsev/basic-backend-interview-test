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
}
