<?php

namespace AppBundle\Controller;

use AppBundle\Entity\License;
use AppBundle\Form\LicenseFilterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LicenseController extends Controller
{
    /**
     * @Route("/licenses", name="licenses")
     */
    public function indexAction()
    {
        $filter = $this->createForm(new LicenseFilterType());
        $repository = $this->getDoctrine()->getRepository('AppBundle:License');
        $licenses = $repository->findAll();
        return $this->render(':license:list.html.twig', [
            'licenses' => $licenses,
            'filterForm' => $filter->createView()
        ]);
    }

    /**
     * @Route("/license/{licenseId}", name="license_detail")
     */
    public function detailAction(License $license)
    {
        return $this->render(':license:detail.html.twig', ['license' => $license]);
    }
}
