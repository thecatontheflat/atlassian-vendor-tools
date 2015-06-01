<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DrillRegisteredSchema;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * @Route("/drill")
 */
class DrillController extends Controller
{
    /**
     * @Route("/cancel/{id}", name="drill_cancel")
     */
    public function cancelAction(DrillRegisteredSchema $drill)
    {
        $em = $this->getDoctrine()->getManager();
        foreach ($drill->getDrillRegisteredEvents() as $event) {
            if ('new' == $event->getStatus()) {
                $event->setStatus('canceled');
                $em->persist($event);
            }
        }

        $em->flush();

        return $this->redirectToRoute('license_detail', ['licenseId' => $drill->getLicenseId()]);
    }
}
