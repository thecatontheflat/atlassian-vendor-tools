<?php

namespace AppBundle\Controller;

use AppBundle\Entity\License;
use AppBundle\Form\LicenseFilterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LicenseController extends Controller
{
    /**
     * @Route("/licenses", name="licenses")
     */
    public function indexAction(Request $request)
    {
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
     * @Route("/license/{licenseId}", name="license_detail")
     */
    public function detailAction(Request $request, $licenseId)
    {
        $licenses = $this->getDoctrine()->getRepository('AppBundle:License')
            ->findBy(['licenseId' => $licenseId]);

        $sales = $this->getDoctrine()->getRepository('AppBundle:Sale')
            ->findBy(['licenseId' => $licenseId], ['date' => 'DESC']);

        return $this->render(':license:detail.html.twig', [
            'licenses' => $licenses,
            'sales' => $sales
        ]);
    }
}
