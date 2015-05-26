<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Sale;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SaleController extends Controller
{
    /**
     * @Route("/sales", name="sales")
     */
    public function indexAction()
    {
        $salesByAddon = $this->getDoctrine()->getRepository('AppBundle:Sale')->findSalesByAddon();

        return $this->render(':sale:index.html.twig', [
            'salesByAddon' => $salesByAddon
        ]);
    }
}
