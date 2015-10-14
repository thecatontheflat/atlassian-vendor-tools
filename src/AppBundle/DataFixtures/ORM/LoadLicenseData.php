<?php

namespace Acme\HelloBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\License;

class LoadLicenseData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $license1 = $this->createLicense(
            'SEN-111111', 'Agile Values', 'Planning Poker', 'planning.poker', 'Vitaliy Zurian', 'vitaliy.zurian@agile-values.com', '+49178 174 147 4', null, '+1 months'
        );

        $license2 = $this->createLicense(
            'SEN-222222', 'Agile Values', 'Planning Poker', 'planning.poker', 'Vitaliy Zurian', 'vitaliy.zurian@agile-values.com', '+49178 174 147 4', '-1 months', '+5 days'
        );

        $manager->persist($license1);
        $manager->persist($license2);
        $manager->flush();
    }

    /**
     * @param $licenseId
     * @param $organisationName
     * @param $addonName
     * @param $addonKey
     * @param $techContactName
     * @param $techContactEmail
     * @param $techContactPhone
     * @param $startDate
     * @param $endDate
     *
     * @return License
     */
    private function createLicense(
        $licenseId,
        $organisationName,
        $addonName,
        $addonKey,
        $techContactName,
        $techContactEmail,
        $techContactPhone,
        $startDate,
        $endDate
    ) {
        $license = new License();
        $license
            ->setLicenseId($licenseId)
            ->setOrganisationName($organisationName)
            ->setAddonName($addonName)
            ->setAddonKey($addonKey)
            ->setTechContactName($techContactName)
            ->setTechContactEmail($techContactEmail)
            ->setTechContactPhone($techContactPhone)
            ->setStartDate(new \DateTime($startDate))
            ->setEndDate(new \DateTime($endDate));

        return $license;
    }
}