<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Sale;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    /**
     * @Route("/", name="dashboard")
     */
    public function indexAction()
    {
        $expiringSoon = $this->getDoctrine()->getRepository('AppBundle:License')
            ->findExpiringSoon();

        $starters = $this->getDoctrine()->getRepository('AppBundle:License')
            ->findStartedToday();

        $sales = $this->getDoctrine()->getRepository('AppBundle:Sale')->findAll();
        $groupedSales = [];
        foreach ($sales as $sale) {
            $this->addMonltySale($groupedSales, $sale);
        }

        $groupedSales = array_reverse($groupedSales, true);
        $groupedSales = array_slice($groupedSales, -6, 6, true);

        return $this->render(':dashboard:index.html.twig', [
            'expiringSoon' => $expiringSoon,
            'sales' => $groupedSales,
            'starters' => $starters
        ]);
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
}
