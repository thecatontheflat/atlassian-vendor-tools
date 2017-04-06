<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Status
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Status
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $licenseImportLastExecution;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $licenseImportException;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $transactionImportLastExecution;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $transactionImportException;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $cronCommandLastExecution;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $availableUpdates;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getLicenseImportLastExecution()
    {
        return $this->licenseImportLastExecution;
    }

    /**
     * @param \DateTime $licenseImportLastExecution
     * @return $this
     */
    public function setLicenseImportLastExecution($licenseImportLastExecution)
    {
        $this->licenseImportLastExecution = $licenseImportLastExecution;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLicenseImportException()
    {
        return $this->licenseImportException;
    }

    /**
     * @param mixed $licenseImportException
     * @return $this
     */
    public function setLicenseImportException($licenseImportException)
    {
        $this->licenseImportException = $licenseImportException;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTransactionImportLastExecution()
    {
        return $this->transactionImportLastExecution;
    }

    /**
     * @param \DateTime $transactionImportLastExecution
     * @return $this
     */
    public function setTransactionImportLastExecution($transactionImportLastExecution)
    {
        $this->transactionImportLastExecution = $transactionImportLastExecution;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTransactionImportException()
    {
        return $this->transactionImportException;
    }

    /**
     * @param mixed $transactionImportException
     * @return $this
     */
    public function setTransactionImportException($transactionImportException)
    {
        $this->transactionImportException = $transactionImportException;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCronCommandLastExecution()
    {
        return $this->cronCommandLastExecution;
    }

    /**
     * @param \DateTime $cronCommandLastExecution
     * @return $this
     */
    public function setCronCommandLastExecution($cronCommandLastExecution)
    {
        $this->cronCommandLastExecution = $cronCommandLastExecution;
        return $this;
    }

    /**
     * @return string
     */
    public function getAvailableUpdates()
    {
        return $this->availableUpdates;
    }

    /**
     * @param string $availableUpdates
     * @return $this
     */
    public function setAvailableUpdates($availableUpdates)
    {
        $this->availableUpdates = $availableUpdates;
        return $this;
    }

}

