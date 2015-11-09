<?php

namespace AppBundle\Command;

use AppBundle\Entity\License;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportLicenseCommand extends ContainerAwareCommand
{
    /** @var InputInterface */
    private $input;
    /** @var OutputInterface */
    private $output;

    protected function configure()
    {
        $this
            ->setName('app:import:license')
            ->addArgument('file', InputArgument::OPTIONAL, 'Import from file. Overrides remote import');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $container = $this->getContainer();
        $scheduler = $container->get('app.scheduler')->setOutput($output);;
        $mailChimp = $container->get('app.service.mailchimp')->setOutput($output);
        $em = $container->get('doctrine')->getManager();
        $repository = $container->get('doctrine')->getRepository('AppBundle:License');

        if ($input->getArgument('file')) {
            $csv = $this->getLocalFile();
        } else {
            $csv = $this->getRemoteFile();
        }

        unset($csv[0]);
        foreach ($csv as $row) {
            $row = trim($row);
            if (empty($row)) continue;

            $data = str_getcsv($row, ',');
            $license = $repository->findOrCreate($data[0], $data[3]);
            $license->setFromCSV($data);

            $mailChimp->addToList($license);

            $em->persist($license);
        }

        $em->flush();

        $output->writeln(sprintf('Imported %s licenses', count($csv)));

        $scheduler->schedule();
    }

    private function getLocalFile()
    {
        $filePath = $this->input->getArgument('file');
        $content = file_get_contents($filePath);

        return str_getcsv($content, "\n");
    }

    /**
     * @codeCoverageIgnore
     */
    private function getRemoteFile()
    {
        $container = $this->getContainer();
        $urlTemplate = 'https://marketplace.atlassian.com/rest/1.0/vendors/%s/license/report';

        $vendorId = $container->getParameter('vendor_id');
        $login = $container->getParameter('vendor_email');
        $password = $container->getParameter('vendor_password');

        $url = sprintf($urlTemplate, $vendorId);

        try {
            $client = new Client();
            $response = $client->get($url, ['auth' => [$login, $password]]);
            $contents = $response->getBody()->getContents();

            return str_getcsv($contents, "\n");

        } catch (\Exception $e) {
            $this->output->writeln($e->getMessage());

            return [];
        }
    }
}