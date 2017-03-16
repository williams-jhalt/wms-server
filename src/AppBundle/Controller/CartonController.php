<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/carton")
 */
class CartonController extends Controller {

    /**
     * @Route("/{company}", name="carton_index", defaults={"company" = "WTC"})
     */
    public function indexAction($company, Request $request) {
        // replace this example code with whatever you need
        return $this->render('carton/index.html.twig', [
                    'company' => $company
        ]);
    }

    /**
     * @Route("/{company}/view", name="carton_view", defaults={"company" = "WTC"})
     */
    public function viewAction($company, Request $request) {

        $manifestId = $request->get('manifestId');

        list($orderNumber, $recordSequence) = explode('-', $manifestId);

        $repo = $this->getDoctrine()->getRepository('AppBundle:Carton');

        if ($company == 'MFG') {
            $service = $this->get('app.mfg_order_service');
        } else {
            $service = $this->get('app.order_service');
        }

        $cartons = array();

        $packages = $service->getCartons($orderNumber);

        foreach ($packages as $package) {
            $cartons[] = $repo->find($package->getUcc());
        }

        return $this->render('carton/view.html.twig', [
                    'manifestId' => $manifestId,
                    'company' => $company,
                    'cartons' => $cartons
        ]);
    }

}
