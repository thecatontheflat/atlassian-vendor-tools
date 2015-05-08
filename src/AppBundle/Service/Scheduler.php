<?php

namespace AppBundle\Service;

use AppBundle\Entity\License;
use AppBundle\Entity\ScheduledEvent;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

class Scheduler
{
    private $events;
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var OutputInterface
     */
    private $output;

    public function __construct($events, EntityManager $em)
    {
        $this->events = $events;
        $this->em = $em;
        $this->output = new NullOutput();
    }

    /**
     * @param OutputInterface $output
     * @return $this
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;

        return $this;
    }

    public function schedule()
    {
        $licenseRepo = $this->em->getRepository('AppBundle:License');
        $scheduledEventRepo = $this->em->getRepository('AppBundle:ScheduledEvent');

        $scheduledCount = 0;
        foreach ($this->events as $event) {
            foreach ($licenseRepo->findByEvent($event) as $license) {
                $existing = $scheduledEventRepo->findOneBy([
                    'licenseId' => $license->getLicenseId(),
                    'addonKey' => $license->getAddonKey(),
                    'name' => $event['name']
                ]);

                if ($existing) {
                    $message = sprintf('%s: %s skipped', $license->getLicenseId(), $event['name']);
                    $this->output->writeln($message);

                    continue;
                }

                $scheduledEvent = new ScheduledEvent();
                $scheduledEvent
                    ->setAddonKey($license->getAddonKey())
                    ->setLicenseId($license->getLicenseId())
                    ->setName($event['name'])
                    ->setStatus('scheduled');

                $this->em->persist($scheduledEvent);
                $message = sprintf('%s: %s scheduled', $license->getLicenseId(), $event['name']);

                $this->output->writeln($message);
                $scheduledCount++;
            }
        }

        $this->em->flush();
        $this->output->writeln(sprintf('Scheduled %s events', $scheduledCount));
    }
}