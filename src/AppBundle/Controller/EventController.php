<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ScheduledEvent;
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

    /**
     * @Route("/event/cancel/{id}", name="cancel_event")
     */
    public function cancelEvent(ScheduledEvent $event)
    {
        $event->setStatus('cancelled');
        $em = $this->getDoctrine()->getManager();
        $em->persist($event);

        $em->flush();

        return $this->redirectToRoute('event');
    }
}
