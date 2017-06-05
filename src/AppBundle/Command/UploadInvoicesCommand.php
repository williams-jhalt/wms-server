<?php

namespace AppBundle\Command;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UploadInvoicesCommand extends ContainerAwareCommand {

    protected function configure() {
        $this->setName('app:upload_invoices')
                ->setDescription('Export invoices to FTP')
                ->addArgument('customerNumber', InputArgument::REQUIRED, "Customer Number")
                ->addArgument('hostname', InputArgument::REQUIRED, "FTP Hostname")
                ->addArgument('username', InputArgument::REQUIRED, "FTP Username")
                ->addArgument('password', InputArgument::REQUIRED, "FTP Password")
                ->addArgument('startDate', InputArgument::OPTIONAL, "Start Date")
                ->addArgument('endDate', InputArgument::OPTIONAL, "End Date");
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        if ($input->hasArgument('startDate')) {
            $startDate = new DateTime($input->getArgument('startDate'));
        } else {
            $startDate = new DateTime("yesterday");
        }

        if ($input->hasArgument('endDate')) {
            $endDate = new DateTime($input->getArgument('endDate'));
        } else {
            $endDate = new DateTime("tomorrow");
        }

        $service = $this->getContainer()->get('app.export_service');
        $output->write("Beginning invoice export...\n");
        $service->uploadInvoicesToFtp(
                $input->getArgument('customerNumber'), $startDate, $endDate, $input->getArgument('hostname'), $input->getArgument('username'), $input->getArgument('password')
        );

        $output->write("Finished!\n\n");
    }

}
