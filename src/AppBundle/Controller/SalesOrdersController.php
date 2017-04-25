<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/orders")
 */
class SalesOrdersController extends Controller {

    /**
     * @Route("/", name="sales_orders_index")
     */
    public function indexAction() {
        return $this->render('sales-orders/index.html.twig');
    }

    /**
     * @Route("/list", name="sales_orders_list")
     */
    public function listAction(Request $request) {

        $manifestId = $request->get('manifestId');

        list($orderNumber, $recordSequence) = explode('-', $manifestId);

            $service = $this->get('app.order_service');

        $salesOrder = $service->getOrder($orderNumber);

        return $this->render('sales-orders/view.html.twig', [
                    'order' => $salesOrder
        ]);
    }

}
