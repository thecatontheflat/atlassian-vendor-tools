<?php

namespace AppBundle\Controller;

use AppBundle\Form\LicenseFilterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CompanyController extends Controller
{
    /**
     * @Route("/companies", name="companies")
     */
    public function indexAction(Request $request)
    {
        throw new \Exception("Refactor");
        $repository = $this->getDoctrine()->getRepository('AppBundle:License');
        $addonChoices = $repository->getAddonChoices();

        $filterForm = $this->createForm(new LicenseFilterType($addonChoices));
        $filterForm->submit($request);
        $filters = $filterForm->getData();

        $query = $repository->getFilteredQuery($filters);

        $paginator  = $this->get('knp_paginator');
        $licenses = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $filters['limit'] ?: 50
        );

        return $this->render(':license:list.html.twig', [
            'licenses' => $licenses,
            'filterForm' => $filterForm->createView()
        ]);
    }

    /**
     * @Route("/company/{senId}", name="company_detail")
     * @ParamConverter("company",class="AppBundle:Company")
     */
    public function detailAction(Request $request, $company)
    {
        return $this->render(':company:detail.html.twig', [
            'company' => $company
        ]);
    }
}
