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

        $expiringSoonSales = $this->getDoctrine()->getRepository('AppBundle:Sale')
            ->findLastSalesByLicenses($expiringSoon);

        $starters = $this->getDoctrine()->getRepository('AppBundle:License')
            ->findStartedToday();

        $sales = $this->getDoctrine()->getRepository('AppBundle:Sale')
            ->findSalesForChart();

        $topCustomers = $this->getDoctrine()->getRepository('AppBundle:Sale')
            ->findTopCustomers();

        $estimatedIncome = $this->getDoctrine()->getRepository('AppBundle:Sale')
        ->findEstimatedMonthlyIncome();

        return $this->render(':dashboard:index.html.twig', [
            'expiringSoon' => $expiringSoon,
            'sales' => $sales,
            'starters' => $starters,
            'topCustomers' => $topCustomers,
            'expiringSoonSales' => $expiringSoonSales,
            'estimatedIncome' => $estimatedIncome
        ]);
    }
}
