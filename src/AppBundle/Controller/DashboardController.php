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

        $recent = $this->getDoctrine()->getRepository('AppBundle:License')
            ->findRecent();

        $sales = $this->getDoctrine()->getRepository('AppBundle:Transaction')
            ->findTransactionsForChart();

        $topLicenses = $this->getDoctrine()->getRepository('AppBundle:License')
            ->findTopLicenses();

        // TODO: add top customers?

        $estimatedIncome =  $this->getDoctrine()->getRepository('AppBundle:Transaction')->findEstimatedMonthlyIncome();

        return $this->render(':dashboard:index.html.twig', [
            'expiringSoon' => $expiringSoon,
            'sales' => $sales,
            'recent' => $recent,
            'topLicenses' => $topLicenses,
            'estimatedIncome' => $estimatedIncome,
        ]);
    }
}
