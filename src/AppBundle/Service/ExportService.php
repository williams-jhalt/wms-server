<?php

namespace AppBundle\Service;

use DateTime;
use Doctrine\ORM\EntityManager;
use SplFileInfo;
use SplFileObject;
use Williams\ConnectshipBundle\Service\ConnectshipService;
use Williams\ErpBundle\Service\ErpService;
use Williams\WmsBundle\Service\WmsService;

class ExportService {

    /**
     *
     * @var ErpService
     */
    private $erp;

    /**
     *
     * @var ConnectshipService
     */
    private $connectship;

    /**
     *
     * @var WmsService
     */
    private $muffsWms;

    /**
     *
     * @var WmsService
     */
    private $williamsWms;

    /**
     *
     * @var EntityManager
     */
    private $em;

    public function __construct(ErpService $erp, ConnectshipService $connectship, WmsService $muffsWms, WmsService $williamsWms, EntityManager $em) {
        $this->erp = $erp;
        $this->connectship = $connectship;
        $this->muffsWms = $muffsWms;
        $this->williamsWms = $williamsWms;
        $this->em = $em;
    }

    /**
     * 
     * @param string $customerNumber
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param SplFileInfo $headerFile
     * @param SplFileInfo $detailFile
     */
    public function exportInvoiceData($customerNumber, DateTime $startDate, DateTime $endDate, $consolidated = false, SplFileInfo $headerFile, SplFileInfo $detailFile) {

        $repo = $this->erp->getInvoiceRepository();
        
        $headerFh = $headerFile->openFile("wb");
        $detailFh = $detailFile->openFile("wb");
        
        $headerFh->fputcsv(['orderNumber', 'recordSequence', 'webReferenceNumber', 'customerReferenceNumber', 'invoiceDate', 'customerNumber', 'grossInvoiceAmount', 'shippingAndHandling', 'freightCharge', 'netInvoiceAmount']);
        $detailFh->fputcsv(['orderNumber', 'recordSequence', 'itemNumber', 'qtyOrdered', 'qtyBilled', 'price', 'unitOfMeasure']);
        
        $offset = 0;
        $limit = 1000;
        
        do {
            $invoices = $repo->findByCustomerAndDate($customerNumber, $startDate, $endDate, $consolidated, $limit, $offset)->getInvoices();
            foreach ($invoices as $invoice) {
                $headerFh->fputcsv([
                    $invoice->getOrderNumber(),
                    $invoice->getRecordSequence(),
                    $invoice->getWebReferenceNumber(),
                    $invoice->getCustomerPurchaseOrder(),
                    $invoice->getInvoiceDate()->format('m/d/Y'),
                    $invoice->getCustomerNumber(),
                    $invoice->getGrossInvoiceAmount(),
                    $invoice->getShippingAndHandling(),
                    $invoice->getFreightCharge(),
                    $invoice->getNetInvoiceAmount()
                ]);
                
                $items = $repo->getItems($invoice->getOrderNumber(), $invoice->getRecordSequence())->getItems();
                
                foreach ($items as $item) {
                    $detailFh->fputcsv([
                        $invoice->getOrderNumber(),
                        $invoice->getRecordSequence(),
                        $item->getItemNumber(),
                        $item->getQuantityOrdered(),
                        $item->getQuantityBilled(),
                        $item->getPrice(),
                        $item->getUnitOfMeasure()
                    ]);
                }
                
            }
            $offset += $limit;
        } while (count($invoices) > 0);
                
    }

    /**
     * Exports invoices and uploads them to specified FTP server
     * 
     * Uploaded files will be named $customerNumber_YYYYMMDD_header.csv
     * and $customerNumber_YYYYMMDD_detail.csv
     * 
     * @param string $customerNumber
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param string $hostname
     * @param string $username
     * @param string $password
     */
    public function uploadInvoicesToFtp($customerNumber, DateTime $startDate, DateTime $endDate, $hostname, $username, $password, $destination = "") {

        $headerFile = new SplFileInfo(tempnam(sys_get_temp_dir(), 'exh'));
        $detailFile = new SplFileInfo(tempnam(sys_get_temp_dir(), 'exd'));

        $this->exportInvoiceData($customerNumber, $startDate, $endDate, false, $headerFile, $detailFile);

        $headerFileName = $destination . $customerNumber . "_" . $endDate->format('Ymd') . "_header.csv";
        $detailFileName = $destination . $customerNumber . "_" . $endDate->format('Ymd') . "_detail.csv";

        $ftp = ftp_connect($hostname);
        ftp_login($ftp, $username, $password);
        ftp_put($ftp, $headerFileName, $headerFile->getPathname(), FTP_ASCII);
        ftp_put($ftp, $detailFileName, $detailFile->getPathname(), FTP_ASCII);
        ftp_close($ftp);
        
        unlink($headerFile->getPathname());
        unlink($detailFile->getPathname());
        
    }

}
