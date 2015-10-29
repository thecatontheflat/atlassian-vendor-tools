<?php

namespace AppBundle\Command;

use AppBundle\Entity\Sale;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Service\SaleMailer;

class ImportSaleCommand extends ContainerAwareCommand
{
    private $container;
    private $vendorId;
    private $login;
    private $password;
    private $url;
    /** @var ObjectManager */
    private $em;
    /** @var SaleMailer */
    private $saleMailer;

    protected function configure()
    {
        $this->setName('app:import:sale');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->init();
        $limit = 50;
        $offset = 0;
        $newCnt = 0;
        $repository = $this->em->getRepository('AppBundle:Sale');

        try {
            do {
                $json = $this->getSales($limit, $offset);
                $total = $json['numSales'];
                $newCnt = $newCnt + $this->saveSales($json['sales'], $repository);
                $this->em->flush();

                $offset += count($json['sales']);

                $output->writeln(sprintf('Imported %s of %s sales, %s new so far', $offset, $total, $newCnt));
            } while ($total > $offset);

        } catch (\Exception $e) {
            $output->writeln($e->getMessage());

            return;
        }

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

    private function saveSales($jsonSales, $repository)
    {
        $newCnt = 0;

        foreach ($jsonSales as $jsonSale) {
            if ($repository->findIfSaleIsNew($jsonSale['invoice'], $jsonSale['licenseId'])) {
                $newCnt++;
                $sale = new Sale();
                $sale->setFromJSON($jsonSale);
                $this->em->persist($sale);

                if (true == $this->getContainer()->getParameter('new_sale_notification')) {
                    $this->saleMailer->sendEmail($sale);
                }
            }
        }

        return $newCnt;
    }

    private function init()
    {
        $this->container = $this->getContainer();

        $this->vendorId = $this->container->getParameter('vendor_id');
        $this->login = $this->container->getParameter('vendor_email');
        $this->password = $this->container->getParameter('vendor_password');
        $this->em = $this->container->get('doctrine')->getManager();
        $this->saleMailer = $this->container->get('app.sale.mailer');

        $urlTemplate = 'https://marketplace.atlassian.com/rest/1.0/vendors/%s/sales';
        $this->url = sprintf($urlTemplate, $this->vendorId);
    }
}