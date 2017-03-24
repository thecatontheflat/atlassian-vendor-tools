<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Company
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Company
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
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $company;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $region;

    /**
     * @var string
     * According to https://developer.atlassian.com/market/api/2/reference/resource/vendors/%7BvendorId%7D/reporting/licenses there are no required fieds in Contact property.
     * It could create issues, if Contact would be separate entity.
     * For example, if contact will have only email showed and its changed - we cant determine is it the same contact email update (email changed) or is contact change (contact changed). etc.
     * So, using separate Contact entity could be problematical.
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $techContactName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $techContactEmail;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $techContactPhone;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $techContactAddress1;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $techContactAddress2;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $techContactCity;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $techContactState;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $techContactPostcode;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $techContactCountry;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $billingContactName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $billingContactEmail;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $billingContactPhone;

    /**
     * @var License
     *
     * @ORM\OneToMany(targetEntity="License", mappedBy="company")
     */
    private $licenses;


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
     * Set company
     *
     * @param string $company
     * @return Company
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string 
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return Company
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set region
     *
     * @param string $region
     * @return Company
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string 
     */
    public function getRegion()
    {
        return $this->region;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->licenses = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set techContactName
     *
     * @param string $techContactName
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * Add licenses
     *
     * @param \AppBundle\Entity\License $licenses
     * @return Company
     */
    public function addLicense(\AppBundle\Entity\License $licenses)
    {
        $this->licenses[] = $licenses;

        return $this;
    }

    /**
     * Remove licenses
     *
     * @param \AppBundle\Entity\License $licenses
     */
    public function removeLicense(\AppBundle\Entity\License $licenses)
    {
        $this->licenses->removeElement($licenses);
    }

    /**
     * Get licenses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLicenses()
    {
        return $this->licenses;
    }
}
