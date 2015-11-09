<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sale
 *
 * @ORM\Table(name="sale", uniqueConstraints={@ORM\UniqueConstraint(name="sale", columns={"invoice", "license_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Entity\SaleRepository")
 */
class Sale
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
     * @ORM\Column(name="license_type", type="string", length=255)
     */
    private $licenseType;

    /**
     * @var string
     *
     * @ORM\Column(name="sale_type", type="string", length=255)
     */
    private $saleType;

    /**
     * @var string
     *
     * @ORM\Column(name="license_id", type="string", length=255)
     */
    private $licenseId;

    /**
     * @var string
     *
     * @ORM\Column(name="license_size", type="string", length=255)
     */
    private $licenseSize;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="vendor_amount", type="decimal", precision=6, scale=2)
     */
    private $vendorAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="plugin_key", type="string", length=255)
     */
    private $pluginKey;

    /**
     * @var string
     *
     * @ORM\Column(name="invoice", type="string", length=255)
     */
    private $invoice;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="plugin_name", type="string", length=255)
     */
    private $pluginName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="maintenance_end_date", type="datetime")
     */
    private $maintenanceEndDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="maintenance_start_date", type="datetime")
     */
    private $maintenanceStartDate;

    /**
     * @var string
     *
     * @ORM\Column(name="organisation_name", type="string", length=255)
     */
    private $organisationName;

    /**
     * @var boolean
     *
     * @ORM\Column(name="discounted", type="boolean")
     */
    private $discounted;

    /**
     * @var string
     *
     * @ORM\Column(name="purchase_price", type="decimal", precision=6, scale=2)
     */
    private $purchasePrice;


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
     * Set licenseType
     *
     * @param string $licenseType
     * @return Sale
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
     * Set saleType
     *
     * @param string $saleType
     * @return Sale
     */
    public function setSaleType($saleType)
    {
        $this->saleType = $saleType;

        return $this;
    }

    /**
     * Get saleType
     *
     * @return string 
     */
    public function getSaleType()
    {
        return $this->saleType;
    }

    /**
     * Set licenseId
     *
     * @param string $licenseId
     * @return Sale
     */
    public function setLicenseId($licenseId)
    {
        $this->licenseId = $licenseId;

        return $this;
    }

    /**
     * Get licenseId
     *
     * @return string 
     */
    public function getLicenseId()
    {
        return $this->licenseId;
    }

    /**
     * Set licenseSize
     *
     * @param string $licenseSize
     * @return Sale
     */
    public function setLicenseSize($licenseSize)
    {
        $this->licenseSize = $licenseSize;

        return $this;
    }

    /**
     * Get licenseSize
     *
     * @return string 
     */
    public function getLicenseSize()
    {
        return $this->licenseSize;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return Sale
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
     * Set vendorAmount
     *
     * @param float $vendorAmount
     * @return Sale
     */
    public function setVendorAmount($vendorAmount)
    {
        $this->vendorAmount = $vendorAmount;

        return $this;
    }

    /**
     * Get vendorAmount
     *
     * @return float
     */
    public function getVendorAmount()
    {
        return $this->vendorAmount;
    }

    /**
     * Set pluginKey
     *
     * @param string $pluginKey
     * @return Sale
     */
    public function setPluginKey($pluginKey)
    {
        $this->pluginKey = $pluginKey;

        return $this;
    }

    /**
     * Get pluginKey
     *
     * @return string 
     */
    public function getPluginKey()
    {
        return $this->pluginKey;
    }

    /**
     * Set invoice
     *
     * @param string $invoice
     * @return Sale
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * Get invoice
     *
     * @return string 
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Sale
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set pluginName
     *
     * @param string $pluginName
     * @return Sale
     */
    public function setPluginName($pluginName)
    {
        $this->pluginName = $pluginName;

        return $this;
    }

    /**
     * Get pluginName
     *
     * @return string 
     */
    public function getPluginName()
    {
        return $this->pluginName;
    }

    /**
     * Set maintenanceEndDate
     *
     * @param \DateTime $maintenanceEndDate
     * @return Sale
     */
    public function setMaintenanceEndDate($maintenanceEndDate)
    {
        $this->maintenanceEndDate = $maintenanceEndDate;

        return $this;
    }

    /**
     * Get maintenanceEndDate
     *
     * @return \DateTime 
     */
    public function getMaintenanceEndDate()
    {
        return $this->maintenanceEndDate;
    }

    /**
     * Set maintenanceStartDate
     *
     * @param \DateTime $maintenanceStartDate
     * @return Sale
     */
    public function setMaintenanceStartDate($maintenanceStartDate)
    {
        $this->maintenanceStartDate = $maintenanceStartDate;

        return $this;
    }

    /**
     * Get maintenanceStartDate
     *
     * @return \DateTime 
     */
    public function getMaintenanceStartDate()
    {
        return $this->maintenanceStartDate;
    }

    /**
     * Set organisationName
     *
     * @param string $organisationName
     * @return Sale
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
     * Set discounted
     *
     * @param boolean $discounted
     * @return Sale
     */
    public function setDiscounted($discounted)
    {
        $this->discounted = $discounted;

        return $this;
    }

    /**
     * Get discounted
     *
     * @return boolean 
     */
    public function getDiscounted()
    {
        return $this->discounted;
    }

    /**
     * Set purchasePrice
     *
     * @param string $purchasePrice
     * @return Sale
     */
    public function setPurchasePrice($purchasePrice)
    {
        $this->purchasePrice = $purchasePrice;

        return $this;
    }

    /**
     * Get purchasePrice
     *
     * @return string 
     */
    public function getPurchasePrice()
    {
        return $this->purchasePrice;
    }

    public function setFromJSON($json)
    {
        //@TODO: For some reason there are sales without licenseType. Clarify with AMKT?
        $licenseType = !empty($json['licenseType']) ? $json['licenseType'] : 'UNKNOWN';

        $this->setLicenseType($licenseType)
            ->setSaleType($json['saleType'])
            ->setLicenseId($json['licenseId'])
            ->setLicenseSize($json['licenseSize'])
            ->setCountry($json['country'])
            ->setVendorAmount($json['vendorAmount'])
            ->setPluginKey($json['pluginKey'])
            ->setInvoice($json['invoice'])
            ->setDate(new \DateTime($json['date']))
            ->setPluginName($json['pluginName'])
            ->setMaintenanceEndDate(new \DateTime($json['maintenanceEndDate']))
            ->setMaintenanceStartDate(new \DateTime($json['maintenanceStartDate']))
            ->setOrganisationName($json['organisationName'])
            ->setDiscounted($json['discounted'])
            ->setPurchasePrice($json['purchasePrice']);

        return $this;
    }
}
