<?php

namespace AppBundle\Tests\Service;

use AppBundle\DataFixtures\LicenseFactory;
use AppBundle\Entity\DrillSchemaEvent;
use AppBundle\Service\Scheduler;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SchedulerTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    public function setUp()
    {
        self::bootKernel();
        $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
        $this->em->beginTransaction();
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->em->close();
    }

    public function testScheduler()
    {
        $drillSchemaEvent = new DrillSchemaEvent();
        $drillSchemaEvent
            ->setName('some')
            ->setDateShift('+2')
            ->setDateField('startDate')
            ->setLicenseTypeCondition('EVALUATION')
            ->setAddonKey('some.addon')
            ->setEmailTemplate('some')
            ->setEmailSubject('some')
            ->setEmailFromEmail('some')
            ->setEmailFromName('some');

        $license = LicenseFactory::createLicense(
            'SEN-111111', 'EVALUATION','Agile Values', 'Planning Poker', 'some.addon', 'Vitaliy Zurian', 'vitaliy.zurian@agile-values.com', '+49178 174 147 4', null, '+1 months'
        );

        $this->em->persist($drillSchemaEvent);
        $this->em->persist($license);
        $this->em->flush();

        $scheduler = new Scheduler($this->em);

        // Asserting that events and schemas were created
        $scheduler->schedule();
        $drillRegisteredSchemas = $this->em->getRepository('AppBundle:DrillRegisteredSchema')->findAll();
        $drillRegisteredEvents = $this->em->getRepository('AppBundle:DrillSchemaEvent')->findAll();
        $this->assertCount(1, $drillRegisteredSchemas);
        $this->assertCount(1, $drillRegisteredEvents);

        // Asserting that no consequent events and schemas were created for the same add-on
        $scheduler->schedule();
        $drillRegisteredSchemas = $this->em->getRepository('AppBundle:DrillRegisteredSchema')->findAll();
        $drillRegisteredEvents = $this->em->getRepository('AppBundle:DrillSchemaEvent')->findAll();
        $this->assertCount(1, $drillRegisteredSchemas);
        $this->assertCount(1, $drillRegisteredEvents);
    }
}