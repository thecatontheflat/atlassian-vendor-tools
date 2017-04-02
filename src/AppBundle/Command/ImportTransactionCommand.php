<?php

namespace AppBundle\Command;

use AppBundle\Entity\Sale;
use AppBundle\Entity\Transaction;
use AppBundle\Helper\Setter;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Service\SaleMailer;

class ImportTransactionCommand extends ContainerAwareCommand
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
    /** @var InputInterface */
    private $input;
    /** @var OutputInterface */
    private $output;

    private $locatedExistingLicense = false;

    protected function configure()
    {
        $this->setName('app:import:transaction')
             ->addOption(
                'new-transaction-notification',
                null,
                InputOption::VALUE_NONE,
                'Sends the email notification when a new transaction is found'
             );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->init($input);

        $limit = 50;
        $offset = 0;
        $newCnt = 0;

        try {
            do {
                $json = $this->getTransactions($limit, $offset);
//                $total = $json['numSales']; // TODO: find total transactions count in APIv2
                $newCur = $this->saveTransactions($json['transactions']);
                $newCnt = $newCnt + $newCur;


                $offset += count($json['transactions']);

                $output->writeln(sprintf('Imported %s of %s transactions, %s new so far', $offset, "TODO", $newCnt));
            } while ($newCur > 0);

        } catch (\Exception $e) {
            $output->writeln($e->getMessage());

            return;
        }

        $output->writeln(sprintf('Imported %s transactions', $offset));
    }

    private function getTransactions($limit, $offset)
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

    private function saveTransactions($jsonTransactions)
    {
        $transactionRepository = $this->em->getRepository('AppBundle:Transaction');
        $licenseRepository = $this->em->getRepository('AppBundle:License');
        $addonRepository = $this->em->getRepository('AppBundle:Addon');
        $newCnt = 0;

        foreach ($jsonTransactions as $jsonTransaction) {
            if ($transaction = $transactionRepository->findOneBy(["transactionId"=>$jsonTransaction['transactionId']])) {
                // TODO: check if transaction is changed.
            } else {
                $newCnt++;
                $transaction = new Transaction();
                $transaction->setTransactionId($jsonTransaction['transactionId']);
                $this->em->persist($transaction);
                $this->em->flush($transaction);
                if($license = $licenseRepository->findOneBy(["addonLicenseId"=>$jsonTransaction["addonLicenseId"]])) {
                    $this->locatedExistingLicense = true;
                    $transaction->setLicense($license);
                    // TODO: check if license info is changed
                } else {
                    if($this->locatedExistingLicense) {
                        // Something weird happens. We already located transaction with existing licence. So either transactions is incorrectly ordered, OR we have bug
                        // TODO: set flag
                        throw new \Exception("Lost license for transaction #".$transaction->getTransactionId());
                    }
                    continue;
                }
                Setter::set($jsonTransaction,$transaction,"transactionId");
                if(isset($jsonTransaction["customerDetails"]["company"])) {
//                  Pretty weird, but Atlassian provide full company name in transactions list and brief company name for licenses list.
//                  Probably, this should be refactored later.
//                  Could be used as $company->getCompanyTitleFixed()
                    $license->getCompany()->setCompanyFromTransaction($jsonTransaction["customerDetails"]["company"]);
                    $this->em->flush($license->getCompany());
                }

                if(!$addon = $addonRepository->findOneBy(["addonKey"=>$jsonTransaction["addonKey"]])) {
                    continue;
                }
                if($addon->getId() != $license->getAddon()->getId()) {
                    throw new \Exception("Invalid addon or license info in transaction #".$transaction->getTransactionId());
                }

                Setter::set($jsonTransaction["purchaseDetails"],$transaction,"saleDate,tier,billingPeriod,purchasePrice,vendorAmount,saleType,maintenanceStartDate,maintenanceEndDate");

                if (!$this->allowedForImport($transaction)) continue;

                $this->em->persist($transaction);

                if (true == $this->input->getOption('new-transaction-notification')) {
                    $this->saleMailer->sendEmail($transaction);
                }
            }
        }

        return $newCnt;
    }

    private function init($input)
    {
        $this->container = $this->getContainer();

        $this->input = $input;

        $this->vendorId = $this->container->getParameter('vendor_id');
        $this->login = $this->container->getParameter('vendor_email');
        $this->password = $this->container->getParameter('vendor_password');
        $this->em = $this->container->get('doctrine')->getManager();
        $this->saleMailer = $this->container->get('app.sale.mailer');

        $urlTemplate = 'https://marketplace.atlassian.com/rest/2/vendors/%s/reporting/sales/transactions';
        $this->url = sprintf($urlTemplate, $this->vendorId);
    }

    /**
     * The use-case of filtered add-ons is when a vendor wants to share information only relevant to a certain add-on
     *
     * @param Sale $sale
     *
     * @return bool
     */
    private function allowedForImport(Transaction $transaction)
    {
        if ($this->getContainer()->getParameter('filter_addons_enabled')) {
            $allowedKeys = $this->getContainer()->getParameter('filter_addons');
            if (in_array($transaction->getLicense()->getAddon()->getAddonKey(), $allowedKeys)) {
                return true;
            }

            return false;
        }

        return true;
    }
}