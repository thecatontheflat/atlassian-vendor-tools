<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class SaleRepository extends EntityRepository
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
        $sales = $this->findAll();

        $groupedSales = [];
        foreach ($sales as $sale) {
            $this->addMonltySale($groupedSales, $sale);
        }

        $groupedSales = array_reverse($groupedSales, true);
        $groupedSales = array_slice($groupedSales, -6, 6, true);

        return $groupedSales;
    }

    private function addMonltySale(&$groupedSales, Sale $sale)
    {
        if (!isset($groupedSales[$sale->getDate()->format('Y-m')])) {
            $monthlySale = [
                'new' => 0.00,
                'renewal' => 0.00,
                'other' => 0.00
            ];
        } else {
            $monthlySale = $groupedSales[$sale->getDate()->format('Y-m')];
        }

        switch ($sale->getSaleType()) {
            case 'Renewal':
                $monthlySale['renewal'] += $sale->getVendorAmount();
                break;
            case 'New':
                $monthlySale['new'] += $sale->getVendorAmount();
                break;
            default:
                $monthlySale['other'] += $sale->getVendorAmount();
                break;
        }

        $groupedSales[$sale->getDate()->format('Y-m')] = $monthlySale;
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
            ->select(['s.licenseId', 's.vendorAmount', 's.date'])
            ->where('s.licenseId IN (?1)')
            ->setParameter('1', array_values($licenseIds))
            ->groupBy('s.licenseId')
            ->orderBy('s.licenseId', 'DESC')
            ->addOrderBy('s.date', 'DESC')
            ->getQuery()
            ->getResult();

        $sales = [];
        foreach ($result as $sale) {
            $sales[$sale['licenseId']] = $sale;
        }

        return $sales;
    }

    public function findEstimatedMonthlyIncome()
    {
        $beginning = new \DateTime('first day of this month');
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
}
