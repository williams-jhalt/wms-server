<?php

namespace ErpBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use ErpBundle\Model\ShipmentPackageCollection;
use ErpBundle\Service\ErpService;

class ShipmentCartonsController extends FOSRestController {
    
    /**
     * 
     * @return ErpService
     */
    private function getErpService() {        
        return $this->get('williams_erp.service');
    }
        
    /**
     * @param int $orderNumber
     * @return View
     */
    public function getCartonsAction($orderNumber) {
                
        $repo = $this->getErpService()->getShipmentRepository();
        
        $items = $repo->getPackages((int)$orderNumber);        
        
        $view = $this->view($items, 200);
        
        return $this->handleView($view);
        
    }
    
}