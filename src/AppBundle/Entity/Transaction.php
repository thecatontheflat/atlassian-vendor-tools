<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transaction
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TransactionRepository")
 */
class Transaction
{
    const SALE_TYPE_NEW = 1;
    const SALE_TYPE_UPGRADE = 2;
    const SALE_TYPE_RENEWAL = 3;
    const SALE_TYPE_REFUND = 4;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    private $saleType;

    /**
     * @ORM\ManyToOne(targetEntity="License", inversedBy="transactions")
     */
    private $license;

    /**
     * @var string
     * License Size
     *
     * @ORM\Column(type="string", length=255)
     */
    private $tier;

    /**
     * @var string
     *
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */
    private $vendorAmount;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $transactionId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $maintenanceEndDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $maintenanceStartDate;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $discounted; // TODO: cant find it in https://developer.atlassian.com/market/api/2/reference/resource/vendors/%7BvendorId%7D/reporting/sales/transactions

    /**
     * @var string
     *
     * @ORM\Column(type="decimal", precision=6, scale=2)
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
     * Set saleType
     *
     * @param string $saleType
     * @return Transaction
     */
    public function setSaleType($saleType)
    {
        if(!Transaction::isValidSaleType($saleType)) {
            throw new \Exception("Invalid saleType - ".$saleType);
        }
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
     * Set vendorAmount
     *
     * @param float $vendorAmount
     * @return Transaction
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
     * Set date
     *
     * @param \DateTime $date
     * @return Transaction
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
     * Set maintenanceEndDate
     *
     * @param \DateTime $maintenanceEndDate
     * @return Transaction
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
     * @return Transaction
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
     * Set discounted
     *
     * @param boolean $discounted
     * @return Transaction
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
     * @return Transaction
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
        //@TODO: For some reason there are sales with inconsistent data. Clarify with AMKT?
        $licenseType = !empty($json['licenseType']) ? $json['licenseType'] : 'UNKNOWN';
        $licenseSize = !empty($json['licenseSize']) ? $json['licenseSize'] : 'UNKNOWN';
        $maintenanceEndDate = !empty($json['maintenanceEndDate']) ? $json['maintenanceEndDate'] : $json['date'];
        $maintenanceStartDate = !empty($json['maintenanceStartDate']) ? $json['maintenanceStartDate'] : $json['date'];

        $this->setLicenseType($licenseType)
            ->setSaleType($json['saleType'])
            ->setLicenseId($json['licenseId'])
            ->setLicenseSize($licenseSize)
            ->setCountry($json['country'])
            ->setVendorAmount($json['vendorAmount'])
            ->setPluginKey($json['pluginKey'])
            ->setInvoice($json['invoice'])
            ->setDate(new \DateTime($json['date']))
            ->setPluginName($json['pluginName'])
            ->setMaintenanceEndDate(new \DateTime($maintenanceEndDate))
            ->setMaintenanceStartDate(new \DateTime($maintenanceStartDate))
            ->setOrganisationName($json['organisationName'])
            ->setDiscounted($json['discounted'])
            ->setPurchasePrice($json['purchasePrice']);

        return $this;
    }

    /**
     * Set tier
     *
     * @param string $tier
     * @return Transaction
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
     * Set transactionId
     *
     * @param string $transactionId
     * @return Transaction
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Get transactionId
     *
     * @return string 
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Set license
     *
     * @param \AppBundle\Entity\License $license
     * @return Transaction
     */
    public function setLicense(\AppBundle\Entity\License $license = null)
    {
        $this->license = $license;

        return $this;
    }

    /**
     * Get license
     *
     * @return \AppBundle\Entity\License 
     */
    public function getLicense()
    {
        return $this->license;
    }

    public static function getSaleTypes()
    {
        $types = [];
        $class = new \ReflectionClass("\\AppBundle\\Entity\\Transaction");
        foreach ($class->getConstants() as $constantName=>$value) {
            if(preg_match("'^SALE_TYPE_(.+)$'sui",$constantName,$match)) {
                $types[$value] = strtolower($match[1]);
            }
        }
        return $types;
    }

    public static function isValidSaleType($saleType)
    {
        return in_array($saleType,Transaction::getSaleTypes());
    }
}
