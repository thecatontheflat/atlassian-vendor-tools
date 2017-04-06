<?php

namespace AppBundle\Tests\Command;

use AppBundle\Command\ImportLicenseCommand;
use AppBundle\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use AppBundle\Tests\BaseTest;

class ImportLicenseCommandTest extends BaseTest
{
    /**
     * @group import
     */
    public function testExecute()
    {
        $application = new Application(self::$kernel);
        $application->add(new ImportLicenseCommand());
        $licenseRepo = $this->getEntityManager()->getRepository("AppBundle:License");
        $companyRepo = $this->getEntityManager()->getRepository("AppBundle:Company");
        $this->assertEmpty($licenseRepo->findOneBy(["addonLicenseId"=>1111116]));
        $this->assertEmpty($companyRepo->findOneBy(["senId"=>"SEN-0000005"]));

        $this->assertNotEmpty($fixturesLicense = $licenseRepo->findOneBy(["addonLicenseId"=>1111111]));
        $this->assertNotEmpty($fixturesLicenseCompany = $fixturesLicense->getCompany());
        $this->assertEquals("fake1",$fixturesLicenseCompany->getCompany());
        $this->assertEquals('fake@gmail.com',$fixturesLicenseCompany->getTechnicalContactEmail());
        $this->assertEquals('fake+billing@gmail.com',$fixturesLicenseCompany->getBillingContactEmail());

        $this->checkTopCustomers();

        $command = $application->find('app:import:license');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'file' => 'src/AppBundle/Tests/_json/licenses_no_contacts.json'
        ]);
        $this->getEntityManager()->clear();

        // check fixtures license without contact info update
        $this->assertNotEmpty($existingLicense = $licenseRepo->findOneBy(["addonLicenseId"=>1111111]));
        $this->assertNotEmpty($existingLicenseCompany = $fixturesLicense->getCompany());
        $this->assertEquals("fake1-updated'%~!\"<>",$existingLicenseCompany->getCompany());
        $this->assertEquals("Australia-updated'%~!\"<>",$existingLicenseCompany->getCountry());
        $this->assertEquals("APAC-updated",$existingLicenseCompany->getRegion());

        // check new license without contact info
        $this->assertNotEmpty($license = $licenseRepo->findOneBy(["addonLicenseId"=>1111116]));
        $this->assertEquals(0,count($license->getTransactions()));

        $this->assertNotEmpty($company = $license->getCompany());
        $this->assertEmpty($company->getCompany());
        $this->assertEmpty($company->getTechnicalContactEmail());
        $this->assertEmpty($company->getBillingContactEmail());
        $this->assertEmpty($company->getCountry());
        $this->assertEquals("SEN-0000005",$company->getSenId());

        $this->assertNotEmpty($addon = $license->getAddon());
        $this->assertEquals("Fake Addon #3 '%~!\"<>",$addon->getAddonName());

        $this->assertContains('Imported 2 licenses', $commandTester->getDisplay());

        // importing licenses do not change transactions, so top customers should be the same.
        $this->checkTopCustomers();
    }

    private function checkTopCustomers()
    {
        // TODO: this should be refactored to other test
        $companyRepo = $this->getEntityManager()->getRepository("AppBundle:Company");
        $fixturesTopCustomers = $companyRepo->findTopCustomers();
        $this->assertEquals(2,count($fixturesTopCustomers));
        $this->assertArrayHasKey("0",$fixturesTopCustomers);
        $this->assertArrayHasKey("1",$fixturesTopCustomers);

        $this->assertArrayHasKey("0",$fixturesTopCustomers[0]);
        $this->assertInstanceOf(Company::class,$fixturesTopCustomers[0][0]);
        $this->assertEquals("SEN-0000003",$fixturesTopCustomers[0][0]->getSenId());
        $this->assertArrayHasKey("total",$fixturesTopCustomers[0]);
        $this->assertEquals("7.50",$fixturesTopCustomers[0]["total"]);

        $this->assertArrayHasKey("0",$fixturesTopCustomers[1]);
        $this->assertInstanceOf(Company::class,$fixturesTopCustomers[1][0]);
        $this->assertEquals("SEN-0000004",$fixturesTopCustomers[1][0]->getSenId());
        $this->assertArrayHasKey("total",$fixturesTopCustomers[1]);
        $this->assertEquals("3.75",$fixturesTopCustomers[1]["total"]);
    }
}