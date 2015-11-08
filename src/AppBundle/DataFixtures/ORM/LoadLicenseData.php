<?php

namespace Acme\HelloBundle\DataFixtures\ORM;

use AppBundle\DataFixtures\LicenseFactory;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\License;

class LoadLicenseData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $license1 = LicenseFactory::createLicense(
            'SEN-111111', 'COMMERCIAL','Agile Values', 'Planning Poker', 'planning.poker', 'Vitaliy Zurian', 'vitaliy.zurian@agile-values.com', '+49178 174 147 4', null, '+1 months'
        );

        $license2 = LicenseFactory::createLicense(
            'SEN-222222', 'COMMERCIAL', 'Agile Values', 'Planning Poker', 'planning.poker', 'Vitaliy Zurian', 'vitaliy.zurian@agile-values.com', '+49178 174 147 4', '-1 months', '+5 days'
        );

        $manager->persist($license1);
        $manager->persist($license2);
        $manager->flush();
    }
}