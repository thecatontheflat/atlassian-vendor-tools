<?php

namespace AppBundle\Service;

use AppBundle\Entity\License;
use AppBundle\Entity\ScheduledEvent;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

class Scheduler
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var OutputInterface
     */
    private $output;

    public function __construct(EntityManager $em)
    {
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
        $events = $this->em->getRepository('AppBundle:Event')->findAll();

        $scheduledCount = 0;
        foreach ($events as $event) {
            foreach ($licenseRepo->findForEvent($event) as $license) {
                if ($event->hasScheduledForLicense($license)) {
                    $message = sprintf('%s: %s skipped', $license->getLicenseId(), $event->getName());
                    $this->output->writeln($message);

                    continue;
                }

                $scheduledEvent = new ScheduledEvent();
                $scheduledEvent
                    ->setAddonKey($license->getAddonKey())
                    ->setLicenseId($license->getLicenseId())
                    ->setName($event->getName())
                    ->setStatus('scheduled');

                $scheduledEvent->setEvent($event);

                $this->em->persist($event);
                $this->em->persist($scheduledEvent);
                $message = sprintf('%s: %s scheduled', $license->getLicenseId(), $event->getName());

                $this->output->writeln($message);
                $scheduledCount++;
            }
        }

        $this->em->flush();
        $this->output->writeln(sprintf('Scheduled %s events', $scheduledCount));
    }
}