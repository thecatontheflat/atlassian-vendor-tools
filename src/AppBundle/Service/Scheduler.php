<?php

namespace AppBundle\Service;

use AppBundle\Entity\DrillRegisteredEvent;
use AppBundle\Entity\DrillRegisteredSchema;
use AppBundle\Entity\DrillSchemaEvent;
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
        $drillSchemaRepo = $this->em->getRepository('AppBundle:DrillSchema');
        $licensesWithoutSchema = $licenseRepo->findWithoutRegisteredSchema();
        $drillSchemas = $drillSchemaRepo->findAllFormatted();

        foreach ($licensesWithoutSchema as $license) {
            if (empty($drillSchemas[$license->getAddonKey()])) {
                continue;
            }

            $drillSchema = $drillSchemas[$license->getAddonKey()];
            $drillSchemaEvents = $drillSchema->getDrillSchemaEvents();

            $drillRegisteredSchema = new DrillRegisteredSchema();
            $drillRegisteredSchema->setLicenseId($license->getLicenseId());
            $drillRegisteredSchema->setAddonKey($license->getAddonKey());
            $drillRegisteredSchema->setDrillSchema($drillSchema);
            $this->em->persist($drillRegisteredSchema);

            foreach ($drillSchemaEvents as $drillSchemaEvent) {
                $sendDate = $this->calculateSendDate($drillSchemaEvent, $license);
                $today = new \DateTime();
                // prevent creating events from past
                if ($sendDate < $today->modify('-2 days')) {
                    continue;
                }

                $drillRegisteredEvent = new DrillRegisteredEvent();
                $drillRegisteredEvent->setDrillRegisteredSchema($drillRegisteredSchema);
                $drillRegisteredEvent->setDrillSchemaEvent($drillSchemaEvent);

                // Calculate
                $drillRegisteredEvent->setSendDate($sendDate);
                $drillRegisteredEvent->setStatus('new');
                $this->em->persist($drillRegisteredEvent);
            }
        }

        $this->em->flush();
    }

    /**
     * @param DrillSchemaEvent $drillSchemaEvent
     * @param License $license
     *
     * @return \DateTime
     */
    private function calculateSendDate(DrillSchemaEvent $drillSchemaEvent, License $license)
    {
        $shift = $drillSchemaEvent->getDateShift();
        if ('startDate' == $drillSchemaEvent->getDateField()) {
            $direction = '+';
            $licenseDate = $license->getStartDate();

        } else { // endDate, negative shift
            $direction = '-';
            $licenseDate = $license->getEndDate();
        }

        $sendDate = new \DateTime();
        $sendDate->setTimestamp($licenseDate->getTimestamp());
        $sendDate->modify($direction.$shift.' days');

        return $sendDate;
    }
}