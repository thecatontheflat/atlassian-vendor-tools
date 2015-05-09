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
                $mandrill->messages->send($message, true);

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

    private function contentHash($key, $value)
    {
        return [
            'name' => $key,
            'content' => $value
        ];
    }


    private function prepareMessage(License $license, ScheduledEvent $scheduledEvent)
    {
        $recipient = $this->getContainer()->getParameter('vendor_email');
        $bcc = $this->getContainer()->getParameter('vendor_email');
        $fromEmail = $this->getContainer()->getParameter('vendor_email');

        $event = $scheduledEvent->getEvent();
        $html = $event->getTemplate();

        $content = [
            $this->contentHash('FNAME', $license->getTechContactName()),
            $this->contentHash('ADDON_NAME', $license->getAddonName()),
            $this->contentHash('ADDON_KEY', $license->getAddonKey()),
            $this->contentHash('LICENSE_ID', $license->getLicenseId()),
            $this->contentHash('LICENSE_START_DATE', $license->getStartDate()->format('Y-m-d')),
            $this->contentHash('LICENSE_END_DATE', $license->getEndDate()->format('Y-m-d'))
        ];

        $message = [
            'subject' => 'TESTING - '.$event->getTopic(),
            'from_email' => $fromEmail,
            'from_name' => null,
            'to' => [['email' => $recipient]],
            'bcc_address' => $bcc,
//            'global_merge_vars' => $content,
            'html' => $html
        ];

        return $message;
    }
}