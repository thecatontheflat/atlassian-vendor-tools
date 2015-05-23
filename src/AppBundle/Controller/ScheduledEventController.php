<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ScheduledEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ScheduledEventController extends Controller
{
    /**
     * @Route("/scheduled-events", name="scheduled_events")
     */
    public function indexAction()
    {
        $scheduled = $this->getDoctrine()->getRepository('AppBundle:DrillRegisteredEvent')
            ->findBy([], ['sendDate' => 'DESC'], 100);

        return $this->render(':scheduled:index.html.twig', [
            'scheduled' => $scheduled
        ]);
    }

    /**
     * @Route("/scheduled-event/change-status/{id}/{status}", name="scheduled_event_change_status")
     */
    public function cancelEvent(ScheduledEvent $event, $status)
    {
        $event->setStatus($status);
        $em = $this->getDoctrine()->getManager();
        $em->persist($event);

        $em->flush();

        return $this->redirectToRoute('events');
    }
}
