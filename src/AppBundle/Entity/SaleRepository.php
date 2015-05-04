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
}
