<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Company
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CompanyRepository")
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
     * @var integer
     *
     * @ORM\Column(type="string", length=255)
     */
    private $senId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
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
     * @ORM\Column(type="string", length=255, nullable=true)
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
    private $technicalContactName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $technicalContactEmail;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $technicalContactPhone;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $technicalContactAddress1;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $technicalContactAddress2;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $technicalContactCity;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $technicalContactState;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $technicalContactPostcode;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $technicalContactCountry;

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
     * @return string
     */
    public function getTechnicalContactName()
    {
        return $this->technicalContactName;
    }

    /**
     * @param string $technicalContactName
     * @return $this
     */
    public function setTechnicalContactName($technicalContactName)
    {
        $this->technicalContactName = $technicalContactName;
        return $this;
    }

    /**
     * @return string
     */
    public function getTechnicalContactEmail()
    {
        return $this->technicalContactEmail;
    }

    /**
     * @param string $technicalContactEmail
     * @return $this
     */
    public function setTechnicalContactEmail($technicalContactEmail)
    {
        $this->technicalContactEmail = $technicalContactEmail;
        return $this;
    }

    /**
     * @return string
     */
    public function getTechnicalContactPhone()
    {
        return $this->technicalContactPhone;
    }

    /**
     * @param string $technicalContactPhone
     * @return $this
     */
    public function setTechnicalContactPhone($technicalContactPhone)
    {
        $this->technicalContactPhone = $technicalContactPhone;
        return $this;
    }

    /**
     * @return string
     */
    public function getTechnicalContactAddress1()
    {
        return $this->technicalContactAddress1;
    }

    /**
     * @param string $technicalContactAddress1
     * @return $this
     */
    public function setTechnicalContactAddress1($technicalContactAddress1)
    {
        $this->technicalContactAddress1 = $technicalContactAddress1;
        return $this;
    }

    /**
     * @return string
     */
    public function getTechnicalContactAddress2()
    {
        return $this->technicalContactAddress2;
    }

    /**
     * @param string $technicalContactAddress2
     * @return $this
     */
    public function setTechnicalContactAddress2($technicalContactAddress2)
    {
        $this->technicalContactAddress2 = $technicalContactAddress2;
        return $this;
    }

    /**
     * @return string
     */
    public function getTechnicalContactCity()
    {
        return $this->technicalContactCity;
    }

    /**
     * @param string $technicalContactCity
     * @return $this
     */
    public function setTechnicalContactCity($technicalContactCity)
    {
        $this->technicalContactCity = $technicalContactCity;
        return $this;
    }

    /**
     * @return string
     */
    public function getTechnicalContactState()
    {
        return $this->technicalContactState;
    }

    /**
     * @param string $technicalContactState
     * @return $this
     */
    public function setTechnicalContactState($technicalContactState)
    {
        $this->technicalContactState = $technicalContactState;
        return $this;
    }

    /**
     * @return string
     */
    public function getTechnicalContactPostcode()
    {
        return $this->technicalContactPostcode;
    }

    /**
     * @param string $technicalContactPostcode
     * @return $this
     */
    public function setTechnicalContactPostcode($technicalContactPostcode)
    {
        $this->technicalContactPostcode = $technicalContactPostcode;
        return $this;
    }

    /**
     * @return string
     */
    public function getTechnicalContactCountry()
    {
        return $this->technicalContactCountry;
    }

    /**
     * @param string $technicalContactCountry
     * @return $this
     */
    public function setTechnicalContactCountry($technicalContactCountry)
    {
        $this->technicalContactCountry = $technicalContactCountry;
        return $this;
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
     * @return License[]
     */
    public function getLicenses()
    {
        return $this->licenses;
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return $this->id == null;
    }

    /**
     * @return int
     */
    public function getSenId()
    {
        return $this->senId;
    }

    /**
     * @param int $senId
     * @return $this
     */
    public function setSenId($senId)
    {
        $this->senId = $senId;
        return $this;
    }

    public function getTotalVendorAmount()
    {
        $total = 0;
        foreach ($this->getLicenses() as $license) {
            foreach ($license->getTransactions() as $transaction) {
                $total += $transaction->getVendorAmount();
            }
        }
        return $total;
    }
}
