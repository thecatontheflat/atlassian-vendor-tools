<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Sale;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TransactionController extends Controller
{
    /**
     * @Route("/transactions", name="transactions")
     */
    public function indexAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Transaction');
        $query = $repository->getFilteredQuery([]);
        $paginator  = $this->get('knp_paginator');
        $transactions = $paginator->paginate($query, $request->query->getInt('page', 1), 50);

        return $this->render(':transactions:list.html.twig', [
            'transactions' => $transactions,
            'addons' => $this->getDoctrine()->getRepository("AppBundle:Addon")->findAll()
        ]);
    }

    /**
     * @Route("/transaction/{transactionId}", name="transaction_detail")
     */
    public function detailAction(Request $request, $transactionId)
    {
        return $this->render(':transactions:detail.html.twig', [
            'transactions' => $this->getDoctrine()->getRepository("AppBundle:Transaction")->findBy(["transactionId"=>$transactionId]),
        ]);
    }
}
