<?php

namespace AppBundle\Command;

use AppBundle\Entity\License;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportLicenseCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:import:license');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $repository = $this->getContainer()->get('doctrine')->getRepository('AppBundle:License');
        $csv = file('https://marketplace.atlassian.com/rest/1.0/vendors/1211528/license/report');
        unset($csv[0]);

        foreach ($csv as $row) {
            $row = trim($row);
            if (empty($row)) continue;

            $data = str_getcsv($row, ',');
            $license = $repository->findOrCreate($data[0], $data[3]);
            $license->setFromCSV($data);

            $em->persist($license);
        }

        $em->flush();

        $output->writeln('Done');
    }
}