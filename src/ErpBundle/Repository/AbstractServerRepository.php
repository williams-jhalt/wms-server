<?php

namespace ErpBundle\Repository;

use ErpBundle\Service\ErpServerService;

abstract class AbstractServerRepository {

    /**
     * @var ErpServerService
     */
    protected $erp;

    public function __construct(ErpServerService $erp) {
        $this->erp = $erp;
    }
    
}