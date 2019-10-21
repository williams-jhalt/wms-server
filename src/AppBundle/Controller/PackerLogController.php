<?php

namespace AppBundle\Controller;

use DateInterval;
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
        
        $erpService = $this->get('williams_erp.service');
        
        $repo = $erpService->getPackerLogEntryRepository();
        
        $endDate = new DateTimeImmutable();
        $startDate = $endDate->sub(new DateInterval("P1D"));
        
        $entries = $repo->findByStartDateAndEndDate($startDate, $endDate);
        
        $data = [];
        
        foreach ($entries->getPackerLogEntries() as $entry) {
            $userId = strtoupper($entry->getUserId());
            if (isset($data[$userId])) {
                $data[$userId][$entry->getUcc()] = $entry->getQtyShipped();
            } else {
                $data[$userId] = [ $entry->getUcc() => $entry->getQtyShipped() ];
            }
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

}
