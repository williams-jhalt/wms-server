<?php

namespace ErpBundle\Repository;

use GuzzleHttp\Client;
use ErpBundle\Service\ErpClientService;

abstract class AbstractClientRepository {

    /**
     * @var ErpClientService
     */
    protected $erp;

    /**
     * 
     * @var Client
     */
    protected $client;

    public function __construct(ErpClientService $erp) {
        $this->erp = $erp;

        $this->client = new Client([
            'base_uri' => $erp->getHost(),
            'auth' => [$erp->getUsername(), $erp->getPassword()]
        ]);
    }

}
