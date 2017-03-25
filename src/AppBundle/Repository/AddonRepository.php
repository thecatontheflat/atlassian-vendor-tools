<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Addon;
use Doctrine\ORM\EntityRepository;

class AddonRepository extends EntityRepository
{
    /**
     * @param $addonLicenseId
     *
     * @return Addon
     */
    public function findOrCreate($addonKey)
    {
        $addon = $this->findOneBy([
            'addonKey' => $addonKey,
        ]);

        if (!$addon) {
            $addon = new Addon();
        }

        return $addon;
    }
}
