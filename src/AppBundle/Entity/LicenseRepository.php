<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class LicenseRepository extends EntityRepository
{
    /**
     * @param $licenseId
     * @param $addonKey
     *
     * @return License
     */
    public function findOrCreate($licenseId, $addonKey)
    {
        $license = $this->findOneBy([
            'licenseId' => $licenseId,
            'addonKey' => $addonKey
        ]);

        if (!$license) {
            $license = new License();
        }

        return $license;
    }

    public function findFiltered($filters)
    {
        $criteria = [];

        if (!empty($filters['addonKey'])) {
            $criteria['addonKey'] = $filters['addonKey'];
        }

        if (!empty($filters['licenseType'])) {
            $criteria['licenseType'] = $filters['licenseType'];
        }

        $order[$filters['sort_field']] = $filters['sort_direction'];
        $limit = $filters['limit'];

        return $this->findBy($criteria, $order, $limit);
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
     * @param $event
     *
     * @return License[]
     */
    public function findByEvent($event)
    {
        if ('startDate' == $event['license_field']) {
            $where = 'DATE_DIFF(CURRENT_DATE(), l.startDate) = ?1';
        } else {
            $where = 'DATE_DIFF(l.endDate, CURRENT_DATE()) = ?1';
        }

        $result = $this->createQueryBuilder('l')
            ->where($where)
            ->andWhere('l.licenseType = ?2')
            ->setParameter('1', $event['shift_days'])
            ->setParameter('2', $event['license_type'])
            ->getQuery()
            ->getResult();

        return $result;
    }

    public function findExpiringSoon()
    {
        $result = $this->createQueryBuilder('l')
            ->where('DATE_DIFF(l.endDate, CURRENT_DATE()) <= ?1')
            ->andWhere('DATE_DIFF(l.endDate, CURRENT_DATE()) >= ?2')
            ->orderBy('l.endDate', 'DESC')
            ->setParameter('1', 2)
            ->setParameter('2', 0)
            ->getQuery()
            ->getResult();

        return $result;
    }
}
