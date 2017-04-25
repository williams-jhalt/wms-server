<?php

namespace ErpBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use ErpBundle\Model\SalesOrderItemCollection;
use ErpBundle\Service\ErpService;

class SalesOrderItemsController extends FOSRestController {

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
    public function getItemsAction($orderNumber) {
        
        $repo = $this->getErpService()->getSalesOrderRepository();

        $items = $repo->getItems((int)$orderNumber);

        $view = $this->view($items, 200);

        return $this->handleView($view);
    }

}
