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

    /**
     *
     * @var \ConnectshipBundle\Service\ConnectshipService
     */
    private $cs;

    public function __construct(ErpService $service, \ConnectshipBundle\Service\ConnectShipService $cs) {
        $this->service = $service;
        $this->cs = $cs;
    }

    public function submitOrder(Order $order, $customerNumber) {

        $validShipViaCodes = ['BESTRATE', 'GROUND', 'PRIORITY', 'FIRST', 'UPSECON', 'RED', 'BLUE'];

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
            $item->setLineNumber($line->getLineNumber());
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

            $packages = $erpPackages->getShipmentPackages();
            if (!empty($packages)) {
                $trackingNumber = $packages[0]->getTrackingNumber();
                $carrierCode = $packages[0]->getManifestCarrier();
                $shipmentInfo->setCarrierCode($carrierCode);
            } else {
                $shipmentInfo->setCarrierCode($erpShipment->getShipViaCode());
            }

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
                $carrierCode = null;

                foreach ($erpPackages->getShipmentPackages() as $erpPackage) {
                    $trackingNumber = $erpPackage->getTrackingNumber();
                    $carrierCode = $erpPackage->getManifestCarrier();
                    foreach ($erpPackage->getItems() as $erpPackageItem) {
                        if ($erpPackageItem->getItemNumber() == $erpItem->getItemNumber()) {
                            $trackingNumber = $erpPackage->getTrackingNumber();
                            $carrierCode = $erpPackage->getManifestCarrier();
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
//                $lineShipmentInfo->setCarrierCode($carrierCode);
                $lineShipmentInfo->setTrackingNumber($trackingNumber);
                $lineShipmentInfo->setClassCode($carrierCode);
                $lineShipmentInfo->setQty($erpItem->getQuantityShipped());
                $lineShipmentInfos[] = $lineShipmentInfo;

                $line->setShipmentInfos($lineShipmentInfos);

//                $line->setQuantity($erpItem->getQuantityShipped());
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

    private function getServiceLevelFromTrackingNumber($trackingNumber) {

        $regexps = [
            "UPS United States Next Day Air" => "/^1Z\w{6}01\d{8}$/",
            "UPS United States 2nd Day Air" => "/^1Z\w{6}02\d{8}$/",
            "UPS United States Ground" => "/^1Z\w{6}03\d{8}$/",
            "UPS Canada Express Saver" => "/^1Z\w{6}04\d{8}$/",
            "UPS United States 3 Day Select" => "/^1Z\w{6}12\d{8}$/",
            "UPS United States Next Day Air Saver" => "/^1Z\w{6}13\d{8}$/",
            "UPS Canada Express" => "/^1Z\w{6}14\d{8}$/",
            "UPS United States Next Day Air Early A.M." => "/^1Z\w{6}15\d{8}$/",
            "UPS Canada Expedited" => "/^1Z\w{6}17\d{8}$/",
            "UPS Canada Standard" => "/^1Z\w{6}20\d{8}$/",
            "UPS United States Ground - Returns Plus - Three Pickup Attempts" => "/^1Z\w{6}22\d{8}$/",
            "UPS United States Next Day Air Early A.M. - COD" => "/^1Z\w{6}32\d{8}$/",
            "UPS United States Next Day Air Early A.M. - Saturday Delivery, COD" => "/^1Z\w{6}33\d{8}$/",
            "UPS United States Next Day Air Early A.M. - Saturday Delivery" => "/^1Z\w{6}41\d{8}$/",
            "UPS United States Ground - Signature Required" => "/^1Z\w{6}42\d{8}$/",
            "UPS United States Next Day Air - Saturday Delivery" => "/^1Z\w{6}44\d{8}$/",
            "UPS United States Worldwide Express" => "/^1Z\w{6}66\d{8}$/",
            "UPS United States Ground - Collect on Delivery" => "/^1Z\w{6}72\d{8}$/",
            "UPS United States Ground - Returns Plus - One Pickup Attempt" => "/^1Z\w{6}78\d{8}$/",
            "UPS United States Ground - Returns - UPS Prints and Mails Label" => "/^1Z\w{6}90\d{8}$/",
            "UPS United States Next Day Air Early A.M. - Adult Signature Required" => "/^1Z\w{6}A0\d{8}$/",
            "UPS United States Next Day Air Early A.M. - Saturday Delivery, Adult Signature Required" => "/^1Z\w{6}A1\d{8}$/",
            "UPS United States Next Day Air - Adult Signature Required" => "/^1Z\w{6}A2\d{8}$/",
            "UPS United States Ground - Adult Signature Required" => "/^1Z\w{6}A8\d{8}$/",
            "UPS United States Next Day Air Early A.M. - Adult Signature Required, COD" => "/^1Z\w{6}A9\d{8}$/",
            "UPS United States Next Day Air Early A.M. - Saturday Delivery, Adult Signature Required, COD" => "/^1Z\w{6}AA\d{8}$/",
            "UPS Mail Innovations" => "/^92748.*/",
            "UPS SurePost" => "/^1Z\w{6}Y[T|W]\d{8}$/",
            "USPS Express" => "/^94001.*/",
            "USPS Priority Mail" => "/^94055.*/",
            "USPS Certified Mail" => "/^94073.*/",
            "USPS Priority Mail Express 1-Day" => "/^94817.*/",
            "USPS COD" => "/^93033.*/",
            "USPS Global Express Garanteed" => "/^82.*/",
            "USPS Priority Mail Express" => "/^92701.*/",
            "USPS Priority Mail International" => "/^\w{2}\d{9}\w{2}.*/",
            "USPS Registered Mail" => "/^92088.*/",
            "USPS Signature Confirmation" => "/^92\d21.*/",
            "FedEx Ground" => "/^66802.*/"
        ];

        foreach ($regexps as $code => $regexp) {
            if (preg_match($regexp, $trackingNumber)) {
                return $code;
            }
        }
    }

    private function getCarrierFromTrackingNumber($trackingNumber) {
        $code = $this->getServiceLevelFromTrackingNumber($trackingNumber);
        return substr($code, 0, strpos($code, " "));
    }

}
