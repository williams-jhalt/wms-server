<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/picker-metrics")
 */
class PickerMetricsController extends Controller {

    /**
     * @Route("/", name="picker_metrics_index")
     */
    public function indexAction(Request $request) {
        return $this->render('picker-metrics/index.html.twig');
    }

}
