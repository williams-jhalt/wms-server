<?php

namespace AppBundle\Controller;

use AppBundle\Entity\PickerLog;
use AppBundle\Service\OrderService;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/picker-log")
 */
class PickerLogController extends Controller {

    /**
     * 
     * @return OrderService
     */
    private function getOrderService() {
        return $this->get('app.order_service');
    }

    /**
     * @Route("/", name="picker_log_index")
     */
    public function indexAction(Request $request) {
        return $this->render('picker-log/index.html.twig');
    }

    /**
     * @Route("/single-pick", name="picker_log_single_pick")
     */
    public function singlePickAction(Request $request) {
        return $this->render('picker-log/single-pick.html.twig');
    }

    /**
     * @Route("/scan", name="picker_log_scan")
     */
    public function scanAction(Request $request) {

        $manifestId = $request->get('orderNumber');
        $user = $request->get('user');
        $lineCount = $request->get('lineCount');
        $pageCount = $request->get('pageCount');

        $messages = array();

        if (!preg_match("/\d+-\d+/", $manifestId)) {
            $messages[] = "Not a valid order number";
        }

        if (sizeof($messages) == 0) {
            $scan = new PickerLog();
            $scan->setOrderNumber($manifestId);
            $scan->setUser($user);
            $scan->setLineCount($lineCount);
            $scan->setPageCount($pageCount);
            $this->getDoctrine()->getManager()->persist($scan);
            $this->getDoctrine()->getManager()->flush();
        }

        return new Response(json_encode(['messages' => $messages]));
    }

    /**
     * @Route("/single-pick-scan", name="picker_log_single_pick_scan")
     */
    public function singlePickScanAction(Request $request) {

        $manifestId = $request->get('orderNumber');
        $user = $request->get('user');

        $messages = array();

        if (!preg_match("/\d+-\d+/", $manifestId)) {
            $messages[] = "Not a valid order number";
        }

        $service = $this->getOrderService();

        list($orderNumber, $recordSequence) = explode('-', $manifestId);
        
        $items = $service->getShipmentItems($orderNumber, $recordSequence);

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
                        'SELECT o '
                        . 'FROM AppBundle:PickerLog o '
                        . 'WHERE o.orderNumber = :orderNumber '
                        . 'ORDER BY o.timestamp DESC')
                ->setParameter('orderNumber', $manifestId)
                ->setMaxResults(1);

#        if ($query->getOneOrNullResult() !== null) {
#            $messages[] = "Order has already been scanned";
#        }

        if (sizeof($messages) == 0) {
            $scan = new PickerLog();
            $scan->setOrderNumber($manifestId);
            $scan->setUser($user);
            $scan->setLineCount(count($items));
            $scan->setPageCount(ceil(count($items) / 30));
            $this->getDoctrine()->getManager()->persist($scan);
            $this->getDoctrine()->getManager()->flush();
        }

        return new Response(json_encode(['messages' => $messages]));
    }

    /**
     * @Route("/list", name="picker_log_list")
     */
    public function listAction(Request $request) {

        $scans = $this->getDoctrine()->getRepository('AppBundle:PickerLog')->findBy(array(), array('timestamp' => 'desc'), 50);

        return $this->render('picker-log/list.html.twig', ['scans' => $scans]);
    }

    /**
     * @Route("/search", name="picker_log_search")
     */
    public function searchAction(Request $request) {

        $searchTerms = $request->get('searchTerms');
        $startDate = $request->get('startDate');
        $endDate = $request->get('endDate');

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder()
                ->select('o')
                ->from('AppBundle:PickerLog', 'o');

        if (!empty($searchTerms)) {
            $qb->andWhere('o.orderNumber LIKE :search OR o.user = :user')
                    ->setParameter('search', $searchTerms . "%")
                    ->setParameter('user', $searchTerms);
        }

        if (!empty($startDate)) {
            $qb->andWhere('o.timestamp >= :startDate')
                    ->setParameter('startDate', new DateTime($startDate));
        }

        if (!empty($endDate)) {
            $qb->andWhere('o.timestamp <= :endDate')
                    ->setParameter('endDate', new DateTime($endDate));
        }

        $qb->orderBy('o.timestamp', 'desc')
                ->setMaxResults(50);

        $scans = $qb->getQuery()->getResult();

        return $this->render('picker-log/search.html.twig', ['scans' => $scans]);
    }

}
