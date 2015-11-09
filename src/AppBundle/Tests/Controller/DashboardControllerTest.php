<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DashboardControllerTest extends WebTestCase
{
    public function testHomepageAndWidgets()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $topCustomer = $crawler->filter('#top-customers td')->first()->text();
        $topCustomer = trim($topCustomer);
        $this->assertEquals('SEN-3259293', $topCustomer);
    }
}