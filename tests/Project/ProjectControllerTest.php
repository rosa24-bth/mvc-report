<?php

namespace App\Tests\Project;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests for ProjectController.
 */
class ProjectControllerTest extends WebTestCase
{
    public function testProjectIndexLoads()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/project');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains(
            'h2',
            'Välkommen till projektet om ekonomisk hållbar utveckling'
        );
    }

    public function testProjectAboutLoads()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/project/about');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Om projektet');
    }
}
