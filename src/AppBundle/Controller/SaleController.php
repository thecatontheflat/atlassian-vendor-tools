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
    public function indexAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Sale');
        $query = $repository->getFilteredQuery([]);
        $paginator  = $this->get('knp_paginator');
        $sales = $paginator->paginate($query, $request->query->getInt('page', 1), 50);

        $salesByAddon = $repository->findSalesByAddon();

        return $this->render(':sale:list.html.twig', [
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
