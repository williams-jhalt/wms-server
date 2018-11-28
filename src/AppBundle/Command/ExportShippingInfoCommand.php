<?php

namespace AppBundle\Command;

use DateTime;
use SplFileInfo;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportShippingInfoCommand extends ContainerAwareCommand {

    protected function configure() {
        $this->setName('app:export_shipping_data')
                ->setDescription('Export shipment data to CSV')
                ->addArgument('startDate', InputArgument::REQUIRED, "Start Date")
                ->addArgument('endDate', InputArgument::REQUIRED, "End Date")
                ->addArgument('output', InputArgument::REQUIRED, "Output File");
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        
        $startDate = new DateTime($input->getArgument('startDate'));
        $endDate = new DateTime($input->getArgument('endDate'));
        
        $outputFile = new SplFileInfo($input->getArgument('output'));

        $service = $this->getContainer()->get('app.export_service');
        $output->write("Beginning shipment export...\n");
        $service->exportShippingData(
                $startDate, 
                $endDate, 
                $outputFile
        );

        $output->write("Finished!\n\n");
    }

}
