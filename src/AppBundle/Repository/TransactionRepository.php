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

    public function findEstimatedMonthlyIncome()
    {
        return $this->findEstimatedIncome();
    }

    public function findEstimatedIncome($beginning = null, $end = null)
    {
        if(is_null($beginning)) {
            $beginning = new \DateTime();
        }elseif(is_string($beginning)) {
            $beginning = new \DateTime($beginning);
        }elseif(!($beginning instanceof \DateTime)) {
            throw new \Exception("beginning should be null, string or DateTime");
        }

        if(is_null($end)) {
            $end = new \DateTime('last day of this month');
        }elseif(is_string($end)) {
            $end = new \DateTime($end);
        }elseif(!($end instanceof \DateTime)) {
            throw new \Exception("end should be null, string or DateTime");
        }

        /** @var $expiringInThisIntervalLicenses License[] */
        $expiringInThisIntervalLicenses = $this->getEntityManager()->getRepository('AppBundle:License')
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
        foreach ($expiringInThisIntervalLicenses as $license) {
            if($lastTransaction = $license->getLastTransaction()) {
                $total += $lastTransaction->getVendorAmount();
            }
        }

        return $total;
    }

    public function getFilteredQuery($filters)
    {
        $builder = $this->createQueryBuilder('s')
            ->orderBy('s.saleDate', 'DESC');

        return $builder->getQuery();
    }
}
