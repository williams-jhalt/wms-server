<?php

namespace AppBundle\Controller;

use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/cubiscan")
 */
class CubiscanController extends Controller {

    /**
     * @Route("/post", name="cubiscan_post")
     */
    public function postAction(Request $request, LoggerInterface $logger) {
        if ($request->isMethod("POST")) {
            $data = json_decode($request->getContent());

            $logger->info($request->getContent());

        }
    }

}
