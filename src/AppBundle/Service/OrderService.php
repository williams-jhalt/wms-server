<?php

namespace AppBundle\Service;

use AppBundle\Model\SalesOrder;
use DateTime;
use Doctrine\ORM\EntityManager;
use Williams\ConnectshipBundle\Service\ConnectshipService;
use Williams\ErpBundle\Model\SalesOrder as SalesOrder2;
use Williams\ErpBundle\Model\ShipmentPackage;
use Williams\ErpBundle\Service\ErpService;
use Williams\WmsBundle\Model\Weborder;
use Williams\WmsBundle\Service\WmsService;

class OrderService {

    const WMS_MUFFS = 'muffs';
    const WMS_WILLIAMS = 'williams';

    /**
     * @var ErpService
     */
    private $erp;

    /**
     * @var ConnectshipService
     */
    private $connectship;

    /**
     * @var WmsService
     */
    private $muffsWms;

    /**
     * @var WmsService
     */
    private $williamsWms;
    
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(ErpService $erp, ConnectshipService $connectship, WmsService $muffsWms, WmsService $williamsWms, EntityManager $em) {
        $this->erp = $erp;
        $this->connectship = $connectship;
        $this->muffsWms = $muffsWms;
        $this->williamsWms = $williamsWms;
        $this->em = $em;
    }

    /**
     * 
     * @return Weborder[]
     */
    public function getNewOrders($company = self::WMS_WILLIAMS) {

        if ($company == self::WMS_MUFFS) {
            $repo = $this->muffsWms->getWeborderRepository();
        } else {
            $repo = $this->williamsWms->getWeborderRepository();
        }

        return $repo->getNewOrders();
    }
    
    /**
     * @return SalesOrder
     */
    public function getOrder($orderNumber) {
        
        $erpOrder = $this->erp->getSalesOrderRepository()->get($orderNumber);
        
        $order = new SalesOrder();
        
        $this->loadOrderFromErp($order, $erpOrder);
                
        if (strtoupper(substr($order->getCustomerNumber(), -1)) == 'I') {
            $weborder = $this->muffsWms->getWeborderRepository()->getOrder($order->getWebsiteId());            
        } else {
            $weborder = $this->williamsWms->getWeborderRepository()->getOrder($order->getWebsiteId());
        }
        
        if ($weborder !== null) {
            $this->loadOrderFromWms($order, $weborder);
        }
        
        return $order;
        
    }
    
    /**
     * @return ShipmentPackage[]
     */
    public function getCartons($orderNumber) {
        
        $repo = $this->em->getRepository('AppBundle:Carton');
        $cartons = $this->erp->getShipmentRepository()->getPackages($orderNumber)->getShipmentPackages();
        
        foreach ($cartons as $carton) {
            
            $c = $repo->find($carton->getUcc());
            
            if ($c !== null) {
                $carton->setPackageHeight($c->getHeight());
                $carton->setPackageLength($c->getLength());
                $carton->setPackageWidth($c->getWidth());
                $carton->setShippingWeight($c->getWeight());
            }
            
        }
        
        return $cartons;
        
    }
    
    /**
     * Get orders from website by date, company can be one of:
     * 
     * OrderService::WMS_WILLIAMS
     * OrderService::WMS_MUFFS
     * 
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param string $company
     * 
     * @return Weborder[]
     */
    public function getWebsiteOrdersByDate(DateTime $startDate, DateTime $endDate, $company) {
        
        if ($company == self::WMS_WILLIAMS) {
            return $this->williamsWms->getWeborderRepository()->findByOrderDate($startDate, $endDate);
        } else {
            return $this->muffsWms->getWeborderRepository()->findByOrderDate($startDate, $endDate);
        }
        
    }

    public function getOrdersByDate(DateTime $startDate, DateTime $endDate, $company = self::WMS_WILLIAMS) {

        if ($company == self::WMS_MUFFS) {
            return $this->getMuffsOrdersByDate($startDate, $endDate);
        } else {
            return $this->getWilliamsOrdersByDate($startDate, $endDate);
        }
    }

    private function getWilliamsOrdersByDate(DateTime $startDate, DateTime $endDate) {
        
        $result = array();
        
        $limit = 1000;
        $offset = 0;
        
        do {
        
            $erpOrders = $this->erp->getSalesOrderRepository()->findByOrderDate($startDate, $endDate, $limit, $offset)->getSalesOrders();
            
            foreach ($erpOrders as $erpOrder) {
                
                if (strtoupper(substr($erpOrder->getCustomerNumber(), -1)) == 'I') {
                    continue;
                }                
                
                $salesOrder = new SalesOrder();
                
                $salesOrder->setCompany(SalesOrder::COMPANY_WILLIAMS);
                
                $this->loadOrderFromErp($salesOrder, $erpOrder);                
                
                if (!empty($erpOrder->getWebReferenceNumber())) {
                    $salesOrder->setSource(SalesOrder::SOURCE_WEBSITE);
                    $weborder = $this->williamsWms->getWeborderRepository()->getOrder($salesOrder->getWebsiteId());
                    $this->loadOrderFromWms($salesOrder, $weborder);
                } else {
                    $salesOrder->setSource(SalesOrder::SOURCE_CSR);
                }
                
                $result[] = $salesOrder;
                
            }
            
            $offset += $limit;
            
        } while(count($erpOrders) > 0);

        return $result;
        
    }

    private function getMuffsOrdersByDate(DateTime $startDate, DateTime $endDate) {
        
        $result = array();
        
        $limit = 1000;
        $offset = 0;
        
        do {
        
            $erpOrders = $this->erp->getSalesOrderRepository()->findByOrderDate($startDate, $endDate, $limit, $offset)->getSalesOrders();
            
            foreach ($erpOrders as $erpOrder) {
                
                if (strtoupper(substr($erpOrder->getCustomerNumber(), -1)) != 'I') {
                    continue;
                }                
                
                $salesOrder = new SalesOrder();
                
                $salesOrder->setCompany(SalesOrder::COMPANY_MUFFS);
                
                $this->loadOrderFromErp($salesOrder, $erpOrder);                
                
                if (!empty($erpOrder->getWebReferenceNumber())) {
                    $salesOrder->setSource(SalesOrder::SOURCE_WEBSITE);
                    $weborder = $this->williamsWms->getWeborderRepository()->getOrder($salesOrder->getWebsiteId());
                    $this->loadOrderFromWms($salesOrder, $weborder);
                } else {
                    $salesOrder->setSource(SalesOrder::SOURCE_CSR);
                }
                 
                $result[] = $salesOrder;
                
            }
            
            $offset += $limit;
            
        } while(count($erpOrders) > 0);

        return $result;
        
    }

    private function loadOrderFromWms(SalesOrder $order, Weborder $weborder) {

        $order->setWebsiteOrderDate($weborder->getOrderDate())
                ->setWebsiteNotes($weborder->getNotes())
                ->setShippingDate($weborder->getShipments()[0]->getShippingDate());

        return $order;
    }

    private function loadOrderFromErp(SalesOrder $order, SalesOrder2 $erpOrder) {

        $order->setCustomerNumber($erpOrder->getCustomerNumber())
                ->setCustomerPurchaseOrder($erpOrder->getCustomerPurchaseOrder())
                ->setOpen($erpOrder->getOpen())
                ->setOrderDate($erpOrder->getOrderDate())
                ->setOrderNumber($erpOrder->getOrderNumber())
                ->setRecordSequence($erpOrder->getRecordSequence())
                ->setShipToAddress1($erpOrder->getShipToAddress1())
                ->setShipToAddress2($erpOrder->getShipToAddress2())
                ->setShipToAddress3($erpOrder->getShipToAddress3())
                ->setShipToCity($erpOrder->getShipToCity())
                ->setShipToCountry($erpOrder->getShipToCountry())
                ->setShipToEmail($erpOrder->getShipToEmail())
                ->setShipToName($erpOrder->getShipToName())
                ->setShipToPhone($erpOrder->getShipToPhone())
                ->setShipToState($erpOrder->getShipToState())
                ->setShipToZip($erpOrder->getShipToZip())
                ->setShipViaCode($erpOrder->getShipViaCode())
                ->setStatus($erpOrder->getStatus())
                ->setWebReferenceNumber($erpOrder->getWebReferenceNumber());

        return $order;
    }

}
