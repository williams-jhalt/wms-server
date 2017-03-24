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
     * @Route("/{company}", name="sales_orders_index", defaults={"company" = "WTC"})
     */
    public function indexAction($company) {
        return $this->render('sales-orders/index.html.twig', [
                    'company' => $company
        ]);
    }

    /**
     * @Route("/{company}/list", name="sales_orders_list", defaults={"company" = "WTC"})
     */
    public function listAction($company, Request $request) {

        $manifestId = $request->get('manifestId');

        list($orderNumber, $recordSequence) = explode('-', $manifestId);

        if ($company == 'MFG') {
            $service = $this->get('app.mfg_order_service');
        } else {
            $service = $this->get('app.order_service');
        }

        $salesOrder = $service->getOrder($orderNumber);

        return $this->render('sales-orders/view.html.twig', [
                    'order' => $salesOrder,
                    'company' => $company
        ]);
    }

}
