<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DrillSchemaEvent;
use AppBundle\Entity\License;
use AppBundle\Form\DrillSchemaEventType;
use AppBundle\Form\EventType;
use AppBundle\Service\MandrillMessage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
        $events = $em->getRepository('AppBundle:DrillSchemaEvent')->findAll();

        return $this->render(':event:index.html.twig', [
            'events' => $events
        ]);
    }

    /**
     * @Route("/event/new", name="event_new")
     */
    public function newAction(Request $request)
    {
        $event = new DrillSchemaEvent();

        return $this->handleForm($request, $event);
    }

    /**
     * @Route("/event/{id}/edit", name="event_edit")
     */
    public function editAction(Request $request, DrillSchemaEvent $event)
    {
        return $this->handleForm($request, $event);
    }

    /**
     * @param Request $request
     * @param DrillSchemaEvent $event
     * @return RedirectResponse|Response
     */
    private function handleForm(Request $request, DrillSchemaEvent $event)
    {
        $addonKeys = $this->getDoctrine()->getRepository('AppBundle:License')
            ->getAddonChoices();

        $form = $this->createForm(new DrillSchemaEventType($addonKeys), $event, [
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

    /**
     * @Route("/event/{id}/send-test", name="event_send_test")
     */
    public function sendTestAction(DrillSchemaEvent $event)
    {
        $license = $this->getDoctrine()->getRepository('AppBundle:License')->findOneBy(['licenseId' => 'SEN-3253462']);
        $mandrill = $this->get('app.mandrill');
        $recipients = [['email' => $this->container->getParameter('vendor_email')]];

        $message = MandrillMessage::prepareMessage($license, $event, $recipients);

        $mandrill->messages->send($message, true);

        return $this->redirectToRoute('events');
    }
}
