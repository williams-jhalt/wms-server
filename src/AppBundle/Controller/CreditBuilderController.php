<?php

namespace AppBundle\Controller;

use AppBundle\Service\OrderService;
use AppBundle\Service\ProductService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/credits")
 */
class CreditBuilderController extends Controller {

    private $orderService;
    private $productService;
    
    /**
     * @Route("/", name="credits_index")
     */
    public function indexAction() {
        return $this->render('credits/index.html.twig');
    }
    
    /**
     * 
     * @return OrderService
     */
    private function getOrderService() {
        if ($this->orderService == null) {
            $this->orderService = $this->get('app.order_service');
        }
        return $this->orderService;
    }
    
    /**
     * @return ProductService
     */
    private function getProductService() {
        if ($this->productService == null) {
            $this->productService = $this->get('app.product_service');            
        }
        return $this->productService;
    }

    /**
     * @Route("/list", name="credits_list")
     */
    public function listAction(Request $request) {

        $manifestId = $request->get('manifestId');

        list($orderNumber, $recordSequence) = explode('-', $manifestId);

        $service = $this->getOrderService();
        $productService = $this->getProductService();

        $salesOrder = $service->getOrder($orderNumber);
        $salesOrderItems = $service->getOrderItems($orderNumber);
        
        $creditEstimate = new \AppBundle\Model\CreditEstimate($productService, $salesOrder, $salesOrderItems);

        return $this->render('credits/view.html.twig', [
                    'creditEstimate' => $creditEstimate
        ]);
    }

}
