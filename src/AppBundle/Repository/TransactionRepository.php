<?php

namespace AppBundle\Repository;

use AppBundle\Entity\License;
use AppBundle\Entity\Transaction;
use Doctrine\ORM\EntityRepository;

class TransactionRepository extends EntityRepository
{
    public function findTransactionsForChart()
    {
        $transactions = $this->findBy([], ['saleDate' => 'DESC']);

        $groupedTransactions = [];
        foreach ($transactions as $transaction) {
            $this->addMonthlyTransaction($groupedTransactions, $transaction);
        }

        $groupedTransactions = array_reverse($groupedTransactions, true);
        $groupedTransactions = array_slice($groupedTransactions, -6, 6, true);

        return $groupedTransactions;
    }

    private function addMonthlyTransaction(&$groupedTransactions, Transaction $transaction)
    {
        if (!isset($groupedTransactions[$transaction->getSaleDate()->format('Y-m')])) {
            $monthlyTransactionsTotals = [
                'new' => 0.00,
                'renewal' => 0.00,
                'other' => 0.00
            ];
        } else {
            $monthlyTransactionsTotals = $groupedTransactions[$transaction->getSaleDate()->format('Y-m')];
        }

        switch ($transaction->getSaleTypeStr()) {
            case 'renewal':
                $monthlyTransactionsTotals['renewal'] += $transaction->getVendorAmount();
                break;
            case 'new':
                $monthlyTransactionsTotals['new'] += $transaction->getVendorAmount();
                break;
            default:
                $monthlyTransactionsTotals['other'] += $transaction->getVendorAmount();
                break;
        }

        $groupedTransactions[$transaction->getSaleDate()->format('Y-m')] = $monthlyTransactionsTotals;
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

        /** @var $expiringInThisMonthLicenses License[] */
        $expiringInThisMonthLicenses = $this->getEntityManager()->getRepository('AppBundle:License')
            ->createQueryBuilder('l')
            ->where('l.licenseType = ?1')
            ->andWhere('l.maintenanceEndDate >= :beginning')
            ->andWhere('l.maintenanceEndDate <= :end')
            ->setParameter('1', 'COMMERCIAL')
            ->setParameter('beginning', $beginning)
            ->setParameter('end', $end)
            ->getQuery()
            ->getResult();

        $total = 0;
        foreach ($expiringInThisMonthLicenses as $license) {
            if($lastTransaction = $license->getLastTransaction()) {
                $total += $lastTransaction->getVendorAmount();
            }
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
            ->orderBy('s.saleDate', 'DESC');

        return $builder->getQuery();
    }
}
