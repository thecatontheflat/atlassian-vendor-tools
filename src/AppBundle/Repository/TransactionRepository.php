<?php

namespace AppBundle\Repository;

use AppBundle\Entity\License;
use AppBundle\Entity\Transaction;
use Doctrine\ORM\EntityRepository;

class TransactionRepository extends EntityRepository
{
    public function removeSales()
    {
        // Truncating to reset IDs
        $connection = $this->getEntityManager()->getConnection();
        $connection->exec('TRUNCATE TABLE sale');
    }

    public function findTopCustomers()
    {
        $result = $this->createQueryBuilder('s')
            ->select(['s.licenseId', 's.organisationName', 'SUM(s.vendorAmount) as total'])
            ->groupBy('s.licenseId')
            ->orderBy('total', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        return $result;
    }

    public function findSalesForChart()
    {
        $sales = $this->findBy([], ['date' => 'DESC']);

        $groupedSales = [];
        foreach ($sales as $sale) {
            $this->addMonthlySale($groupedSales, $sale);
        }

        $groupedSales = array_reverse($groupedSales, true);
        $groupedSales = array_slice($groupedSales, -6, 6, true);

        return $groupedSales;
    }

    private function addMonthlySale(&$groupedSales, Transaction $transaction)
    {
        if (!isset($groupedSales[$transaction->getDate()->format('Y-m')])) {
            $monthlySale = [
                'new' => 0.00,
                'renewal' => 0.00,
                'other' => 0.00
            ];
        } else {
            $monthlySale = $groupedSales[$transaction->getDate()->format('Y-m')];
        }

        switch ($transaction->getSaleType()) {
            case 'Renewal':
                $monthlySale['renewal'] += $transaction->getVendorAmount();
                break;
            case 'New':
                $monthlySale['new'] += $transaction->getVendorAmount();
                break;
            default:
                $monthlySale['other'] += $transaction->getVendorAmount();
                break;
        }

        $groupedSales[$transaction->getDate()->format('Y-m')] = $monthlySale;
    }

    /**
     * @param License[] $licenses
     * @return array
     */
    public function findLastSalesByLicenses($licenses)
    {
        $licenseIds = [];
        foreach ($licenses as $license) {
            $licenseIds[] = $license->getLicenseId();
        }

        $result = $this->createQueryBuilder('s')
            ->select(['s.licenseId', 's.pluginKey', 's.vendorAmount', 's.date'])
            ->where('s.licenseId IN (?1)')
            ->setParameter('1', array_values($licenseIds))
            ->orderBy('s.date', 'DESC')
            ->addOrderBy('s.licenseId', 'DESC')
            ->addOrderBy('s.pluginKey', 'DESC')
            ->getQuery()
            ->getResult();

        $sales = [];
        // Kind of grouping by taking the first item from the ordered result set
        foreach ($result as $sale) {
            $key = $sale['licenseId'].$sale['pluginKey'];
            if (!empty($sales[$key])) {
                continue;
            }

            $sales[$key] = $sale;
        }

        return $sales;
    }

    public function findIfSaleIsNew($invoice, $licenseId, $pluginKey)
    {
        return count($this->createQueryBuilder('s')
                ->select(['s.invoice', 's.licenseId', 's.pluginKey'])
                ->where('s.invoice = ?1')
                ->andWhere('s.licenseId = ?2')
                ->andWhere('s.pluginKey = ?3')
                ->setParameter('1', $invoice)
                ->setParameter('2', $licenseId)
                ->setParameter('3', $pluginKey)
                ->getQuery()
                ->getResult()) == 0;
    }

    public function findEstimatedMonthlyIncome()
    {
        $beginning = new \DateTime();
        $end = new \DateTime('last day of this month');

        $licenses = $this->getEntityManager()->getRepository('AppBundle:License')
            ->createQueryBuilder('l')
            ->where('l.licenseType = ?1')
            ->andWhere('l.endDate >= :beginning')
            ->andWhere('l.endDate <= :end')
            ->setParameter('1', 'COMMERCIAL')
            ->setParameter('beginning', $beginning)
            ->setParameter('end', $end)
            ->getQuery()
            ->getResult();

        $lastSales = $this->findLastSalesByLicenses($licenses);

        $total = 0;
        foreach ($lastSales as $sale) {
            $total += $sale['vendorAmount'];
        }

        return $total;
    }

    public function findSalesByAddon()
    {
        $results = $this->createQueryBuilder('s')
            ->select(['s.pluginName', 'SUM(s.vendorAmount) as total'])
            ->groupBy('s.pluginName')
            ->getQuery()
            ->getResult();

        return $results;
    }

    public function getFilteredQuery($filters)
    {
        $builder = $this->createQueryBuilder('s')
            ->orderBy('s.date', 'DESC');

        return $builder->getQuery();
    }
}
