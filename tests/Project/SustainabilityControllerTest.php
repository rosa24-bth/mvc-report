<?php

namespace App\Tests\Project;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests for SustainabilityController.
 */
class SustainabilityControllerTest extends WebTestCase
{
    public function testIndexPageLoads()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/sustainability');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table.pivot-table');
    }

    public function testGraphsPageLoadsWithCanvases()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/sustainability/graphs');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('canvas#lowStandardChart');
        $this->assertSelectorExists('canvas#longSupportChart');
    }

    public function testApiDocumentationPageLoads()
    {
        $client = static::createClient();
        $client->request('GET', '/proj/api');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'API');
    }
}
