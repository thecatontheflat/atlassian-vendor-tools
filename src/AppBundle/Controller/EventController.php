<?php

namespace AppBundle\Controller;

use AppBundle\Form\EventType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Event;


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
     * @Method("GET")
     */
    public function newAction()
    {
        $entity = new Event();
        $form = $this->createForm(new EventType(), $entity, [
            'action' => $this->generateUrl('event_new_post'),
            'method' => 'POST'
        ]);

        return $this->render(':event:new.html.twig', [
            'entity' => $entity,
            'form' => $form->createView(),
        ]);
    }

    /**
     *
     * @Route("/event/new", name="event_new_post")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $entity = new Event();
        $form = $this->createForm(new EventType(), $entity, [
            'action' => $this->generateUrl('event_new'),
            'method' => 'POST'
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('event_show', ['id' => $entity->getId()]));
        }

        return $this->render(':event:new.html.twig', [
            'entity' => $entity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/event/{id}", name="event_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Event')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Template entity.');
        }

        return $this->render(':event:show.html.twig', [
            'entity' => $entity,
        ]);
    }
}
