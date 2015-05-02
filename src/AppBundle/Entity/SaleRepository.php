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
}
