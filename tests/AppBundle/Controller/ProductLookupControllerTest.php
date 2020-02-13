<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductLookupControllerTest extends WebTestCase {

    public function testIndex() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/product-lookup/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    public function testCommitted() {
//        $client = static::createClient();
//        $crawler = $client->request('GET', '/product-lookup/committed');
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}
