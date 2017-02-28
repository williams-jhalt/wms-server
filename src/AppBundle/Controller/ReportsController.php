<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/reports")
 */
class ReportsController extends Controller {
    
    /**
     * @Route("/", name="reports_index")
     */
    public function indexAction() {
        return $this->render('reports/index.html.twig');
    }

}
