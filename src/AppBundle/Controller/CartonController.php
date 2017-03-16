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
     * @Route("/", name="carton_index")
     */
    public function indexAction(Request $request) {
        // replace this example code with whatever you need
        return $this->render('carton/index.html.twig');
    }

    /**
     * @Route("/view", name="carton_view")
     */
    public function viewAction(Request $request) {
        
        $ucc = $request->get('ucc');
        
        $repo = $this->getDoctrine()->getRepository('AppBundle:Carton');
        
        $carton = $repo->find($ucc);
        
        return $this->render('carton/view.html.twig', [
            'carton' => $carton
        ]);
        
    }

}
