<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ScheduledEventControllerTest extends WebTestCase
{
    public function testIndexAction()
    {
        $client = $this->createClient();
        $client->request('GET', '/scheduled-events');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}