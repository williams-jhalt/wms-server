<?php

namespace ErpBundle\Model;

use JMS\Serializer\Annotation as JMS;

class InvoiceItemCollection {

    /**
     * @JMS\Type("array<ErpBundle\Model\InvoiceItem>")
     * @var InvoiceItem[]
     */
    protected $items;
    
    /**
     * @param InvoiceItem[] $items
     */
    public function __construct(array $items) {
        $this->items = $items;
    }

    /**
     * 
     * @return InvoiceItem[]
     */
    function getItems() {
        return $this->items;
    }

    /**
     * 
     * @param InvoiceItem[] $items
     * 
     * @return InvoiceItem[]
     */
    function setItems(array $items) {
        $this->items = $items;
        return $this;
    }

}
