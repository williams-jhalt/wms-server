<?php

namespace ErpBundle\Model;

use JMS\Serializer\Annotation as JMS;

class SalesOrderItemCollection {

    /**
     * @JMS\Type("array<ErpBundle\Model\SalesOrderItem>")
     * @var SalesOrderItem[]
     */
    protected $items;
    
    /**
     * @param SalesOrderItem[] $items
     */
    public function __construct(array $items) {
        $this->items = $items;
    }

    /**
     * 
     * @return SalesOrderItem[]
     */
    function getItems() {
        return $this->items;
    }

    /**
     * 
     * @param SalesOrderItem[] $items
     * @return SalesOrderItemCollection
     */
    function setItems(array $items) {
        $this->items = $items;
    }

}
