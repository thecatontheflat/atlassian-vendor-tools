<?php

namespace AppBundle\Entity;

use AppBundle\Helper\Setter;
use Doctrine\ORM\Mapping as ORM;

/**
 * License
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LicenseRepository")
 */
class License
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
     * @var integer
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $addonLicenseId;

    /**
     * @var Company
     *
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="licenses", cascade={"persist"})
     */
    private $company;

    /**
     * @var Addon
     *
     * @ORM\ManyToOne(targetEntity="Addon", inversedBy="licenses", cascade={"persist"})
     */
    private $addon;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $edition; // TODO: cant find it in https://developer.atlassian.com/market/api/2/reference/resource/vendors/%7BvendorId%7D/reporting/licenses

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $licenseType;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $maintenanceStartDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $maintenanceEndDate;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $renewalAction;

    /**
     * @ORM\ManyToOne(targetEntity="Transaction", inversedBy="license")
     */
    private $transactions;

    /**
     * @var string
     * License Size
     *
     * @ORM\Column(type="string", length=255)
     */
    private $tier;


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
     * Set addonLicenseId
     *
     * @param string $addonLicenseId
     * @return License
     */
    public function setAddonLicenseId($addonLicenseId)
    {
        $this->addonLicenseId = $addonLicenseId;

        return $this;
    }

    /**
     * Get addonLicenseId
     *
     * @return string 
     */
    public function getAddonLicenseId()
    {
        return $this->addonLicenseId;
    }

    /**
     * Set edition
     *
     * @param string $edition
     * @return License
     */
    public function setEdition($edition)
    {
        $this->edition = $edition;

        return $this;
    }

    /**
     * Get edition
     *
     * @return string 
     */
    public function getEdition()
    {
        return $this->edition;
    }

    /**
     * Set licenseType
     *
     * @param string $licenseType
     * @return License
     */
    public function setLicenseType($licenseType)
    {
        $this->licenseType = $licenseType;

        return $this;
    }

    /**
     * Get licenseType
     *
     * @return string 
     */
    public function getLicenseType()
    {
        return $this->licenseType;
    }

    /**
     * @return \DateTime
     */
    public function getMaintenanceStartDate()
    {
        return $this->maintenanceStartDate;
    }

    /**
     * @param \DateTime $maintenanceStartDate
     * @return $this
     */
    public function setMaintenanceStartDate($maintenanceStartDate)
    {
        if(is_string($maintenanceStartDate)) {
            $maintenanceStartDate = new \DateTime($maintenanceStartDate);
        }
        $this->maintenanceStartDate = $maintenanceStartDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getMaintenanceEndDate()
    {
        return $this->maintenanceEndDate;
    }

    /**
     * @param \DateTime $maintenanceEndDate
     * @return $this
     */
    public function setMaintenanceEndDate($maintenanceEndDate)
    {
        if(is_string($maintenanceEndDate)) {
            $maintenanceEndDate = new \DateTime($maintenanceEndDate);
        }
        $this->maintenanceEndDate = $maintenanceEndDate;
        return $this;
    }

    /**
     * Set renewalAction
     *
     * @param string $renewalAction
     * @return License
     */
    public function setRenewalAction($renewalAction)
    {
        $this->renewalAction = $renewalAction;

        return $this;
    }

    /**
     * Get renewalAction
     *
     * @return string 
     */
    public function getRenewalAction()
    {
        return $this->renewalAction;
    }

    /**
     * Set tier
     *
     * @param string $tier
     * @return License
     */
    public function setTier($tier)
    {
        $this->tier = $tier;

        return $this;
    }

    /**
     * Get tier
     *
     * @return string 
     */
    public function getTier()
    {
        return $this->tier;
    }

    /**
     * Set company
     *
     * @param \AppBundle\Entity\Company $company
     * @return License
     */
    public function setCompany(\AppBundle\Entity\Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return \AppBundle\Entity\Company 
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set addon
     *
     * @param \AppBundle\Entity\Addon $addon
     * @return License
     */
    public function setAddon(\AppBundle\Entity\Addon $addon = null)
    {
        $this->addon = $addon;

        return $this;
    }

    /**
     * Get addon
     *
     * @return \AppBundle\Entity\Addon 
     */
    public function getAddon()
    {
        return $this->addon;
    }

    /**
     * Set transactions
     *
     * @param \AppBundle\Entity\Transaction $transactions
     * @return License
     */
    public function setTransactions(\AppBundle\Entity\Transaction $transactions = null)
    {
        $this->transactions = $transactions;

        return $this;
    }

    /**
     * Get transactions
     *
     * @return \AppBundle\Entity\Transaction 
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return $this->id == null;
    }
}
