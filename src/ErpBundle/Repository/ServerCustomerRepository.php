<?php

namespace ErpBundle\Repository;

use ErpBundle\Model\Customer;
use ErpBundle\Model\CustomerCollection;

class ServerCustomerRepository extends AbstractServerRepository implements CustomerRepositoryInterface {

    /**
     * 
     * @param integer $limit
     * @param integer $offset
     * @return CustomerCollection
     */
    public function findAll($limit = 1000, $offset = 0) {

        $query = "FOR EACH customer NO-LOCK "
                . "WHERE customer.company_cu = '" . $this->erp->getCompany() . "'";

        $fields = "customer.customer,"
                . "customer.name";

        $response = $this->erp->read($query, $fields, $limit, $offset);

        $result = array();

        foreach ($response as $erpItem) {
            $item = new Customer();
            $item->setCustomerNumber($erpItem->customer_customer);
            $item->setName($erpItem->customer_name);
            $result[] = $item;
        }

        return new CustomerCollection($result);
    }

    /**
     * 
     * @param string $searchTerms
     * @param integer $limit
     * @param integer $offset
     * @return CustomerCollection
     */
    public function findByTextSearch($searchTerms, $limit = 1000, $offset = 0) {

        $query = "FOR EACH customer NO-LOCK "
                . "WHERE customer.company_cu = '{$this->erp->getCompany()}' "
                . "AND customer.sy_lookup MATCHES '*{$searchTerms}*'";

        $fields = "customer.customer,"
                . "customer.name";

        $response = $this->erp->read($query, $fields, $limit, $offset);

        $result = array();

        foreach ($response as $erpItem) {
            $item = new Customer();
            $item->setCustomerNumber($erpItem->customer_customer);
            $item->setName($erpItem->customer_name);
            $result[] = $item;
        }

        return new CustomerCollection($result);
    }

    /**
     * 
     * @param string $customerNumber
     * @return Customer
     */
    public function getByCustomerNumber($customerNumber) {

        $query = "FOR EACH customer NO-LOCK "
                . "WHERE customer.company_cu = '{$this->erp->getCompany()}' "
                . "AND customer.customer EQ '{$customerNumber}'";

        $fields = "customer.customer,"
                . "customer.name";

        $response = $this->erp->read($query, $fields, 1);

        if (sizeof($response) == 0) {
            return null;
        }

        $erpItem = $response[0];

        $item = new Customer();
        $item->setCustomerNumber($erpItem->customer_customer);
        $item->setName($erpItem->customer_name);

        return $item;
    }

}
