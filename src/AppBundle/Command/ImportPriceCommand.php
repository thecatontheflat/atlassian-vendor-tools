<?php

namespace AppBundle\Command;

use AppBundle\Entity\Price;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ImportPriceCommand extends ContainerAwareCommand
{
    private $container;
    private $vendorId;
    private $login;
    private $password;
    private $venderUrl;
    private $pricesUrlTemplate;
    /** @var ObjectManager */
    private $em;
    /** @var InputInterface */
    private $input;

    protected function configure()
    {
        $this->setName('app:import:price');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->init($input);

        $importedCnt = 0;
        $newCnt = 0;
        $repository = $this->em->getRepository('AppBundle:Price');

        try {
            $pluginKeys = $this->getVendorPluginKeys();

            $output->writeln(sprintf('Found %s vendor plugins, importing prices for all', count($pluginKeys)));
            foreach ($pluginKeys as $key) {
                $prices = $this->getPrices($key, 'server');
                $importedCnt = $importedCnt + count($prices);
                $newCnt = $newCnt + $this->savePrices($key, $prices, $repository);
                $this->em->flush();

                $prices = $this->getPrices($key, 'cloud');
                $importedCnt = $importedCnt + count($prices);
                $newCnt = $newCnt + $this->savePrices($key.'.ondemand', $prices, $repository);
                $this->em->flush();
            }

            $output->writeln(sprintf('Imported %s prices, %s new', $importedCnt, $newCnt));
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());

            return;
        }
    }

    private function getVendorPluginKeys()
    {
        $client = new Client();
        $response = $client->get(
            $this->venderUrl,
            [
                'auth' => [$this->login, $this->password]
            ]
        );

        $keys = array();
        foreach ($response->json()['_embedded']['addons'] as $plugin) {
            $keys[] = $plugin['key'];
        }

        return $keys;
    }

    private function getPrices($pluginKey, $type)
    {
        try {
            $client = new Client();
            $response = $client->get(
                sprintf($this->pricesUrlTemplate, $pluginKey, $type),
                [
                    'auth' => [$this->login, $this->password]
                ]
            );

            return $response->json()['items'];
        } catch (\Exception $e) {
            return array();
        }
    }

    private function savePrices($pluginKey, $jsonPrices, $repository)
    {
        $readCnt = 0;

        foreach ($jsonPrices as $jsonPrice) {
            $price = $repository->findOrCreate($pluginKey, $jsonPrice['editionDescription'], $jsonPrice['monthsValid']);
            $price->setFromJSON($jsonPrice, $pluginKey);
            $readCnt++;
            $this->em->persist($price);
        }

        return $readCnt;
    }

    private function init($input)
    {
        $this->container = $this->getContainer();

        $this->input = $input;

        $this->vendorId = $this->container->getParameter('vendor_id');
        $this->login = $this->container->getParameter('vendor_email');
        $this->password = $this->container->getParameter('vendor_password');
        $this->em = $this->container->get('doctrine')->getManager();

        $urlTemplate = 'https://marketplace.atlassian.com/rest/2.0-beta/addons/vendor/%s';
        $this->venderUrl = sprintf($urlTemplate, $this->vendorId);
        $this->pricesUrlTemplate = 'https://marketplace.atlassian.com/rest/2.0-beta/addons/%s/pricing/%s/live';
    }
}