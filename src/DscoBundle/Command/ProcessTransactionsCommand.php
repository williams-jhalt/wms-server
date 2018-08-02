<?php

namespace DscoBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessTransactionsCommand extends ContainerAwareCommand {

    protected function configure() {
        $this->setName('dsco:process')
                ->setDescription('Process Transactions through Dsco');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $service = $this->getContainer()->get('app.dsco');
        $output->write("Beginning EDI process...\n");
        $output->write("Retrieving Orders...");
        $service->retrieveOrders();
        $output->write("Done\n");
        $output->write("Acknowledging Receipt...");
        $service->acknowledgeReceipt();
        $output->write("Done\n");
        $output->write("Submitting Shipments...");
        $service->submitShipments();
        $output->write("Done\n");
        $output->write("Submitting Invoices...");
        $service->submitInvoices();
        $output->write("Done\n");        
        $output->write("Updating Inventory...");        
        $service->updateInventory();
        $output->write("Done\n");        
        $output->write("Finished!\n\n");
    }
    
}