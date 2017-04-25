<?php

namespace ErpBundle\Repository;

use ErpBundle\Model\Customer;
use ErpBundle\Model\CustomerCollection;

interface CustomerRepositoryInterface {

    /**
     * 
     * @param integer $limit
     * @param integer $offset
     * 
     * @return CustomerCollection
     */
    public function findAll($limit = 1000, $offset = 0);
    
    /**
     * 
     * @param string $searchTerms
     * @param integer $limit
     * @param integer $offset
     * 
     * @return CustomerCollection
     */
    public function findByTextSearch($searchTerms, $limit = 1000, $offset = 0);
    
    /**
     * 
     * @param string $customerNumber
     * 
     * @return Customer
     */
    public function getByCustomerNumber($customerNumber);

}
