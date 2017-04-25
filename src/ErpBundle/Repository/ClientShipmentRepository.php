<?php

namespace ErpBundle\Repository;

use ErpBundle\Model\Shipment;
use ErpBundle\Model\ShipmentCollection;
use ErpBundle\Model\ShipmentItemCollection;
use ErpBundle\Model\ShipmentPackageCollection;

class ClientShipmentRepository extends AbstractClientRepository implements ShipmentRepositoryInterface {

    /**
     * 
     * @param integer $limit
     * @param integer $offset
     * @return ShipmentCollection
     */
    public function findAll($limit = 1000, $offset = 0) {

        $format = 'json';

        $response = $this->client->get("shipments.{$format}", ['query' => ['limit' => $limit, 'offset' => $offset]]);
        
        $data = $response->getBody();

        $serializer = $this->erp->getSerializer();

        $result = $serializer->deserialize($data, 'ErpBundle\Model\ShipmentCollection', $format);

        return $result;
    }

    /**
     * 
     * @param int $limit
     * @param int $offset
     * @return InvoiceCollection
     */
    public function findOpen($limit = 1000, $offset = 0) {

        $format = 'json';

        $query = [
            'open' => true,
            'limit' => $limit,
            'offset' => $offset
        ];

        $salesOrderResponse = $this->client->get("orders.{$format}", ['query' => $query]);

        $salesOrderData = $salesOrderResponse->getBody();

        $serializer = $this->erp->getSerializer();

        $salesOrders = $serializer->deserialize($salesOrderData, 'ErpBundle\Model\SalesOrderCollection', $format);
        
        $result = new ShipmentCollection();        

        foreach ($salesOrders->getSalesOrders() as $salesOrder) {

            $shipments = $this->findByOrderNumber($salesOrder->getOrderNumber());
            
            $result->setShipments(array_merge($result->getShipments(), $shipments->getShipments()));
            
        }

        return $result;
    }

    /**
     * 
     * @param integer $orderNumber
     * @return ShipmentCollection
     */
    public function findByOrderNumber($orderNumber) {

        $format = 'json';

        $response = $this->client->get("shipments/{$orderNumber}.{$format}");
        
        $data = $response->getBody();

        $serializer = $this->erp->getSerializer();

        $result = $serializer->deserialize($data, 'ErpBundle\Model\ShipmentCollection', $format);

        return $result;
    }

    /**
     * 
     * @param integer $orderNumber
     * @param integer $recordSequence
     * @return Shipment
     */
    public function get($orderNumber, $recordSequence = 1) {

        $format = 'json';

        $response = $this->client->get("shipments/{$orderNumber}/{$recordSequence}.{$format}");
        
        $data = $response->getBody();

        $serializer = $this->erp->getSerializer();

        $result = $serializer->deserialize($data, 'ErpBundle\Model\Shipment', $format);

        return $result;
        
    }

    /**
     * 
     * @param integer $orderNumber
     * @param integer $recordSequence
     * @return ShipmentItemCollection
     */
    public function getItems($orderNumber, $recordSequence = 1) {

        $format = 'json';

        $response = $this->client->get("shipments/{$orderNumber}/{$recordSequence}/items.{$format}");
        
        $data = $response->getBody();

        $serializer = $this->erp->getSerializer();

        $result = $serializer->deserialize($data, 'ErpBundle\Model\ShipmentItemCollection', $format);

        return $result;
    }

    /**
     * 
     * @param integer $orderNumber
     * @return ShipmentPackageCollection
     */
    public function getPackages($orderNumber) {

        $format = 'json';

        $response = $this->client->get("shipments/{$orderNumber}/packages.{$format}");
        
        $data = $response->getBody();

        $serializer = $this->erp->getSerializer();

        $result = $serializer->deserialize($data, 'ErpBundle\Model\ShipmentPackageCollection', $format);

        return $result;
        
    }

}
