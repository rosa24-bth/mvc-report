<?php

namespace App\Tests\Project;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests for ProjectApiController.
 */
class ProjectApiControllerTest extends WebTestCase
{
    public function testLowSamtligaReturnsJson(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/low/group/samtliga');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
    }

    public function testLongSamtligaReturnsJson(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/long/group/samtliga');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
    }

    public function testLowByGroupPostWithValidGroup(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/low/group',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['groupName' => 'Samtliga personer'])
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
    }

    public function testLowByGroupPostWithMissingGroup(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/low/group',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([])
        );

        $this->assertResponseStatusCodeSame(400);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $data);
    }

    public function testLowAllGroupsReturnsSortedList(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/low/groups');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $groups = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($groups);
        $this->assertNotEmpty($groups);
        $this->assertSame($groups, array_values(array_unique($groups)));
    }

    public function testLowWomenNoChildrenReturnsJson(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/low/group/ensamstaende-kvinnor-utan-barn');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
    }

    public function testLongEjBoendeReturnsJson(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/long/group/ej-boende-med-foralder');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
    }

    public function testLongChildrenOfSingleWomenReturnsJson(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/long/group/barn-till-ensamstaende-kvinnor');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
    }

    public function testLowByGroupPostWithUnknownGroup(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/low/group',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['groupName' => 'Grupp som inte finns'])
        );

        $this->assertResponseStatusCodeSame(404);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $data);
        $this->assertSame('Ingen data hittades för angiven grupp.', $data['error']);
    }
}
