<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ScheduledEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EventController extends Controller
{
    /**
     * @Route("/events", name="events")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository('AppBundle:Event')->findAll();

        return $this->render(':event:index.html.twig', [
            'events' => $events
        ]);
    }
}
