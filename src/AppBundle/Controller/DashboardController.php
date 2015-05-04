<?php

namespace AppBundle\Controller;

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

        $sales = $this->getDoctrine()->getRepository('AppBundle:Sale')->findAll();
        $groupedSales = [];
        foreach ($sales as $sale) {
            if (empty($groupedSales[$sale->getDate()->format('Y-m')])) {
                $groupedSales[$sale->getDate()->format('Y-m')] = $sale->getVendorAmount();
            } else {
                $groupedSales[$sale->getDate()->format('Y-m')] += $sale->getVendorAmount();
            }
        }

        $groupedSales = array_reverse($groupedSales, true);
        $groupedSales = array_slice($groupedSales, -6, 6, true);

        return $this->render(':dashboard:index.html.twig', [
            'expiringSoon' => $expiringSoon,
            'sales' => $groupedSales
        ]);
    }
}
