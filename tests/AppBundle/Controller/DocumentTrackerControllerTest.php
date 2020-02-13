<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DocumentTrackerControllerTest extends WebTestCase {

    public function testIndex() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/document-tracker/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}
