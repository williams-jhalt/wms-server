<?php

namespace AppBundle\Controller;

use AppBundle\Service\OrderService;
use ConnectshipBundle\Service\ConnectshipService;
use ErpBundle\Service\ErpService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route("/shipping")
 */
class ShippingController extends Controller {

    /**
     *
     * @var ConnectshipService
     */
    private $_cs;

    /**
     * @var ErpService
     */
    private $_erp;

    /**
     * 
     * @return ConnectshipService
     */
    private function getConnectShipService() {
        if ($this->_cs == null) {
            $this->_cs = $this->get('app.connectship_service');
        }
        return $this->_cs;
    }

    /**
     * 
     * @return ErpService
     */
    private function getErpService() {
        if ($this->_erp == null) {
            $this->_erp = $this->get('williams_erp.service');
        }
        return $this->_erp;
    }

    /**
     * @Route("/", name="shipping_index")
     */
    public function indexAction(SessionInterface $session) {

        $printerNames = $this->getConnectShipService()->getPrinterNames();

        return $this->render('shipping/index.html.twig', [
                    'printers' => $printerNames,
                    'selectedPrinter' => $session->get('selectedPrinter', $printerNames[0])
        ]);
    }

    /**
     * @Route("/review", name="shipping_review")
     */
    public function reviewAction(Request $request, SessionInterface $session) {

        $erp = $this->getErpService();

        $printer = $request->get('printer');
        $ucc = $request->get('ucc');

        $session->set('selectedPrinter', $printer);

        $shipment = $erp->getShipmentRepository()->getByUcc($ucc);
        
        return $this->render('shipping/review.html.twig', [
            'shipment' => $shipment
        ]);
        
    }

}
