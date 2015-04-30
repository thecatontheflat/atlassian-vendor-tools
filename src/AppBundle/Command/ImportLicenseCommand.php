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
        $csv = file('/Users/vitaliizurian/Downloads/licenseReport.csv');
        unset($csv[0]);

        foreach ($csv as $row) {
            $row = trim($row);
            if (empty($row)) continue;

            $data = str_getcsv($row, ',');
            $license = $repository->findOrCreate($data[0], $data[3]);
            $license
                ->setLicenseId($data[0])
                ->setOrganisationName($data[1])
                ->setAddonName($data[2])
                ->setAddonKey($data[3])
                ->setTechContactName($data[4])
                ->setTechContactEmail($data[5])
                ->setTechContactPhone($data[6])
                ->setTechContactAddress1($data[7])
                ->setTechContactAddress2($data[8])
                ->setTechContactCity($data[9])
                ->setTechContactState($data[10])
                ->setTechContactPostcode($data[11])
                ->setTechContactCountry($data[12])
                ->setBillingContactName($data[13])
                ->setBillingContactEmail($data[14])
                ->setBillingContactPhone($data[15])
                ->setEdition($data[16])
                ->setLicenseType($data[17])
                ->setStartDate(new \DateTime($data[18]))
                ->setEndDate(new \DateTime($data[19]))
                ->setRenewalAction($data[20]);

            $em->persist($license);
        }

        $em->flush();

        $output->writeln('Done');
    }
}