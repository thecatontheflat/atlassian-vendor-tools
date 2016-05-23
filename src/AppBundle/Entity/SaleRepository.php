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
            ->groupBy('s.licenseId', 's.organisationName')
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
            ->select(['s.licenseId', 's.pluginKey', 's.vendorAmount', 's.date', 's.discounted', 's.licenseSize', 's.maintenanceEndDate', 's.maintenanceStartDate'])
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
