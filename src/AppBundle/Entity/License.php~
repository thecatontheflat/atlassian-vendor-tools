<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * License
 *
 * @ORM\Table(name="license", uniqueConstraints={@ORM\UniqueConstraint(name="license", columns={"license_id", "addon_key"})})
 * @ORM\Entity(repositoryClass="AppBundle\Entity\LicenseRepository")
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
     * @ORM\Column(name="license_id", type="string", length=255)
     */
    private $licenseId;

    /**
     * @var string
     *
     * @ORM\Column(name="organisation_name", type="string", length=255, nullable=true)
     */
    private $organisationName;

    /**
     * @var string
     *
     * @ORM\Column(name="addon_name", type="string", length=255)
     */
    private $addonName;

    /**
     * @var string
     *
     * @ORM\Column(name="addon_key", type="string", length=255)
     */
    private $addonKey;

    /**
     * @var string
     *
     * @ORM\Column(name="tech_contact_name", type="string", length=255, nullable=true)
     */
    private $techContactName;

    /**
     * @var string
     *
     * @ORM\Column(name="tech_contact_email", type="string", length=255, nullable=true)
     */
    private $techContactEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="tech_contact_phone", type="string", length=255, nullable=true)
     */
    private $techContactPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="tech_contact_address_1", type="string", length=255, nullable=true)
     */
    private $techContactAddress1;

    /**
     * @var string
     *
     * @ORM\Column(name="tech_contact_address_2", type="string", length=255, nullable=true)
     */
    private $techContactAddress2;

    /**
     * @var string
     *
     * @ORM\Column(name="tech_contact_city", type="string", length=255, nullable=true)
     */
    private $techContactCity;

    /**
     * @var string
     *
     * @ORM\Column(name="tech_contact_state", type="string", length=255, nullable=true)
     */
    private $techContactState;

    /**
     * @var string
     *
     * @ORM\Column(name="tech_contact_postcode", type="string", length=255, nullable=true)
     */
    private $techContactPostcode;

    /**
     * @var string
     *
     * @ORM\Column(name="tech_contact_country", type="string", length=255, nullable=true)
     */
    private $techContactCountry;

    /**
     * @var string
     *
     * @ORM\Column(name="billing_contact_name", type="string", length=255, nullable=true)
     */
    private $billingContactName;

    /**
     * @var string
     *
     * @ORM\Column(name="billing_contact_email", type="string", length=255, nullable=true)
     */
    private $billingContactEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="billing_contact_phone", type="string", length=255, nullable=true)
     */
    private $billingContactPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="edition", type="string", length=255, nullable=true)
     */
    private $edition;

    /**
     * @var string
     *
     * @ORM\Column(name="license_type", type="string", length=255, nullable=true)
     */
    private $licenseType;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="date", nullable=true)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="date", nullable=true)
     */
    private $endDate;

    /**
     * @var string
     *
     * @ORM\Column(name="renewal_action", type="string", length=255, nullable=true)
     */
    private $renewalAction;


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
     * Set licenseId
     *
     * @param string $licenseId
     * @return License
     */
    public function setLicenseId($licenseId)
    {
        $this->licenseId = $licenseId;

        return $this;
    }

    /**
     * Get licenseId
     *
     * @return integer 
     */
    public function getLicenseId()
    {
        return $this->licenseId;
    }

    /**
     * Set organisationName
     *
     * @param string $organisationName
     * @return License
     */
    public function setOrganisationName($organisationName)
    {
        $this->organisationName = $organisationName;

        return $this;
    }

    /**
     * Get organisationName
     *
     * @return string 
     */
    public function getOrganisationName()
    {
        return $this->organisationName;
    }

    /**
     * Set addonName
     *
     * @param string $addonName
     * @return License
     */
    public function setAddonName($addonName)
    {
        $this->addonName = $addonName;

        return $this;
    }

    /**
     * Get addonName
     *
     * @return string 
     */
    public function getAddonName()
    {
        return $this->addonName;
    }

    /**
     * Set addonKey
     *
     * @param string $addonKey
     * @return License
     */
    public function setAddonKey($addonKey)
    {
        $this->addonKey = $addonKey;

        return $this;
    }

    /**
     * Get addonKey
     *
     * @return string 
     */
    public function getAddonKey()
    {
        return $this->addonKey;
    }

    /**
     * Set techContactName
     *
     * @param string $techContactName
     * @return License
     */
    public function setTechContactName($techContactName)
    {
        $this->techContactName = $techContactName;

        return $this;
    }

    /**
     * Get techContactName
     *
     * @return string 
     */
    public function getTechContactName()
    {
        return $this->techContactName;
    }

    /**
     * Set techContactEmail
     *
     * @param string $techContactEmail
     * @return License
     */
    public function setTechContactEmail($techContactEmail)
    {
        $this->techContactEmail = $techContactEmail;

        return $this;
    }

    /**
     * Get techContactEmail
     *
     * @return string 
     */
    public function getTechContactEmail()
    {
        return $this->techContactEmail;
    }

    /**
     * Set techContactPhone
     *
     * @param string $techContactPhone
     * @return License
     */
    public function setTechContactPhone($techContactPhone)
    {
        $this->techContactPhone = $techContactPhone;

        return $this;
    }

    /**
     * Get techContactPhone
     *
     * @return string 
     */
    public function getTechContactPhone()
    {
        return $this->techContactPhone;
    }

    /**
     * Set techContactAddress1
     *
     * @param string $techContactAddress1
     * @return License
     */
    public function setTechContactAddress1($techContactAddress1)
    {
        $this->techContactAddress1 = $techContactAddress1;

        return $this;
    }

    /**
     * Get techContactAddress1
     *
     * @return string 
     */
    public function getTechContactAddress1()
    {
        return $this->techContactAddress1;
    }

    /**
     * Set techContactAddress2
     *
     * @param string $techContactAddress2
     * @return License
     */
    public function setTechContactAddress2($techContactAddress2)
    {
        $this->techContactAddress2 = $techContactAddress2;

        return $this;
    }

    /**
     * Get techContactAddress2
     *
     * @return string 
     */
    public function getTechContactAddress2()
    {
        return $this->techContactAddress2;
    }

    /**
     * Set techContactCity
     *
     * @param string $techContactCity
     * @return License
     */
    public function setTechContactCity($techContactCity)
    {
        $this->techContactCity = $techContactCity;

        return $this;
    }

    /**
     * Get techContactCity
     *
     * @return string 
     */
    public function getTechContactCity()
    {
        return $this->techContactCity;
    }

    /**
     * Set techContactState
     *
     * @param string $techContactState
     * @return License
     */
    public function setTechContactState($techContactState)
    {
        $this->techContactState = $techContactState;

        return $this;
    }

    /**
     * Get techContactState
     *
     * @return string 
     */
    public function getTechContactState()
    {
        return $this->techContactState;
    }

    /**
     * Set techContactPostcode
     *
     * @param string $techContactPostcode
     * @return License
     */
    public function setTechContactPostcode($techContactPostcode)
    {
        $this->techContactPostcode = $techContactPostcode;

        return $this;
    }

    /**
     * Get techContactPostcode
     *
     * @return string 
     */
    public function getTechContactPostcode()
    {
        return $this->techContactPostcode;
    }

    /**
     * Set techContactCountry
     *
     * @param string $techContactCountry
     * @return License
     */
    public function setTechContactCountry($techContactCountry)
    {
        $this->techContactCountry = $techContactCountry;

        return $this;
    }

    /**
     * Get techContactCountry
     *
     * @return string 
     */
    public function getTechContactCountry()
    {
        return $this->techContactCountry;
    }

    /**
     * Set billingContactName
     *
     * @param string $billingContactName
     * @return License
     */
    public function setBillingContactName($billingContactName)
    {
        $this->billingContactName = $billingContactName;

        return $this;
    }

    /**
     * Get billingContactName
     *
     * @return string 
     */
    public function getBillingContactName()
    {
        return $this->billingContactName;
    }

    /**
     * Set billingContactEmail
     *
     * @param string $billingContactEmail
     * @return License
     */
    public function setBillingContactEmail($billingContactEmail)
    {
        $this->billingContactEmail = $billingContactEmail;

        return $this;
    }

    /**
     * Get billingContactEmail
     *
     * @return string 
     */
    public function getBillingContactEmail()
    {
        return $this->billingContactEmail;
    }

    /**
     * Set billingContactPhone
     *
     * @param string $billingContactPhone
     * @return License
     */
    public function setBillingContactPhone($billingContactPhone)
    {
        $this->billingContactPhone = $billingContactPhone;

        return $this;
    }

    /**
     * Get billingContactPhone
     *
     * @return string 
     */
    public function getBillingContactPhone()
    {
        return $this->billingContactPhone;
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
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return License
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return License
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
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

    public function setFromCSV($data)
    {
        $this->setLicenseId($data[0])
            ->setOrganisationName($data[1])
            ->setAddonName($data[2])
            ->setAddonKey($data[3])
            ->setTechContactName($data[4])
            ->setTechContactEmail($data[5])
            ->setTechContactPhone($data[6])
            ->setTechContactAddress1($data[7])
            ->setTechContactAddress2($data[8])
            ->setTechContactCity($data[9])
            ->setTechContactState($data[10])
            ->setTechContactPostcode($data[11])
            ->setTechContactCountry($data[12])
            ->setBillingContactName($data[13])
            ->setBillingContactEmail($data[14])
            ->setBillingContactPhone($data[15])
            ->setEdition($data[16])
            ->setLicenseType($data[17])
            ->setStartDate(new \DateTime($data[18]))
            ->setEndDate(new \DateTime($data[19]))
            ->setRenewalAction($data[20]);
    }

    public function isNew()
    {
        return $this->id == null;
    }
}
