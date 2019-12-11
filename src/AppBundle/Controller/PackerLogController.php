<?php

namespace AppBundle\Controller;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use ErpBundle\Service\ErpService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/packer-log")
 */
class PackerLogController extends Controller {

    /**
     * @Route("/", name="packer_log_index")
     */
    public function indexAction(Request $request) {

        $defaultEndDate = new DateTimeImmutable();
        $defaultStartDate = $defaultEndDate->sub(new DateInterval("P1W"));

        $startDate = $request->get('startDate', $defaultStartDate->format('m/d/Y'));
        $endDate = $request->get('endDate', $defaultEndDate->format('m/d/Y'));

        $erpService = $this->get('williams_erp.service');

        $repo = $erpService->getPackerLogEntryRepository();

        $entries = $repo->findByStartDateAndEndDate(new DateTime($startDate), new DateTime($endDate));

        $data = [];

        foreach ($entries->getPackerLogEntries() as $entry) {
            $userId = strtoupper($entry->getUserId());
            if (!isset($data[$userId])) {
                $data[$userId] = [];
            }
            $data[$userId][$entry->getUcc()] = $entry->getQtyShipped();
        }

        $result = [];

        foreach ($data as $userId => $t) {
            $result[] = [
                'userId' => $userId,
                'totalPackages' => count($t),
                'totalItems' => array_sum($t)
            ];
        }

        return $this->render('packer-log/index.html.twig', [
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'packerData' => $result
        ]);
    }

    /**
     * @Route("/detail/{id}", name="packer_log_detail")
     */
    public function detailAction($id) {

        $endDate = new DateTimeImmutable();
        $startDate = $endDate->sub(new DateInterval("P1M"));

        $erpService = $this->get('williams_erp.service');
        $repo = $erpService->getPackerLogEntryRepository();

        $offset = 0;
        $limit = 1000;

        $data = [
            'totalShipments' => 0,
            'totalItems' => 0
        ];

        do {

            $response = $repo->findByStartDateAndEndDate($startDate, $endDate, $limit, $offset);
            $entries = $response->getPackerLogEntries();
            
            foreach ($entries as $entry) {
                if ($entry->getUserId() == $id) {
                    $data['totalShipments'] ++;
                    $data['totalItems'] += $entry->getQtyShipped();
                }
            }

            $offset = $offset + $limit;
            
        } while (sizeof($entries) > 0);

        return $this->render('packer-log/index.html.twig', [
                    'userId' => $id,
                    'averagePackagesPerDay' => $data['totalShipments'] / 20,
                    'averageItemsPerDay' => $data['totalItems'] / 20
        ]);
    }

}
