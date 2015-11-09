<?php

namespace AppBundle\Tests\Command;

use AppBundle\Command\ImportLicenseCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ImportLicenseCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $application = new Application($kernel);
        $application->add(new ImportLicenseCommand());

        $command = $application->find('app:import:license');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'file' => 'src/AppBundle/Tests/_fixtures/licenseReport.csv'
        ]);

        $this->assertContains('Imported 2 licenses', $commandTester->getDisplay());
    }
}