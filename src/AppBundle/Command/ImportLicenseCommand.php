<?php

namespace AppBundle\Command;

use AppBundle\Entity\License;
use AppBundle\Helper\Setter;
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
        $mailChimp = $container->get('app.service.mailchimp')->setOutput($output);
        $em = $container->get('doctrine')->getManager();
        $licenseRepository = $container->get('doctrine')->getRepository('AppBundle:License');
        $addonRepository = $container->get('doctrine')->getRepository('AppBundle:Addon');
        $companyRepository = $container->get('doctrine')->getRepository('AppBundle:Company');

        if ($input->getArgument('file')) {
            $licenses = $this->getLocalFile();
        } else {
            $licenses = $this->getRemoteFile();
        }

        $readCnt = 0;
        $newCnt = 0;

        foreach ($licenses as $licenseJson) {
            $addon = $addonRepository->findOrCreate($licenseJson->addonKey);
            Setter::set($licenseJson, $addon, "addonKey,addonName");
            if ($addon->isNew()) {
                $em->persist($addon);
                $em->flush($addon);
            }

            // TODO: company is seems to be unique identifier for cloud plugins. Not sure about server one?
            $company = $companyRepository->findOrCreate($licenseJson->licenseId);
            if (property_exists($licenseJson, "contactDetails")) {
                Setter::set($licenseJson->contactDetails, $company, "company,country,region");
                if (property_exists($licenseJson->contactDetails, "technicalContact")) {
                    Setter::set($licenseJson->contactDetails->technicalContact, $company, "email,name,state,phone,address1,address2,city,state,postcode,country", "technicalContact");
                }
                if (property_exists($licenseJson->contactDetails, "billingContact")) {
                    Setter::set($licenseJson->contactDetails->billingContact, $company, "email,name,phone", "billingContact");
                }
            }
            if ($company->isNew()) {
                $em->persist($company);
                $em->flush($company);
            }

            $license = $licenseRepository->findOrCreate($licenseJson->addonLicenseId);
            Setter::set($licenseJson, $license, "tier,addonLicenseId,licenseType,maintenanceStartDate,maintenanceEndDate,licenseId");
            $license
                ->setAddon($addon)
                ->setCompany($company);

            if (!$this->allowedForImport($license)) continue;

            $mailChimp->addToList($license);
            if ($license->isNew()) {
                $newCnt++;
            }
            $readCnt++;

            $em->persist($license);

            if (($readCnt % 100) == 0)
                $output->writeln(sprintf('Imported %s of %s licenses, %s new so far', $readCnt, count($licenses), $newCnt));
        }

        $em->flush();
        $output->writeln(sprintf('Imported %s licenses', count($licenses)));

        $this->getContainer()->get("app.status")->importLicenseDone();
        $output->writeln("Command completed successfully");
    }

    private function getLocalFile()
    {
        $filePath = $this->input->getArgument('file');
        $content = file_get_contents($filePath);

        return json_decode($content)->licenses;
    }

    /**
     * @codeCoverageIgnore
     */
    private function getRemoteFile()
    {
        $container = $this->getContainer();
        $urlTemplate = '/rest/2/vendors/%s/reporting/licenses';

        $vendorId = $container->getParameter('vendor_id');
        $login = $container->getParameter('vendor_email');
        $password = $container->getParameter('vendor_password');

        $url = sprintf($urlTemplate, $vendorId);
        $results = [];
        $page = null;

        try {
            do {
                if($page) {
                    $url = $page->_links->next->href;
                }
                $client = new Client();
                $response = $client->get("https://marketplace.atlassian.com".$url, ['auth' => [$login, $password]]);
                $contents = $response->getBody()->getContents();

                $page = json_decode($contents);
                $results = array_merge($results,$page->licenses);
            } while(property_exists($page,"_links") && property_exists($page->_links,"next"));
        } catch (\Exception $e) {
            $this->output->writeln($e->getMessage());

            return [];
        }
        return $results;
    }


    /**
     * The use-case of filtered add-ons is when a vendor wants to share information only relevant to a certain add-on
     *
     * @param License $license
     *
     * @return bool
     */
    private function allowedForImport(License $license)
    {
        if ($this->getContainer()->getParameter('filter_addons_enabled')) {
            $allowedKeys = $this->getContainer()->getParameter('filter_addons');
            if (in_array($license->getAddon()->getAddonKey(), $allowedKeys)) {
                return true;
            }

            return false;
        }

        return true;
    }
}