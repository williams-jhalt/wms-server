<?php

namespace AppBundle\Service;

use DateTime;
use ErpBundle\Model\Order as ErpOrder;
use ErpBundle\Model\OrderItem as ErpOrderItem;
use ErpBundle\Service\ErpService;
use LogicBrokerBundle\Adapter\AbstractInventoryAdapter;
use LogicBrokerBundle\Entity\OrderStatus;
use LogicBrokerBundle\LogicBrokerHandlerInterface;
use LogicBrokerBundle\Model\Inventory;
use LogicBrokerBundle\Model\Invoice;
use LogicBrokerBundle\Model\InvoiceLine;
use LogicBrokerBundle\Model\Order;
use LogicBrokerBundle\Model\Shipment;
use LogicBrokerBundle\Model\ShipmentInfo;
use LogicBrokerBundle\Model\ShipmentLine;
use SplFileObject;

class LogicBrokerHandler implements LogicBrokerHandlerInterface {

    /**
     *
     * @var ErpService
     */
    private $service;

    public function __construct(ErpService $service) {
        $this->service = $service;
    }

    public function submitOrder(Order $order, $customerNumber) {

        $validShipViaCodes = ['BESTRATE', 'GROUND', 'PRIORITY', 'FIRST', 'UPSECON'];

        $salesOrder = new ErpOrder();
        $salesOrder->setCustomerNumber($customerNumber);
        $shipToAddress = $order->getShipToAddress();
        $salesOrder->setShipToAddress1($shipToAddress->getAddress1());
        $salesOrder->setShipToAddress2($shipToAddress->getAddress2());
        $salesOrder->setShipToCity($shipToAddress->getCity());

        if (strlen($shipToAddress->getCountry()) == 3) {
            $salesOrder->setShipToCountry($shipToAddress->getCountry());
        } if (strlen($shipToAddress->getCountry()) == 2) {
            $salesOrder->setShipToCountry($this->translateCountryCode($shipToAddress->getCountry()));
        } else {
            $salesOrder->setShipToCountry('USA');
        }

        $salesOrder->setShipToEmail($shipToAddress->getEmail());
        $salesOrder->setShipToName($shipToAddress->getFirstName() . " " . $shipToAddress->getLastName());
        $salesOrder->setShipToPhone($shipToAddress->getPhone());
        $salesOrder->setShipToState($shipToAddress->getState());
        $salesOrder->setShipToZip($shipToAddress->getZip());
        $salesOrder->setWebOrderNumber($order->getIdentifier()->getLogicBrokerKey());
        $salesOrder->setCustomerPurchaseOrder($order->getPartnerPO());

        if (array_search($order->getShipmentInfos()[0]->getCarrierCode(), $validShipViaCodes)) {
            $salesOrder->setShipViaCode($order->getShipmentInfos()[0]->getCarrierCode());
        } else {
            $salesOrder->setShipViaCode('BESTRATE');
        }

        $items = [];

        foreach ($order->getOrderLines() as $line) {
            $item = new ErpOrderItem();
            $item->setItemNumber($line->getItemIdentifier()->getSupplierSKU());
            $item->setQuantityOrdered($line->getQuantity());
            $items[] = $item;
        }

        $salesOrder->setItems($items);

        $this->service->getSalesOrderRepository()->submitOrder($salesOrder);

        return $order->getIdentifier()->getLogicBrokerKey();
    }

    public function retrieveOrderNumber(OrderStatus $orderStatus) {

        $weborderNumber = $orderStatus->getWeborderNumber();
        $customerNumber = $orderStatus->getCustomerNumber();

        $salesOrder = $this->service->getSalesOrderRepository()->getByWebReferenceNumberAndCustomerNumber($weborderNumber, $customerNumber);

        return $salesOrder->getOrderNumber();
    }

    public function getInvoices(OrderStatus $orderStatus) {

        $orderNumber = $orderStatus->getOrderNumber();

        $invoices = [];

        $erpInvoices = $this->service->getInvoiceRepository()->findByOrderNumber($orderNumber);
        foreach ($erpInvoices->getInvoices() as $erpInvoice) {
            if ($erpInvoice->getOpen()) {
                continue;
            }

            $invoice = new Invoice();
            $invoice->getIdentifier()->setLinkKey($orderStatus->getLinkKey());
            $invoice->setReceiverCompanyId($orderStatus->getSenderCompanyId());
            $invoice->setInvoiceDate($erpInvoice->getInvoiceDate());
            $invoice->setInvoiceNumber($erpInvoice->getOrderNumber() . "-" . $erpInvoice->getRecordSequence());
            $invoice->setDocumentDate(new DateTime());
            $invoice->setPartnerPO($erpInvoice->getCustomerPurchaseOrder());
            $invoice->setInvoiceTotal($erpInvoice->getNetInvoiceAmount());
            $invoice->setHandlingAmount($erpInvoice->getShippingAndHandling());

            $lines = $invoice->getInvoiceLines();

            $erpInvoiceItems = $this->service->getInvoiceRepository()->getItems($erpInvoice->getOrderNumber(), $erpInvoice->getRecordSequence());

            foreach ($erpInvoiceItems->getItems() as $erpItem) {
                $line = new InvoiceLine();
                $line->setLineNumber($erpItem->getLineNumber());
                $line->getItemIdentifier()->setSupplierSKU($erpItem->getItemNumber());
                $line->setQuantity($erpItem->getQuantityBilled());
                $line->setPrice($erpItem->getPrice());
                $lines[] = $line;
            }

            $invoice->setInvoiceLines($lines);
            $invoices[] = $invoice;
        }

        return $invoices;
    }

    public function getShipments(OrderStatus $orderStatus) {

        $orderNumber = $orderStatus->getOrderNumber();

        $erpPackages = $this->service->getShipmentRepository()->getPackages($orderNumber);

        $shipments = [];

        $erpShipments = $this->service->getShipmentRepository()->findByOrderNumber($orderNumber);        
        foreach ($erpShipments->getShipments() as $erpShipment) {
            if ($erpShipment->getOpen()) {
                continue;
            }

            $shipment = new Shipment();
            
            $shipment->getIdentifier()->setLinkKey($orderStatus->getLinkKey());            
            $shipment->setReceiverCompanyId($orderStatus->getSenderCompanyId());
            if ($erpShipment->getShipDate() !== null) {
                $shipment->setDocumentDate($erpShipment->getShipDate());
            } else {
                $shipment->setDocumentDate(new DateTime());
            }
            $shipment->setPartnerPO($erpShipment->getCustomerPurchaseOrder());
            $shipment->setShipmentNumber($erpShipment->getManifestId());

            $shipmentInfos = $shipment->getShipmentInfos();

            $shipmentInfo = new ShipmentInfo();
            $shipmentInfo->setCarrierCode($erpShipment->getShipViaCode());
            $shipmentInfos[] = $shipmentInfo;

            $shipment->setShipmentInfos($shipmentInfos);

            $lines = $shipment->getShipmentLines();

            $erpShipmentItems = $this->service->getShipmentRepository()->getItems($erpShipment->getOrderNumber(), $erpShipment->getRecordSequence());

            foreach ($erpShipmentItems->getItems() as $erpItem) {
                $line = new ShipmentLine();
                $line->setLineNumber($erpItem->getLineNumber());
                $line->getItemIdentifier()->setSupplierSKU($erpItem->getItemNumber());

                $lineShipmentInfos = $line->getShipmentInfos();

                $trackingNumber = null;

                foreach ($erpPackages->getShipmentPackages() as $erpPackage) {
                    $trackingNumber = $erpPackage->getTrackingNumber();
                    foreach ($erpPackage->getItems() as $erpPackageItem) {
                        if ($erpPackageItem->getItemNumber() == $erpItem->getItemNumber()) {
                            $trackingNumber = $erpPackage->getTrackingNumber();
                            break 2;
                        }
                    }
                }

                $lineShipmentInfo = new ShipmentInfo();
                if ($erpShipment->getShipDate() !== null) {
                    $lineShipmentInfo->setDateShipped($erpShipment->getShipDate());
                } else {
                    $lineShipmentInfo->setDateShipped(new DateTime());
                }
                $lineShipmentInfo->setTrackingNumber($trackingNumber);
                $lineShipmentInfos[] = $lineShipmentInfo;

                $line->setShipmentInfos($lineShipmentInfos);

                $line->setQuantity($erpItem->getQuantityShipped());
                $lines[] = $line;
            }

            $shipment->setShipmentLines($lines);
            $shipments[] = $shipment;
        }

        return $shipments;
    }

    public function writeInventory(AbstractInventoryAdapter $adapter) {

        $limit = 1000;
        $offset = 0;

        $repo = $this->service->getProductRepository();

        do {

            $items = $repo->findAll($limit, $offset);

            foreach ($items->getProducts() as $item) {

                $inventory = new Inventory();
                $inventory->setSupplierSKU($item->getItemNumber());
                $inventory->setQuantity($item->getQuantityOnHand() - $item->getQuantityCommitted());
                $inventory->setUpc($item->getBarcode());

                $adapter->writeLine($inventory);
            }

            $offset += $limit;
        } while (count($items) > 0);
    }
    
    private function translateCountryCode($code) {
        
        $file = new SplFileObject(__DIR__ . "/countries.csv", "rb");
        
        while (!$file->eof()) {
            
            $row = $file->fgetcsv();
            
            if ($row[2] == $code) {
                $file = null;
                return $row[3];
            }
            
        }
        
        $file = null;
        
        return "USA";
        
    }

}
