<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PickerLogControllerTest extends WebTestCase {

    public function testIndex() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/picker-log/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testSinglePick() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/picker-log/single-pick');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}
