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

        $starter = $crawler->filter('#started-today tbody tr td')->first()->text();
        $starter = trim($starter);
        $this->assertEquals('SEN-111111', $starter);

        $expiringSoon = $crawler->filter('#expiring-soon tbody tr td')->first()->text();
        $expiringSoon = trim($expiringSoon);
        $this->assertEquals('SEN-222222', $expiringSoon);

        $topCustomer = $crawler->filter('#top-customers td')->first()->text();
        $topCustomer = trim($topCustomer);
        $this->assertEquals('SEN-222222', $topCustomer);
    }
}