<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SalesOrdersControllerTest extends WebTestCase {

    public function testIndex() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/orders/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testMfg() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/orders/MFG');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}
