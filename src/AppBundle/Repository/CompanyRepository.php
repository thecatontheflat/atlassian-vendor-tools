<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Company;
use Doctrine\ORM\EntityRepository;

class CompanyRepository extends EntityRepository
{
    /**
     * @param $addonLicenseId
     *
     * @return Company
     */
    public function findOrCreate($company)
    {
        $company = $this->findOneBy([
            'company' => $company,
        ]);

        if (!$company) {
            $company = new Company();
        }

        return $company;
    }

    public function findTopCustomers($count = 10)
    {
        $result = $this->createQueryBuilder('c')
            ->select("c, SUM(t.vendorAmount) as total")
            ->join("c.licenses","l")
            ->join("l.transactions","t")
            ->groupBy('c')
            ->orderBy('total', 'DESC')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult();

        return $result;
    }
}
