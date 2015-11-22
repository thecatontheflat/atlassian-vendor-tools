<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Sale;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SaleController extends Controller
{
    /**
     * @Route("/sales", name="sales")
     */
    public function indexAction()
    {
        $sales = $this->getDoctrine()->getRepository('AppBundle:Sale')->findAll();
        $salesByAddon = $this->getDoctrine()->getRepository('AppBundle:Sale')->findSalesByAddon();

        return $this->render(':sale:index.html.twig', [
            'sales' => $sales,
            'salesByAddon' => $salesByAddon
        ]);
    }

    /**
     * @Route("/sales/{invoice}", name="sale_detail")
     */
    public function detailAction(Request $request, $invoice)
    {
        $sale = $this->getDoctrine()->getRepository('AppBundle:Sale')
            ->findOneBy(['invoice' => $invoice]);

        return $this->render(':sale:detail.html.twig', [
            'sale' => $sale,
        ]);
    }
}
