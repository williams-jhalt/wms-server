<?php

namespace DscoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/orderstatus")
 */
class OrderStatusController extends Controller {
    
    /**
     * @Route("/", name="dsco_orderstatus_index")
     */
    public function indexAction() {
        return $this->redirectToRoute('dsco_orderstatus_list');
    }

    /**
     * @Route("/list", name="dsco_orderstatus_list")
     */
    public function listAction(Request $request) {
        
        $status = $request->get('status', 150);

        $orderStatuses = $this->getDoctrine()->getRepository('DscoBundle:OrderStatus')->findBy(array(
            'statusCode' => $status            
        ), array(
            'logicBrokerKey' => 'desc'
        ));

        return $this->render('@Dsco/orderstatus/list.html.twig', [
            'items' => $orderStatuses
        ]);
    }

    /**
     * @Route("/view/{id}", name="dsco_orderstatus_view")
     */
    public function viewAction($id, Request $request) {

        $status = $this->getDoctrine()->getRepository('DscoBundle:OrderStatus')->find($id);

        return $this->render('@Dsco/orderstatus/view.html.twig', [
                    'item' => $status
        ]);
    }

}
