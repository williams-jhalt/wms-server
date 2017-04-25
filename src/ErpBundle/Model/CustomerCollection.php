<?php

namespace ErpBundle\Model;

use JMS\Serializer\Annotation as JMS;

class CustomerCollection {

    /**
     * @JMS\Type("array<ErpBundle\Model\Customer>")
     * @var Customer[]
     */
    protected $customers;
    
    /**
     * @param Customer[] $customers
     */
    public function __construct(array $customers) {
        $this->customers = $customers;
    }

    /**
     * 
     * @return Customer[]
     */
    function getCustomers() {
        return $this->customers;
    }

    /**
     * 
     * @param Customer[] $customers
     */
    function setCustomers($customers) {
        $this->customers = $customers;
    }

}
