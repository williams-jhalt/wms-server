<?php

namespace AppBundle\Command;

use Exception;
use SplFileObject;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateReportsCommand extends ContainerAwareCommand {

    protected function configure() {
        $this->setName('app:generate_reports')
                ->setDescription('Generate reports');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        
        $service = $this->getContainer()->get('app.reports');
        $output->write("Beginning report generation...\n");
        try {
            $service->generateReports();
        } catch (Exception $e) {
            $output->writeln("There was an error generating the reports: " . $e->getMessage());
            $output->write($e->getTraceAsString());
        }
        $output->write("Finished!\n\n");
    }

}
