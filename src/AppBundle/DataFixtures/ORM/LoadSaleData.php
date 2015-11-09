<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Sale;

class LoadSaleData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $sale = $this->createSale(
            'COMMERCIAL',
            'Renewal',
            'SEN-3259293',
            '15 Users',
            'Norway',
            '375',
            'agile.estimation.3.0_private.ondemand',
            'AT-123123123',
            null,
            'Planning Poker',
            null,
            null,
            'Agile Values',
            false,
            '500'
        );

        $manager->persist($sale);
        $manager->flush();
    }

    /**
     * @param $licenseType
     * @param $saleType
     * @param $licenseId
     * @param $licenseSize
     * @param $country
     * @param $vendorAmount
     * @param $pluginKey
     * @param $invoice
     * @param $date
     * @param $pluginName
     * @param $maintenanceStartDate
     * @param $maintenanceEndDate
     * @param $organisationName
     * @param $discounted
     * @param $purchasePrice
     *
     * @return Sale
     */
    private function createSale(
        $licenseType,
        $saleType,
        $licenseId,
        $licenseSize,
        $country,
        $vendorAmount,
        $pluginKey,
        $invoice,
        $date,
        $pluginName,
        $maintenanceStartDate,
        $maintenanceEndDate,
        $organisationName,
        $discounted,
        $purchasePrice
    ) {
        $sale = new Sale();
        $sale
            ->setLicenseType($licenseType)
            ->setSaleType($saleType)
            ->setLicenseId($licenseId)
            ->setLicenseSize($licenseSize)
            ->setCountry($country)
            ->setVendorAmount($vendorAmount)
            ->setPluginKey($pluginKey)
            ->setPluginName($pluginName)
            ->setInvoice($invoice)
            ->setDate(new \DateTime($date))
            ->setMaintenanceStartDate(new \DateTime($maintenanceStartDate))
            ->setMaintenanceEndDate(new \DateTime($maintenanceEndDate))
            ->setOrganisationName($organisationName)
            ->setDiscounted($discounted)
            ->setPurchasePrice($purchasePrice);

        return $sale;
    }
}