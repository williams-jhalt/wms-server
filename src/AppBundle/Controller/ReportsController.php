<?php

namespace AppBundle\Controller;

use AppBundle\Service\OrderService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/reports")
 */
class ReportsController extends Controller {

    /**
     * @return OrderService
     */
    private function getOrderService() {
        return $this->get('app.order_service');
    }

    /**
     * @Route("/", name="reports_index")
     */
    public function indexAction(Request $request) {

        $reports = [];

        $finder = new Finder();
        $finder->files()->name("*.json")->in(__DIR__ . '/../../../web/data/');

        foreach ($finder as $file) {
            $reports[] = [
                'type' => $file->getBasename(".json"),
                'filename' => $request->getBasePath() . "/data/" . $file->getBasename()
            ];
        }

        return $this->render('reports/index.html.twig', [
                    'reports' => $reports
        ]);
    }

}
