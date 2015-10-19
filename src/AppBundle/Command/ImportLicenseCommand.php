<?php

namespace AppBundle\Command;

use AppBundle\Entity\License;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportLicenseCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:import:license');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $urlTemplate = 'https://marketplace.atlassian.com/rest/1.0/vendors/%s/license/report';
        $container = $this->getContainer();
        $scheduler = $container->get('app.scheduler')->setOutput($output);;

        $contactAdder = $container->get('app.contact.adder');

        $vendorId = $container->getParameter('vendor_id');
        $login = $container->getParameter('vendor_email');
        $password = $container->getParameter('vendor_password');
        $em = $container->get('doctrine')->getManager();
        $repository = $container->get('doctrine')->getRepository('AppBundle:License');

        $url = sprintf($urlTemplate, $vendorId);

        try {
            $client = new Client();
            $response = $client->get($url, ['auth' =>  [$login, $password]]);
            $contents = $response->getBody()->getContents();
            $csv = str_getcsv($contents, "\n");

        } catch (\Exception $e) {
            $output->writeln($e->getMessage());

            return;
        }
        unset($csv[0]);

        $readCnt = 0;
        $newCnt = 0;

        foreach ($csv as $row) {
            $row = trim($row);
            if (empty($row)) continue;

            $data = str_getcsv($row, ',');
            $license = $repository->findOrCreate($data[0], $data[3]);
            $exists = $license->getLicenseId() !== null;
            $license->setFromCSV($data);
            $em->persist($license);

            if (!$exists) {
                $contactAdder->addFrom($license);
                $newCnt++;
            }
            $readCnt++;

            if (($readCnt % 100) == 0)
                $output->writeln(sprintf('Imported %s of %s licenses, %s new so far', $readCnt, count($csv), $newCnt));
        }

        $em->flush();

        $output->writeln(sprintf('Imported %s licenses', count($csv)));

        $scheduler->schedule();
    }
}