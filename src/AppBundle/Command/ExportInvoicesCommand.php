<?php

namespace AppBundle\Command;

use DateTime;
use SplFileInfo;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportInvoicesCommand extends ContainerAwareCommand {

    protected function configure() {
        $this->setName('app:export_invoices')
                ->setDescription('Export invoices to CSV')
                ->addArgument('customerNumber', InputArgument::REQUIRED, "Customer Number")
                ->addArgument('startDate', InputArgument::REQUIRED, "Start Date")
                ->addArgument('endDate', InputArgument::REQUIRED, "End Date")
                ->addArgument('headerFile', InputArgument::REQUIRED, "Header Filename")
                ->addArgument('detailFile', InputArgument::REQUIRED, "Detail Filename");
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        
        $startDate = new DateTime($input->getArgument('startDate'));
        $endDate = new DateTime($input->getArgument('endDate'));
        
        $headerFile = new SplFileInfo($input->getArgument('headerFile'));
        $detailFile = new SplFileInfo($input->getArgument('detailFile'));

        $service = $this->getContainer()->get('app.export_service');
        $output->write("Beginning invoice export...\n");
        $service->exportInvoiceData(
                $input->getArgument('customerNumber'), 
                $startDate, 
                $endDate, 
                false,
                $headerFile,
                $detailFile
        );

        $output->write("Finished!\n\n");
    }

}
