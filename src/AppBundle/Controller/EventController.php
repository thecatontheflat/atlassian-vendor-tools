<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EventController extends Controller
{
    /**
     * @Route("/event", name="event")
     */
    public function indexAction()
    {
        $events = $this->container->getParameter('events');
        $scheduled = $this->getDoctrine()->getRepository('AppBundle:ScheduledEvent')->findAll();

        return $this->render(':event:index.html.twig', [
            'events' => $events,
            'scheduled' => $scheduled
        ]);
    }
}
