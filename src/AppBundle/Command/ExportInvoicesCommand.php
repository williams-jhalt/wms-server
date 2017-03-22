<?php

namespace AppBundle\Command;

use DateTime;
use Exception;
use SplFileInfo;
use SplFileObject;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportInvoicesCommand extends ContainerAwareCommand {

    protected function configure() {
        $this->setName('app:export_invoices')
                ->setDescription('Export invoices to FTP')
                ->addArgument('customerNumber', InputArgument::REQUIRED, "Customer Number")
                ->addArgument('startDate', InputArgument::REQUIRED, "Start Date for Export (Y/M/D)")
                ->addArgument('endDate', InputArgument::REQUIRED, "End Date for Export (Y/M/D)")
                ->addArgument('hostname', InputArgument::REQUIRED, "FTP Hostname")
                ->addArgument('username', InputArgument::REQUIRED, "FTP Username")
                ->addArgument('password', InputArgument::REQUIRED, "FTP Password");
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $service = $this->getContainer()->get('app.export_service');
        $output->write("Beginning invoice export...\n");
        $service->uploadInvoicesToFtp(
                $input->getArgument('customerNumber'), 
                new DateTime($input->getArgument('startDate')), 
                new DateTime($input->getArgument('endDate')), 
                $input->getArgument('hostname'),
                $input->getArgument('username'),
                $input->getArgument('password')
        );

        $output->write("Finished!\n\n");
    }

}
