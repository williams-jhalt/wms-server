<?php

namespace LogicBrokerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/orderstatus")
 */
class OrderStatusController extends Controller {
    
    /**
     * @Route("/", name="logicbroker_orderstatus_index")
     */
    public function indexAction() {
        return $this->redirectToRoute('logicbroker_orderstatus_list');
    }

    /**
     * @Route("/list", name="logicbroker_orderstatus_list")
     */
    public function listAction(Request $request) {
        
        $status = $request->get('status', 150);

        $orderStatuses = $this->getDoctrine()->getRepository('LogicBrokerBundle:OrderStatus')->findBy(array(
            'statusCode' => $status            
        ), array(
            'logicBrokerKey' => 'desc'
        ));

        return $this->render('@LogicBroker/orderstatus/list.html.twig', [
            'items' => $orderStatuses
        ]);
    }

    /**
     * @Route("/view/{id}", name="logicbroker_orderstatus_view")
     */
    public function viewAction($id, Request $request) {

        $status = $this->getDoctrine()->getRepository('LogicBrokerBundle:OrderStatus')->find($id);

        return $this->render('@LogicBroker/orderstatus/view.html.twig', [
                    'item' => $status
        ]);
    }

}
