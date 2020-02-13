<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReportsControllerTest extends WebTestCase {

    public function testIndex() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/reports/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}
