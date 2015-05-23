<?php
namespace AppBundle\Twig;

use AppBundle\Entity\DrillRegisteredEvent;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('sort_events_by_date', array($this, 'sortByDateFilter')),
        );
    }

    /**
     * @param $collection DrillRegisteredEvent[]
     * @return mixed
     */
    public function sortByDateFilter($collection)
    {
        $sorted = [];
        foreach ($collection as $item => $event) {
            $sorted[] = $event;
        }

        usort($sorted, function($a, $b) {
            return $a->getSendDate() > $b->getSendDate();
        });

        return $sorted;
    }

    public function getName()
    {
        return 'app_extension';
    }
}