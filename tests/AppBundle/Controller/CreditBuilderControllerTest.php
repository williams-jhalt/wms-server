<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreditBuilderControllerTest extends WebTestCase {

    public function testIndex() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/credits/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}
