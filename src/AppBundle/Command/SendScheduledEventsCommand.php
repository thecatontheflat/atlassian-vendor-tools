<?php

namespace AppBundle\Command;

use AppBundle\Entity\DrillRegisteredEvent;
use AppBundle\Entity\License;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Mandrill;

class SendScheduledEventsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:events:send');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mandrill = $this->getContainer()->get('app.mandrill');
        $em = $this->getContainer()->get('doctrine')->getManager();
        $licenseRepo = $em->getRepository('AppBundle:License');
        $drillRegisteredEventRepo = $em->getRepository('AppBundle:DrillRegisteredEvent');

        $eventsToSend = $drillRegisteredEventRepo->findEventsToSendToday();
        foreach ($eventsToSend as $eventToSend) {
            $registeredSchema = $eventToSend->getDrillRegisteredSchema();
            $license = $licenseRepo->findOneBy([
                'licenseId' => $registeredSchema->getLicenseId(),
                'addonKey' => $registeredSchema->getAddonKey()
            ]);

            $message = $this->prepareMessage($license, $eventToSend);

            try {
                $response = $mandrill->messages->send($message, true);
                print_r($response);

                $eventToSend->setStatus('sent');
                $output->writeln(sprintf('[%s] %s - sent', $registeredSchema->getLicenseId(), $registeredSchema->getAddonKey()));
            } catch (\Exception $e) {
                $eventToSend->setStatus('error');

                $output->writeln($e->getMessage());
            }

            $em->persist($eventToSend);
            $em->flush();
        }

        $output->writeln('Done');
    }

    private function prepareMessage(License $license, DrillRegisteredEvent $registeredEvent)
    {
        $event = $registeredEvent->getDrillSchemaEvent();
        $recipient = $this->getContainer()->getParameter('vendor_email');
        $bcc = $this->getContainer()->getParameter('vendor_email');

        $html = $event->getEmailTemplate();
        $subject = $event->getEmailSubject();

        $this->replaceTemplateVariables($html, $license);
        $this->replaceTemplateVariables($subject, $license);

        $message = [
            'subject' => $subject,
            'from_email' => $event->getEmailFromEmail(),
            'from_name' => $event->getEmailFromName(),
            'to' => [['email' => $recipient]],
            'bcc_address' => $bcc,
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
            '%_ADDON_URL_%' => $this->buildAddonURL($license->getAddonKey()),
            '%_LICENSE_ID_%' => $license->getLicenseId(),
            '%_LICENSE_START_DATE_%' => $license->getStartDate()->format('Y-m-d'),
            '%_LICENSE_END_DATE_%' => $license->getEndDate()->format('Y-m-d'),
        ];

        foreach ($mapping as $token => $replacement) {
            $html = str_replace($token, $replacement, $html);
        }
    }

    private function buildAddonURL($addonKey)
    {
        $base = 'https://marketplace.atlassian.com/plugins/';
        $addonKey = str_replace('.ondemand', '', $addonKey);
        $url = $base.$addonKey;

        return $url;
    }
}