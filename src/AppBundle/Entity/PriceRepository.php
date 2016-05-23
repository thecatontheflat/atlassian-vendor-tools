<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PriceRepository extends EntityRepository
{
    /**
     * @param $pluginKey
     * @param $edition
     *
     * @return Price
     */
    public function findOrCreate($pluginKey, $edition, $monthsValid)
    {
        $price = $this->findOneBy([
            'pluginKey' => $pluginKey,
            'edition' => $edition,
            'monthsValid' => $monthsValid
        ]);

        if (!$price) {
            $price = new Price();
        }

        return $price;
    }

    public function getRenewalPrice($pluginKey, $edition, $monthsValid) {
        $price = $this->findOneBy([
            'pluginKey' => $pluginKey,
            'edition' => $edition,
            'monthsValid' => $monthsValid
        ]);

        if (!$price) {
            return 0.0;
        }

        return $price->getRenewalAmount();
    }

    public function removePrices()
    {
        // Truncating to reset IDs
        $connection = $this->getEntityManager()->getConnection();
        $connection->exec('TRUNCATE TABLE price');
    }

    public function getFilteredQuery($filters)
    {
        $builder = $this->createQueryBuilder('p')
            ->orderBy('p.plugin_key', 'ASC')
            ->orderBy('p.edition', 'ASC');

        return $builder->getQuery();
    }
}
