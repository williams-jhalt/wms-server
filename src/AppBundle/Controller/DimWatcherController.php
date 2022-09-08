<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/dimwatcher")
 */
class DimWatcherController extends Controller {
    /**
     * @Route("/", name="dimwatcher_index")
     */
    public function indexAction() {
        return $this->render('dimwatcher/index.html.twig');
    }

    /**
     * @Route("/list", name="dimwatcher_list")
     */
    public function listAction(Request $request) {

        $manifestId = $request->get('manifestId');

        if (strpos($manifestId, '-') !== false) {
            list($orderNumber, $recordSequence) = explode('-', $manifestId);
        } else {
            $orderNumber = $manifestId;
        }

        $service = $this->get('app.order_service');

        $salesOrder = $service->getOrder($orderNumber);

        return $this->render('dimwatcher/view.html.twig', [
            'order' => $salesOrder
        ]);
    }
}