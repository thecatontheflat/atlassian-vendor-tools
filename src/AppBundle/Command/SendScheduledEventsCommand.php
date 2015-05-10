<?php

namespace AppBundle\Command;

use AppBundle\Entity\License;
use AppBundle\Entity\ScheduledEvent;
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
        $scheduledEventRepo = $this->getContainer()->get('doctrine')->getRepository('AppBundle:ScheduledEvent');
        $licenseRepo = $this->getContainer()->get('doctrine')->getRepository('AppBundle:License');
        $em = $this->getContainer()->get('doctrine')->getManager();

        $scheduledEvents = $scheduledEventRepo->findBy(['status' => 'scheduled']);

        foreach ($scheduledEvents as $scheduledEvent) {
            $license = $licenseRepo->findOneBy(['licenseId' => $scheduledEvent->getLicenseId(), 'addonKey' => $scheduledEvent->getAddonKey()]);
            $message = $this->prepareMessage($license, $scheduledEvent);

            try {
                $response = $mandrill->messages->send($message, true);
                print_r($response);

                $scheduledEvent->setStatus('sent');
                $output->writeln(sprintf('%s: %s - sent', $scheduledEvent->getLicenseId(), $scheduledEvent->getName()));
            } catch (\Exception $e) {
                $scheduledEvent->setStatus('error');

                $output->writeln($e->getMessage());
            }

            $em->persist($scheduledEvent);
            $em->flush();
        }

        $output->writeln('Done');
    }

    private function prepareMessage(License $license, ScheduledEvent $scheduledEvent)
    {
        $recipient = $this->getContainer()->getParameter('vendor_email');
        $bcc = $this->getContainer()->getParameter('vendor_email');

        $event = $scheduledEvent->getEvent();
        $html = $event->getTemplate();

        $this->replaceTemplateVariables($html, $license);

        $message = [
            'subject' => $event->getTopic(),
            'from_email' => $event->getFromEmail(),
            'from_name' => $event->getFromName(),
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
            '%_ADDON_URL_%' => '#',
            '%_LICENSE_ID_%' => $license->getLicenseId(),
            '%_LICENSE_START_DATE_%' => $license->getStartDate()->format('Y-m-d'),
            '%_LICENSE_END_DATE_%' => $license->getEndDate()->format('Y-m-d'),
        ];

        foreach ($mapping as $token => $replacement) {
            str_replace($token, $replacement, $html);
        }
    }
}