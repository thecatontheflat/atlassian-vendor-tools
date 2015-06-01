<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DrillSchemaEvent;
use AppBundle\Entity\License;
use AppBundle\Form\DrillSchemaEventType;
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
        $form = $this->createForm(new DrillSchemaEventType(), $event, [
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
        $message = $this->prepareMessage($license, $event);
        $mandrill->messages->send($message, true);

        return $this->redirectToRoute('events');
    }

    private function prepareMessage(License $license, DrillSchemaEvent $event)
    {
        $recipients = [['email' => $this->container->getParameter('vendor_email')]];

        $html = $event->getEmailTemplate();
        $subject = $event->getEmailSubject();

        $this->replaceTemplateVariables($html, $license);
        $this->replaceTemplateVariables($subject, $license);

        $message = [
            'subject' => $subject,
            'from_email' => $event->getEmailFromEmail(),
            'from_name' => $event->getEmailFromName(),
            'to' => $recipients,
            'html' => $html
        ];

        return $message;
    }

    private function replaceTemplateVariables(&$html, License $license)
    {
        $mapping = [
            '%_TECH_CONTACT_%' => $license->getTechContactName(),
            '%_ADDON_NAME_%' => $license->getAddonName(),
            '%_ADDON_KEY_%' => $license->getAddonKey(),
            '%_LICENSE_ID_%' => $license->getLicenseId(),
            '%_LICENSE_START_DATE_%' => $license->getStartDate()->format('Y-m-d'),
            '%_LICENSE_END_DATE_%' => $license->getEndDate()->format('Y-m-d'),
        ];

        foreach ($mapping as $token => $replacement) {
            $html = str_replace($token, $replacement, $html);
        }
    }
}
