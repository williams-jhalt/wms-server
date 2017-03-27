<?php

namespace AppBundle\Controller;

use AppBundle\Service\OrderService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/shipments")
 */
class ShipmentsController extends Controller {

    /**
     * @return OrderService
     */
    private function getOrderService() {
        return $this->get('app.order_service');
    }

    /**
     * @Route("/", name="shipments_index")
     */
    public function indexAction() {

        $service = $this->getOrderService();
        
        $docRepo = $this->getDoctrine()->getRepository('AppBundle:PickerLog');

        $openShipments = $service->findOpenShipments();
        
        $data = [];
        
        foreach ($openShipments as $shipment) {
            
            $s = [
                'manifestId' => $shipment->getManifestId(),
                'orderDate' => $shipment->getOrderDate(),
                'picker' => '',
                'pickedOn' => ''
            ];
            
            $pickerLog = $docRepo->findByOrderNumber($shipment->getManifestId());
            
            foreach ($pickerLog as $log) {
                $s['picker'] = $log->getUser();
                $s['pickedOn'] = $log->getTimestamp();
            }
            
            $data[] = $s;
            
        }

        return $this->render('shipments/index.html.twig', [
                    'shipments' => $data
        ]);
    }

}
