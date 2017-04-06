<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Status as StatusEntity;

class Status
{
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function importLicenseException(\Exception $e)
    {
        // todo: send mail ?
        $this->em->flush(
            $this->getStatusEntity()->setLicenseImportException($e->getMessage())
        );
    }
    public function importLicenseDone()
    {
        $this->em->flush(
            $this->getStatusEntity()->setLicenseImportLastExecution(new \DateTime())
        );
    }
    public function importTransactionException(\Exception $e)
    {
        // todo: send mail ?
        $this->em->flush(
            $this->getStatusEntity()->setTransactionImportException($e->getMessage())
        );
    }
    public function importTransactionDone()
    {
        $this->em->flush(
            $this->getStatusEntity()->setTransactionImportLastExecution(new \DateTime())
        );
    }
    public function cronDone()
    {
        $this->em->flush(
            $this->getStatusEntity()->setCronCommandLastExecution(new \DateTime())
        );
    }
    public function setAvailableUpdates($updatesArrayOfStrings)
    {
        $this->em->flush(
            $this->getStatusEntity()->setAvailableUpdates(implode("<br>",$updatesArrayOfStrings))
        );
    }

    /**
     * @return StatusEntity
     */
    public function getStatusEntity()
    {
        if(!$status = $this->em->getRepository("AppBundle:Status")->find(1)) {
            $status = new StatusEntity();
            $this->em->persist($status);
            $this->em->flush($status);
        }
        return $status;
    }
    public function getCrontabProblems()
    {
        $problems = [];
        $lastLicenseExecution = $this->getStatusEntity()->getLicenseImportLastExecution();
        if($lastLicenseExecution && ((time() - $lastLicenseExecution->getTimestamp()) > 86400*3)) {
            $problems[] = "./app/console app:import:license was executed more, that 3 days ago (on ".$lastLicenseExecution->format("Y-m-d H:i:s").")";
        }
        $lastTransactionExecution = $this->getStatusEntity()->getTransactionImportLastExecution();
        if($lastTransactionExecution && ((time() - $lastTransactionExecution->getTimestamp()) > 86400*3)) {
            $problems[] = "./app/console app:import:transaction was executed more, that 3 days ago (on ".$lastTransactionExecution->format("Y-m-d H:i:s").")";
        }
        $lastCronExecution = $this->getStatusEntity()->getCronCommandLastExecution();
        if($lastCronExecution && ((time() - $lastCronExecution->getTimestamp()) > 86400*3)) {
            $problems[] = "./app/console app:cron was executed more, that 3 days ago (on ".$lastCronExecution->format("Y-m-d H:i:s").")";
        }
        return $problems;
    }
    public function getCrontabSetupInstructions()
    {
        $commands = [];
        if(!$this->getStatusEntity()->getLicenseImportLastExecution()) {
            $commands[] = "./app/console app:import:license";
        }
        if(!$this->getStatusEntity()->getTransactionImportLastExecution()) {
            $commands[] = "./app/console app:import:transaction";
        }
        if(!$this->getStatusEntity()->getCronCommandLastExecution()) {
            $commands[] = "./app/console app:cron";
        }
        return $commands;
    }
}