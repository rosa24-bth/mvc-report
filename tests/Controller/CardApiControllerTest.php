<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test cases for CardApiController.
 */
class CardApiControllerTest extends WebTestCase
{
    /**
     * Request the /api/deck route and check that a full deck is returned.
     */
    public function testApiDeckReturnsFullDeck()
    {
        $client = static::createClient();
        $client->request('GET', '/api/deck');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('count', $data);
        $this->assertSame(52, $data['count']);
    }

    /**
     * Request the /api/deck/shuffle route and check that a full deck is returned.
     */
    public function testApiShuffleReturnsShuffledDeck()
    {
        $client = static::createClient();
        $client->request('POST', '/api/deck/shuffle');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('count', $data);
        $this->assertSame(52, $data['count']);
    }

    /**
     * Request the /api/deck/draw route and check that a card is drawn.
     */
    public function testApiDrawReturnsOneCard()
    {
        $client = static::createClient();
        $client->request('GET', '/api/deck');
        $client->request('POST', '/api/deck/draw');

        $this->assertResponseIsSuccessful();

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('drawn', $data);
        $this->assertArrayHasKey('remaining', $data);
        $this->assertSame(51, $data['remaining']);
    }

    /**
     * Request to draw multiple cards and check count.
     */
    public function testApiDrawNumberReturnsCorrectCount()
    {
        $client = static::createClient();
        $client->request('GET', '/api/deck');
        $client->request('POST', '/api/deck/draw/3');

        $this->assertResponseIsSuccessful();

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('drawn', $data);
        $this->assertCount(3, $data['drawn']);
        $this->assertArrayHasKey('remaining', $data);
        $this->assertSame(49, $data['remaining']);
    }

    /**
     * Request to /api/game with no session should return error.
     */
    public function testApiGameReturnsErrorWithoutSession()
    {
        $client = static::createClient();
        $client->request('GET', '/api/game');

        $this->assertResponseIsSuccessful();

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('error', $data);
        $this->assertEquals('Spelet har inte startats ännu.', $data['error']);
    }
}
