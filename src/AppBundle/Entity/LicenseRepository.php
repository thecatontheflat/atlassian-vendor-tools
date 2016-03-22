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
     * @param $event
     *
     * @return License[]
     */
    public function findForEvent(Event $event)
    {
        if ('startDate' == $event->getLicenseField()) {
            $where = 'DATE_DIFF(CURRENT_DATE(), l.startDate) = ?1';
        } else {
            $where = 'DATE_DIFF(l.endDate, CURRENT_DATE()) = ?1';
        }

        $criteria = $this->createQueryBuilder('l')
            ->where($where)
            ->andWhere('l.licenseType = ?2')
            ->setParameter('1', $event->getShiftDays())
            ->setParameter('2', $event->getLicenseType());

        if (null != $event->getAddonKey()) {
            $criteria->andWhere('l.addonKey = ?3')
                ->setParameter('3', $event->getAddonKey());
        }

        return $criteria->getQuery()->getResult();
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

    private function Erf($x) {
        $pi = 3.141592;
        $a = 0.140012;
        return ($x > 0) - ($x < 0) * sqrt(1-exp(-$x**2*(4/$pi+$a*$x**2)/(1+$a*$x**2)));
    }

    private function NormalCdf($x) {
        $loc = -12.0;
        $scale = 31.0;
        return 0.5*(1+$this->Erf(($x-$loc)/($scale*sqrt(2))));
    }

    private function getExpectedValue($endDate, $renewalType) {
        $yesterday = new \DateTime('-1 day');
        $endofmonth = new \DateTime('last day of this month');

        if ($renewalType == "AUTO_RENEW") {
            if (($endDate >= $yesterday) and ($endDate <= $endofmonth)) {
                return 1.0;
            } else {
                return 0.0;
            }
        } else {
            // Probability for renewal of a license today given the end date relative to today (normal distribution)
            $renewalRate = 0.9;
            $endOfMonthOffset = (int)$endDate->diff($endofmonth)->format("%r%a");

            return $renewalRate * $this->NormalCdf($endOfMonthOffset);
        }
    }

    private function getPrice($addonKey, $userCnt, $renewalLength) {
        return $this->getEntityManager()->getRepository('AppBundle:Price')->getRenewalPrice($addonKey, $userCnt, $renewalLength);
    }

    public function findEstimatedMonthlyIncome()
    {
        $licenses = $this->createQueryBuilder('l')
            ->where('l.licenseType in (?1)')
            ->andWhere('l.endDate >= :beginning')
            ->andWhere('l.endDate <= :end')
            ->setParameter('1', array_values(['COMMERCIAL', 'STARTER', 'ACADEMIC']))
            ->setParameter('beginning', new \DateTime('-150 days'))
            ->setParameter('end', new \DateTime('+175 days'))
            ->getQuery()
            ->getResult();

        $yesterday = new \DateTime('-1 days');
        $endofmonth = new \DateTime('last day of this month');

        $total = 0;
        foreach ($licenses as $license) {
            $lastSales = $this->getEntityManager()->getRepository('AppBundle:Sale')->findLastSalesByLicenses(array($license));
            $lastSale = reset($lastSales);

            $price = 0.8; // Take off Atlassian's share

            if ($license->getLicenseType() == 'ACADEMIC') {
                $price *= 0.5;
            }

            // Get user count
            $userCnt = $license->getEdition();
            $renewalLength = 12;
            if ($userCnt == 'Subscription') {
                $userCnt = $lastSale['licenseSize'];
                if ($lastSale['maintenanceEndDate']->diff($lastSale['maintenanceStartDate'])->days < 45) {
                    $renewalLength = 1;
                }
            }

            $price *= $this->getPrice($license->getAddonKey(), $userCnt, $renewalLength);

            // Find probability of license getting renewed this month
            $price *= $this->getExpectedValue($license->getEndDate(), $license->getRenewalAction());

            if (reset($lastSales)['discounted'] == 1) {
                $price += 0.8*$price; // Take off expert's share
            }

            $total += $price;
        }
        return $total;
    }

    /**
     * @return License[]
     */
    public function findWithoutRegisteredSchema()
    {
        $result = $this->createQueryBuilder('l')
            ->leftJoin('AppBundle:DrillRegisteredSchema', 'drs', 'WITH', 'l.licenseId = drs.licenseId AND l.addonKey = drs.addonKey')
            ->where('drs.licenseId IS NULL')
            ->andWhere('l.endDate >= CURRENT_DATE()')
            ->andWhere('l.licenseType = :licenseType')
            ->setParameter('licenseType', 'EVALUATION')
            ->getQuery()
            ->getResult();

        return $result;
    }
}
