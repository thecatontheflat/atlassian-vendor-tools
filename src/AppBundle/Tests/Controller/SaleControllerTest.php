<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SaleControllerTest extends WebTestCase
{
    public function testIndexAction()
    {
        $client = $this->createClient();
        $client->request('GET', '/sales');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('375.00', $client->getResponse()->getContent());
        $this->assertContains('Planning Poker', $client->getResponse()->getContent());
    }
}