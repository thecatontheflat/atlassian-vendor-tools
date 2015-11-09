<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LicenseControllerTest extends WebTestCase
{
    public function testIndexAction()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/licenses');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $sen = $crawler->filter('table td')->first()->text();
        $this->assertEquals('SEN-3259293', trim($sen));
    }

    public function testDetailsAction()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/license/SEN-3259293');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //should be 3 tables: 1 per license, 1 for sales
        $tables = $crawler->filter('table')->count();
        $this->assertEquals(3, $tables);
    }
}