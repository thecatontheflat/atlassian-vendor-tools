<?php

namespace AppBundle\Repository;

use AppBundle\Entity\License;
use Doctrine\ORM\EntityRepository;

class LicenseRepository extends EntityRepository
{
    /**
     * @param $addonLicenseId
     *
     * @return License
     */
    public function findOrCreate($addonLicenseId)
    {
        $license = $this->findOneBy([
            'addonLicenseId' => $addonLicenseId,
        ]);

        if (!$license) {
            $license = new License();
        }

        return $license;
    }

    public function getFilteredQuery($filters)
    {
        $builder = $this->createQueryBuilder('l')
            ->orderBy('l.startDate', 'DESC');

        if (!empty($filters['addonKey'])) {
            $builder->andWhere('l.addonKey IN (:addonKeys)');
            $builder->setParameter('addonKeys', $filters['addonKey']);
        }

        if (!empty($filters['licenseType'])) {
            $builder->andWhere('l.licenseType IN (:licenseTypes)');
            $builder->setParameter('licenseTypes', $filters['licenseType']);
        }

        if (!empty($filters['search'])) {
            $search = '%'.$filters['search'].'%';

            $builder->orWhere('l.billingContactEmail LIKE :search');
            $builder->orWhere('l.billingContactName LIKE :search');
            $builder->orWhere('l.billingContactPhone LIKE :search');

            $builder->orWhere('l.techContactEmail LIKE :search');
            $builder->orWhere('l.techContactName LIKE :search');
            $builder->orWhere('l.techContactPhone LIKE :search');

            $builder->orWhere('l.organisationName LIKE :search');

            $builder->setParameter('search', $search);
        }

        return $builder->getQuery();
    }

    public function getAddonChoices()
    {
        $result = $this->createQueryBuilder('l')
            ->select(['l.addonKey', 'l.addonName'])
            ->distinct()
            ->getQuery()
            ->getResult();

        $choices = [];
        foreach ($result as $choice) {
            $choices[$choice['addonKey']] = $choice['addonName'];
        }

        return $choices;
    }

    /**
     * @return License[]
     */
    public function findExpiringSoon()
    {
        $result = $this->createQueryBuilder('l')
            ->where('DATE_DIFF(l.endDate, CURRENT_DATE()) <= ?1')
            ->andWhere('DATE_DIFF(l.endDate, CURRENT_DATE()) >= ?2')
            ->orderBy('l.endDate', 'DESC')
            ->setParameter('1', 5)
            ->setParameter('2', 0)
            ->getQuery()
            ->getResult();

        return $result;
    }

    /**
     * @return License[]
     */
    public function findRecent()
    {
        $result = $this->createQueryBuilder('l')
            ->orderBy('l.startDate', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        return $result;
    }
}
