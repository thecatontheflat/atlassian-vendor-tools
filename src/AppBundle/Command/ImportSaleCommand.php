<?php

namespace AppBundle\Command;

use AppBundle\Entity\Sale;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ImportSaleCommand extends ContainerAwareCommand
{
    private $container;
    private $vendorId;
    private $login;
    private $password;
    private $url;
    /** @var ObjectManager */
    private $em;

    protected function configure()
    {
        $this->setName('app:import:sale');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->init();
        $limit = 50;
        $offset = 0;
        $repository = $this->em->getRepository('AppBundle:Sale');

        try {
            $output->writeln('Removing existing sales');
            $repository->removeSales();

            $json = $this->getSales($limit, $offset);
            $this->saveSales($json['sales']);
            $output->writeln('Saving bunch of sales...');
            $total = $json['numSales'];

            do {
                $offset += $limit;
                $json = $this->getSales($limit, $offset);
                $this->saveSales($json['sales']);
                $output->writeln('Saving bunch of sales...');
            } while ($total > ($limit + $offset));

        } catch (\Exception $e) {
            $output->writeln($e->getMessage());

            return;
        }

        $this->em->flush();

        $output->writeln(sprintf('Imported %s sales', $total));
    }

    private function getSales($limit, $offset)
    {
        $client = new Client();
        $response = $client->get(
            $this->url,
            [
                'auth' => [$this->login, $this->password],
                'query' => ['limit' => $limit, 'offset' => $offset]
            ]
        );

        return $response->json();
    }

    private function saveSales($jsonSales)
    {
        foreach ($jsonSales as $jsonSale) {
            $sale = new Sale();
            $sale->setFromJSON($jsonSale);

            $this->em->persist($sale);
        }
    }

    private function init()
    {
        $this->container = $this->getContainer();

        $this->vendorId = $this->container->getParameter('vendor_id');
        $this->login = $this->container->getParameter('vendor_email');
        $this->password = $this->container->getParameter('vendor_password');
        $this->em = $this->container->get('doctrine')->getManager();

        $urlTemplate = 'https://marketplace.atlassian.com/rest/1.0/vendors/%s/sales';
        $this->url = sprintf($urlTemplate, $this->vendorId);
    }
}