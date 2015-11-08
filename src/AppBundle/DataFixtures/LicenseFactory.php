<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\License;

class LicenseFactory
{
    /**
     * @param $licenseId
     * @param $licenseType
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
    public static function createLicense(
        $licenseId,
        $licenseType,
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
            ->setLicenseType($licenseType)
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