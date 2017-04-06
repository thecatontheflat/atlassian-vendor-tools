<?php

namespace AppBundle\Tests\Command;

use AppBundle\Command\ImportLicenseCommand;
use AppBundle\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use AppBundle\Tests\BaseTest;

class ImportTransactionCommandTest extends BaseTest
{
    /**
     * @group import2
     */
    public function testExecute()
    {
        $application = new Application(self::$kernel);
        $application->add(new ImportLicenseCommand());
        $transactionRepo = $this->getEntityManager()->getRepository("AppBundle:Transaction");
        $companyRepo = $this->getEntityManager()->getRepository("AppBundle:Company");
        $addonRepo = $this->getEntityManager()->getRepository("AppBundle:Addon");
        $licenseRepo = $this->getEntityManager()->getRepository("AppBundle:License");

        $this->assertEmpty($transactionRepo->findOneBy(["transactionId"=>"AT-00000004"]));
        $this->assertEquals(3.75,$transactionRepo->findEstimatedIncome("2017-01-10","2017-01-30"));

        $this->assertNotEmpty($company1 = $companyRepo->findOneBy(["senId"=>"SEN-0000003"]));
        $this->assertNotEmpty($company1License = $licenseRepo->findOneBy(["addonLicenseId"=>"1111113"]));
        $this->assertEquals(2,count($company1License->getTransactions()));
        $this->assertEquals(7.5,$company1License->getTotalVendorAmount());

        $this->assertNotEmpty($company2 = $companyRepo->findOneBy(["senId"=>"SEN-0000004"]));
        $this->assertNotEmpty($company2License = $licenseRepo->findOneBy(["addonLicenseId"=>"1111114"]));
        $this->assertEquals(1,count($company2License->getTransactions()));
        $this->assertEquals(3.75,$company2License->getTotalVendorAmount());

        $this->assertNotEmpty($fixturesAddon = $addonRepo->findOneBy(["addonKey"=>"net.fake.addon1"]));

        $command = $application->find('app:import:transaction');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'file' => 'src/AppBundle/Tests/_json/transaction.json'
        ]);
        $this->getEntityManager()->clear();

        $this->assertNotEmpty($newTransaction = $transactionRepo->findOneBy(["transactionId"=>"AT-00000004"]));

        $this->assertNotEmpty($company1 = $companyRepo->findOneBy(["senId"=>"SEN-0000003"]));
        $this->assertNotEmpty($company1License = $licenseRepo->findOneBy(["addonLicenseId"=>"1111113"]));
        $this->assertEquals(2,count($company1License->getTransactions()));
        $this->assertEquals(7.5,$company1License->getTotalVendorAmount());

        $this->assertNotEmpty($company2 = $companyRepo->findOneBy(["senId"=>"SEN-0000004"]));
        $this->assertNotEmpty($company2License = $licenseRepo->findOneBy(["addonLicenseId"=>"1111114"]));
        $this->assertEquals(2,count($company2License->getTransactions()));
        $this->assertEquals(7.5,$company2License->getTotalVendorAmount());

        $this->assertEquals(0,$transactionRepo->findEstimatedIncome("2017-01-10","2017-01-30"));

    }
}