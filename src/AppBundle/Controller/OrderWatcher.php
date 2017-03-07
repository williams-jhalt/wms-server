<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Williams\WmsBundle\Service\WmsService;

/**
 * @Route("/order-watcher")
 */
class OrderWatcher extends Controller {
    
    /**
     * 
     * @return WmsService
     */
    private function getMuffsWms() {
        return $this->get('app.muffs_wms');
    }
    
    /**
     * 
     * @return WmsService
     */
    private function getWilliamsWms() {
        return $this->get('app.williams_wms');
    }
    
    /**
     * @Route("/", name="order_watcher_index")
     */
    public function indexAction() {
        
        $newMuffsOrders = $this->getMuffsWms()->getWeborderRepository()->getNewOrders();
        $newWilliamsOrders = $this->getWilliamsWms()->getWeborderRepository()->getNewOrders();
        
        return $this->render('order-watcher/index.html.twig', [
            'muffs_orders' => $newMuffsOrders,
            'williams_orders' => $newWilliamsOrders
        ]);
        
    }
    
}