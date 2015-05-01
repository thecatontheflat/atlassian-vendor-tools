<?php

namespace AppBundle\Command;

use AppBundle\Entity\ScheduledEvent;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ScheduleEventsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:events:schedule');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $events = $this->getContainer()->getParameter('events');
        $licenseRepo = $this->getContainer()->get('doctrine')->getRepository('AppBundle:License');
        $scheduledEventRepo = $this->getContainer()->get('doctrine')->getRepository('AppBundle:ScheduledEvent');
        $em = $this->getContainer()->get('doctrine')->getManager();

        foreach ($events as $event) {
            foreach ($licenseRepo->findByEvent($event) as $license) {
                $existing = $scheduledEventRepo->findOneBy([
                    'licenseId' => $license->getLicenseId(),
                    'addonKey' => $license->getAddonKey(),
                    'name' => $event['name']
                ]);

                if ($existing) {
                    $message = sprintf('%s: %s skipped', $license->getLicenseId(), $event['name']);
                    $output->writeln($message);
                    continue;
                }

                $scheduledEvent = new ScheduledEvent();
                $scheduledEvent
                    ->setAddonKey($license->getAddonKey())
                    ->setLicenseId($license->getLicenseId())
                    ->setName($event['name'])
                    ->setStatus('new');

                $em->persist($scheduledEvent);
                $message = sprintf('%s: %s scheduled', $license->getLicenseId(), $event['name']);

                $output->writeln($message);
            }
        }

        $em->flush();

        $output->writeln('Done');
    }
}