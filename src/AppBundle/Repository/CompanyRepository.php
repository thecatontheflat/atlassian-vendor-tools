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
}
