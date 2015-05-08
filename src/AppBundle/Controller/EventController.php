<?php

namespace AppBundle\Controller;

use AppBundle\Form\EventType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Event;
use \Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\HttpFoundation\RedirectResponse;


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

    /**
     * @Route("/event/new", name="event_new")
     */
    public function newAction(Request $request)
    {
        $event = new Event();

        return $this->handleForm($request, $event);
    }

    /**
     * @Route("/event/{id}/edit", name="event_edit")
     */
    public function editAction(Request $request, Event $event)
    {
        return $this->handleForm($request, $event);
    }

    /**
     * @param Request $request
     * @param Event $event
     * @return RedirectResponse|Response
     */
    private function handleForm(Request $request, Event $event)
    {
        $form = $this->createForm(new EventType(), $event, [
            'method' => 'POST'
        ]);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            return $this->redirect($this->generateUrl('events'));
        }

        return $this->render(':event:edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
