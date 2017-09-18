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
     * @Route("/secured/companies", name="companies")
     */
    public function indexAction(Request $request)
    {
        throw new \Exception("TODO: implement");

    }

    /**
     * @Route("/secured/company/{senId}", name="company_detail")
     * @ParamConverter("company",class="AppBundle:Company")
     */
    public function detailAction(Request $request, $company)
    {
        return $this->render(':company:detail.html.twig', [
            'company' => $company
        ]);
    }
}