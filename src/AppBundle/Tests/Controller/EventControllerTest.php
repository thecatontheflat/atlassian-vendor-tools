<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EventControllerTest extends WebTestCase
{
    public function testIndexAction()
    {
        $client = $this->createClient();
        $client->request('GET', '/events');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testNewAction()
    {
        $client = $this->createClient();
        $client->request('GET', '/event/new');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}