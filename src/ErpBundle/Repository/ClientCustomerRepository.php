<?php

namespace ErpBundle\Repository;

use ErpBundle\Model\Customer;
use ErpBundle\Model\CustomerCollection;

class ClientCustomerRepository extends AbstractClientRepository implements CustomerRepositoryInterface {

    /**
     * 
     * @param integer $limit
     * @param integer $offset
     * @return CustomerCollection
     */
    public function findAll($limit = 1000, $offset = 0) {

        $format = 'json';

        $response = $this->client->get("customers.{$format}", ['query' => ['limit' => $limit, 'offset' => $offset]]);

        $data = $response->getBody();

        $serializer = $this->erp->getSerializer();

        $result = $serializer->deserialize($data, 'ErpBundle\Model\CustomerCollection', $format);

        return $result;
    }

    /**
     * 
     * @param string $searchTerms
     * @param integer $limit
     * @param integer $offset
     * @return CustomerCollection
     */
    public function findByTextSearch($searchTerms, $limit = 1000, $offset = 0) {

        $format = 'json';

        $response = $this->client->get("customers.{$format}", ['query' => ['limit' => $limit, 'offset' => $offset, 'search' => $searchTerms]]);

        $data = $response->getBody();

        $serializer = $this->erp->getSerializer();

        $result = $serializer->deserialize($data, 'ErpBundle\Model\CustomerCollection', $format);

        return $result;
    }

    /**
     * 
     * @param string $customerNumber
     * @return Customer
     */
    public function getByCustomerNumber($customerNumber) {

        $format = 'json';

        $response = $this->client->get("customers/{$customerNumber}.{$format}");

        $data = $response->getBody();

        $serializer = $this->erp->getSerializer();

        $result = $serializer->deserialize($data, 'ErpBundle\Model\Customer', $format);

        return $result;
    }

}
