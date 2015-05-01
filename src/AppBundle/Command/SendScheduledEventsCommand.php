<?php

namespace AppBundle\Command;

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
        $mandrill = new Mandrill($this->getContainer()->getParameter('mandrill_api_key'));
        $scheduledEventRepo = $this->getContainer()->get('doctrine')->getRepository('AppBundle:ScheduledEvent');
        $licenseRepo = $this->getContainer()->get('doctrine')->getRepository('AppBundle:License');
        $em = $this->getContainer()->get('doctrine')->getManager();

        $events = $scheduledEventRepo->findBy(['status' => 'new']);

        foreach ($events as $event) {
            $license = $licenseRepo->findOneBy(['licenseId' => $event->getLicenseId(), 'addonKey' => $event->getAddonKey()]);
            $recipient = $this->getContainer()->getParameter('vendor_email');
            $bcc = $this->getContainer()->getParameter('vendor_email');
            $content = [
                $this->contentHash('FNAME', $license->getTechContactName()),
                $this->contentHash('ADDON_NAME', $license->getAddonName()),
                $this->contentHash('ADDON_KEY', $license->getAddonKey()),
                $this->contentHash('LICENSE_ID', $license->getLicenseId()),
                $this->contentHash('LICENSE_START_DATE', $license->getStartDate()->format('Y-m-d')),
                $this->contentHash('LICENSE_END_DATE', $license->getEndDate()->format('Y-m-d'))
            ];

            $message = [
                'subject' => null,
                'from_email' => null,
                'from_name' => null,
                'to' => [['email' => $recipient]],
                'bcc_address' => $bcc,
                'global_merge_vars' => $content
            ];

            $mandrill->messages->sendTemplate($event->getName(), [], $message, true);

            $event->setStatus('sent');
            $em->persist($event);
            $em->flush();

            $output->writeln(sprintf('%s: %s - sent', $event->getLicenseId(), $event->getName()));
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
}