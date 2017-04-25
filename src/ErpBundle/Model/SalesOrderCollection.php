<?php

namespace ErpBundle\Model;

use JMS\Serializer\Annotation as JMS;

class SalesOrderCollection {

    /**
     * @JMS\Type("array<ErpBundle\Model\SalesOrder>")
     * @var SalesOrder[]
     */
    protected $salesOrders;
    
    /**
     * @param SalesOrder[] $salesOrders
     */
    public function __construct(array $salesOrders) {
        $this->salesOrders = $salesOrders;
    }

    /**
     * 
     * @return SalesOrder[]
     */
    function getSalesOrders() {
        return $this->salesOrders;
    }

    /**
     * 
     * @param SalesOrder[] $salesOrders
     * @return SalesOrderCollection
     */
    function setSalesOrders(array $salesOrders) {
        $this->salesOrders = $salesOrders;
        return $this;
    }

}
