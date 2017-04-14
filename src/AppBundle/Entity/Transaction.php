<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transaction
 *
 * @ORM\Table(uniqueConstraints={
 *   @ORM\UniqueConstraint(name="licence_transaction", columns={"transaction_id","license_id"})
 * })
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
     * @ORM\Column(type="smallint", nullable=true)
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tier;

    /**
     * @var string
     *
     * @ORM\Column(type="decimal", precision=6, scale=2, nullable=true)
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
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $saleDate;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $billingPeriod;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $maintenanceEndDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $maintenanceStartDate;

    /**
     * @var string
     *
     * @ORM\Column(type="decimal", precision=6, scale=2, nullable=true)
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
        if(is_string($saleType)) {
            if(!$saleTypeChecked = Transaction::getSaleTypeForStr($saleType)) {
                throw new \Exception("Invalid saleType - ".$saleType);
            }
            $saleType = $saleTypeChecked;
        }
        if(!Transaction::isValidSaleType($saleType)) {
            throw new \Exception("Invalid saleType - ".$saleType);
        }
        $this->saleType = $saleType;

        return $this;
    }

    /**
     * Get saleType
     *
     * @return integer
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
     * @return \DateTime
     */
    public function getSaleDate()
    {
        return $this->saleDate;
    }

    /**
     * @param \DateTime $saleDate
     * @return $this
     */
    public function setSaleDate($saleDate)
    {
        if(is_string($saleDate)) {
            $saleDate = new \DateTime($saleDate);
        }
        $this->saleDate = $saleDate;
        return $this;
    }

    /**
     * Set maintenanceEndDate
     *
     * @param \DateTime $maintenanceEndDate
     * @return Transaction
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
        if(is_string($maintenanceStartDate)) {
            $maintenanceStartDate = new \DateTime($maintenanceStartDate);
        }
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

    /**
     * @return array
     */
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
    public function getSaleTypeStr()
    {
        return Transaction::getSaleTypes()[$this->saleType];
    }
    /**
     * @return bool
     */
    public static function getSaleTypeForStr($saleTypeStr)
    {
        return array_search(strtolower($saleTypeStr),Transaction::getSaleTypes());
    }
    /**
     * @return bool
     */
    public static function isValidSaleType($saleType)
    {
        return array_key_exists(strtolower($saleType),Transaction::getSaleTypes());
    }
    /**
     * @return bool
     */
    public function isNew()
    {
        return $this->id == null;
    }

    /**
     * @return string
     */
    public function getBillingPeriod()
    {
        return $this->billingPeriod;
    }

    /**
     * @param string $billingPeriod
     * @return $this
     */
    public function setBillingPeriod($billingPeriod)
    {
        $this->billingPeriod = $billingPeriod;
        return $this;
    }

}
