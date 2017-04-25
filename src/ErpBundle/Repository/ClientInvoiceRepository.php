<?php

namespace ErpBundle\Repository;

use DateTime;
use ErpBundle\Model\Invoice;
use ErpBundle\Model\InvoiceCollection;
use ErpBundle\Model\InvoiceItemCollection;

class ClientInvoiceRepository extends AbstractClientRepository implements InvoiceRepositoryInterface {

    /**
     * 
     * @param integer $limit
     * @param integer $offset
     * @return InvoiceCollection
     */
    public function findAll($limit = 1000, $offset = 0) {

        $format = 'json';

        $response = $this->client->get("invoices.{$format}", ['query' => ['limit' => $limit, 'offset' => $offset]]);

        $data = $response->getBody();

        $serializer = $this->erp->getSerializer();

        $result = $serializer->deserialize($data, 'ErpBundle\Model\InvoiceCollection', $format);

        return $result;
    }

    /**
     * 
     * @param string $customerNumber
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param boolean $consolidated
     * @param int $limit
     * @param int $offset
     * @return InvoiceCollection
     */
    public function findByCustomerAndDate($customerNumber, $startDate = null, $endDate = null, $consolidated = false, $limit = 1000, $offset = 0) {

        $format = 'json';

        $query = [
            'customer_number' => $customerNumber,
            'limit' => $limit,
            'offset' => $offset
        ];

        if ($startDate !== null) {
            $query['startDate'] = $startDate->format('c');
        }

        if ($endDate !== null) {
            $query['endDate'] = $endDate->format('c');
        }

        $salesOrderResponse = $this->client->get("orders.{$format}", ['query' => $query]);

        $salesOrderData = $salesOrderResponse->getBody();

        $serializer = $this->erp->getSerializer();

        $salesOrders = $serializer->deserialize($salesOrderData, 'ErpBundle\Model\SalesOrderCollection', $format);
        
        $result = new InvoiceCollection();        

        foreach ($salesOrders->getSalesOrders() as $salesOrder) {
            
            $invoices = $this->findByOrderNumber($salesOrder->getOrderNumber());
            
            $result->setInvoices(array_merge($result->getInvoices(), $invoices->getInvoices()));
            
        }

        return $result;
    }

    /**
     * 
     * @param integer $orderNumber
     * @return InvoiceCollection
     */
    public function findByOrderNumber($orderNumber) {

        $format = 'json';

        $response = $this->client->get("invoices.{$format}", ['query' => ['orderNumber' => $orderNumber]]);

        $data = $response->getBody();

        $serializer = $this->erp->getSerializer();

        $result = $serializer->deserialize($data, 'ErpBundle\Model\InvoiceCollection', $format);

        return $result;
    }

    /**
     * 
     * @param integer $orderNumber
     * @param integer $recordSequence
     * @return Invoice
     */
    public function get($orderNumber, $recordSequence = 1) {

        $format = 'json';

        $response = $this->client->get("invoices/{$orderNumber}/{$recordSequence}.{$format}");

        $data = $response->getBody();

        $serializer = $this->erp->getSerializer();

        $result = $serializer->deserialize($data, 'ErpBundle\Model\Invoice', $format);

        return $result;
    }

    /**
     * 
     * @param integer $orderNumber
     * @param integer $recordSequence
     * @return InvoiceItemCollection
     */
    public function getItems($orderNumber, $recordSequence = 1) {

        $format = 'json';

        $response = $this->client->get("invoices/{$orderNumber}/{$recordSequence}/items.{$format}");

        $data = $response->getBody();

        $serializer = $this->erp->getSerializer();

        $result = $serializer->deserialize($data, 'ErpBundle\Model\InvoiceItemCollection', $format);

        return $result;
    }

}
