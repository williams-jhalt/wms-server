<?php

namespace ErpBundle\Repository;

use ErpBundle\Model\Order;
use ErpBundle\Model\SalesOrder;
use ErpBundle\Model\SalesOrderCollection;
use ErpBundle\Model\SalesOrderItemCollection;

class ClientSalesOrderRepository extends AbstractClientRepository implements SalesOrderRepositoryInterface {

    /**
     * 
     * @param integer $limit
     * @param integer $offset
     * @return SalesOrderCollection
     */
    public function findAll($limit = 100, $offset = 0) {

        $format = 'json';

        $response = $this->client->get("orders.{$format}", ['query' => ['limit' => $limit, 'offset' => $offset]]);
        
        $data = $response->getBody();

        $serializer = $this->erp->getSerializer();

        $result = $serializer->deserialize($data, 'ErpBundle\Model\SalesOrderCollection', $format);

        return $result;
    }

    /**
     * 
     * @param integer $limit
     * @param integer $offset
     * @return SalesOrderCollection
     */
    public function findOpen($limit = 100, $offset = 0) {

        $format = 'json';

        $response = $this->client->get("orders.{$format}", ['query' => ['limit' => $limit, 'offset' => $offset, 'open' => true]]);
        
        $data = $response->getBody();

        $serializer = $this->erp->getSerializer();

        $result = $serializer->deserialize($data, 'ErpBundle\Model\SalesOrderCollection', $format);

        return $result;
    }

    /**
     * 
     * @param string $searchTerms
     * @return SalesOrderCollection
     */
    public function findByTextSearch($searchTerms) {

        $format = 'json';

        $response = $this->client->get("orders.{$format}", ['query' => ['search' => $searchTerms]]);
        
        $data = $response->getBody();

        $serializer = $this->erp->getSerializer();

        $result = $serializer->deserialize($data, 'ErpBundle\Model\SalesOrderCollection', $format);

        return $result;
    }

    /**
     * 
     * @param integer $orderNumber
     * @return SalesOrder
     */
    public function get($orderNumber) {

        $format = 'json';

        $response = $this->client->get("orders/{$orderNumber}.{$format}");
        
        $data = $response->getBody();

        $serializer = $this->erp->getSerializer();

        $result = $serializer->deserialize($data, 'ErpBundle\Model\SalesOrder', $format);

        return $result;
    }

    /**
     * 
     * @param string $webReferenceNumber
     * @param string $customerNumber
     * @return SalesOrder
     */
    public function getByWebReferenceNumberAndCustomerNumber($webReferenceNumber, $customerNumber) {

        $format = 'json';

        $id = $webReferenceNumber . "-" . $customerNumber;

        $response = $this->client->get("weborders/{$id}.{$format}");
        
        $data = $response->getBody();

        $serializer = $this->erp->getSerializer();

        $result = $serializer->deserialize($data, 'ErpBundle\Model\SalesOrder', $format);

        return $result;
    }

    /**
     * 
     * @param integer $orderNumber
     * @return SalesOrderItemCollection
     */
    public function getItems($orderNumber) {

        $format = 'json';

        $response = $this->client->get("orders/{$orderNumber}/items.{$format}");
        
        $data = $response->getBody();

        $serializer = $this->erp->getSerializer();

        $result = $serializer->deserialize($data, 'ErpBundle\Model\SalesOrderItemCollection', $format);

        return $result;
    }

    /**
     * 
     * @param Order $order
     * @return boolean
     */
    public function submitOrder(Order $order) {

        $serializer = $this->erp->getSerializer();

        $this->client->post("orders.{$format}", [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => $serializer->serialize($order, 'json')
        ]);
        
        return true;
        
    }
    
    public function findByOrderDate(\DateTime $startDate, \DateTime $endDate, $limit = 100, $offset = 0) {

        $format = 'json';

        $response = $this->client->get("orders.{$format}", ['query' => ['start_date' => $startDate, 'end_date' => $endDate, 'limit' => $limit, 'offset' => $offset]]);
        
        $data = $response->getBody();

        $serializer = $this->erp->getSerializer();

        $result = $serializer->deserialize($data, 'ErpBundle\Model\SalesOrderCollection', $format);

        return $result;
        
    }
    
    public function findByCustomerNumberAndOrderDate($customerNumber, \DateTime $startDate, \DateTime $endDate, $limit = 100, $offset = 0) {

        $format = 'json';

        $response = $this->client->get("orders.{$format}", ['query' => ['customer_number' => $customerNumber, 'start_date' => $startDate, 'end_date' => $endDate, 'limit' => $limit, 'offset' => $offset]]);
        
        $data = $response->getBody();

        $serializer = $this->erp->getSerializer();

        $result = $serializer->deserialize($data, 'ErpBundle\Model\SalesOrderCollection', $format);

        return $result;
        
    }


}
