<?php

namespace ErpBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use ErpBundle\Model\ShipmentItemCollection;
use ErpBundle\Service\ErpService;

class ShipmentItemsController extends FOSRestController {
    
    /**
     * 
     * @return ErpService
     */
    private function getErpService() {        
        return $this->get('williams_erp.service');
    }
    
    /**
     * @param int $orderNumber
     * @param int $recordSequence
     * @return View
     */
    public function getItemsAction($orderNumber, $recordSequence) {
        
        $repo = $this->getErpService()->getShipmentRepository();
        
        $items = $repo->getItems((int)$orderNumber, (int)$recordSequence);
        
        $view = $this->view($items, 200);
        
        return $this->handleView($view);
        
    }
    
}