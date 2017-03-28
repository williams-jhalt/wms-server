<?php

namespace AppBundle\Controller;

use AppBundle\Service\OrderService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        return $this->render('shipments/index.html.twig');
    }

    /**
     * @param Request $request
     * @return \AppBundle\Controller\Response
     * 
     * @Route("/list", name="shipments_list", options={"expose": true})
     */
    public function listAction(Request $request) {

        $isPacked = $request->get('is_packed');
        $isPicked = $request->get('is_picked');
        $isShipped = $request->get('is_shipped');
        $draw = (int) $request->get('draw', 1);
        $start = (int) $request->get('start', 0);
        $length = (int) $request->get('length', 10);
        $search = $request->get('search');
        $order = $request->get('order');
        $columns = $request->get('columns');

        $cacheItem = $this->get('cache.app')->getItem('shipments_list');

        if (!$cacheItem->isHit()) {
            $service = $this->getOrderService();
            $docRepo = $this->getDoctrine()->getRepository('AppBundle:PickerLog');
            $openShipments = $service->findOpenShipments();

            $resultData = [];

            foreach ($openShipments as $shipment) {

                $picker = $docRepo->findOneByOrderNumber($shipment->getManifestId());
                $cartons = $service->getCartons($shipment->getOrderNumber());

                $cartonCount = count($cartons);

                $trackingNumbers = "";

                if ($cartonCount > 0) {
                    for ($i = 0; $i < $cartonCount; $i++) {
                        if (!empty($cartons[$i]->getTrackingNumber())) {
                            $trackingNumbers .= $cartons[$i]->getTrackingNumber();
                            if ($i < $cartonCount - 1) {
                                $trackingNumbers .= ", ";
                            }
                        }
                    }
                }

                $resultData[] = [
                    $shipment->getManifestId(),
                    $shipment->getOrderDate()->format('Y-m-d'),
                    $picker == null ? "Not Picked" : $picker->getUser(),
                    $cartonCount > 0 ? "{$cartonCount} Cartons Packed" : "Not Packed",
                    empty($trackingNumbers) ? "Not Shipped" : $trackingNumbers
                ];
            }

            $cacheItem->set($resultData);
            $cacheItem->expiresAfter(300);
            $this->get('cache.app')->save($cacheItem);
        } else {
            $resultData = $cacheItem->get();
        }

        $filtered = [];

        foreach ($resultData as $item) {
            if (!empty($search['value']) && !preg_match("/.*{$search['value']}.*/", $item[0])) {
                continue;
            }
            if ($isShipped == 'true' && $item[3] == "Not Shipped") {
                continue;
            }
            if ($isPacked == 'true' && $item[3] == "Not Packed") {
                continue;
            }
            if ($isPicked == 'true' && $item[2] == "Not Picked") {
                continue;
            }
            $filtered[] = $item;
        }

        foreach ($order as $o) {
            usort($filtered, function($a, $b) use ($o) {
                if ($a[$o['column']] == $b[$o['column']]) {
                    return 0;
                }
                if ($o['dir'] == 'desc') {
                    return ($a[$o['column']] > $b[$o['column']]) ? -1 : 1;
                } else {
                    return ($a[$o['column']] < $b[$o['column']]) ? -1 : 1;
                }
            });
        }

        $data = [
            'draw' => $draw,
            'recordsTotal' => count($resultData),
            'recordsFiltered' => count($filtered),
            'data' => array_slice($filtered, $start, $length)
        ];

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($data));

        return $response;
    }

}
