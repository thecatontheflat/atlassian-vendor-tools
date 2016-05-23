<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Price
 *
 * @ORM\Table(name="price", uniqueConstraints={@ORM\UniqueConstraint(name="price", columns={"plugin_key", "edition", "months_valid"})})
 * @ORM\Entity(repositoryClass="AppBundle\Entity\PriceRepository")
 */
class Price
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
     * @ORM\Column(name="plugin_key", type="string", length=255)
     */
    private $pluginKey;

    /**
     * @var string
     *
     * @ORM\Column(name="edition", type="string", length=255)
     */
    private $edition;

    /**
     * @var integer
     *
     * @ORM\Column(name="months_valid", type="integer")
     */
    private $monthsValid;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="decimal", precision=6, scale=2)
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="renewal_amount", type="decimal", precision=6, scale=2)
     */
    private $renewalAmount;


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
     * Set pluginKey
     *
     * @param string $pluginKey
     * @return Price
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
     * Set edition
     *
     * @param string $edition
     * @return Price
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
     * Set monthsValid
     *
     * @param integer $monthsValid
     * @return Price
     */
    public function setMonthsValid($monthsValid)
    {
        $this->monthsValid = $monthsValid;

        return $this;
    }

    /**
     * Get monthsValid
     *
     * @return integer
     */
    public function getMonthsValid()
    {
        return $this->monthsValid;
    }

    /**
     * Set amount
     *
     * @param float $amount
     * @return Price
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set renewalAmount
     *
     * @param float $renewalAmount
     * @return Price
     */
    public function setRenewalAmount($renewalAmount)
    {
        $this->renewalAmount = $renewalAmount;

        return $this;
    }

    /**
     * Get renewalAmount
     *
     * @return float
     */
    public function getRenewalAmount()
    {
        return $this->renewalAmount;
    }

    public function setFromJSON($json, $pluginKey)
    {
        $this->setPluginKey($pluginKey)
            ->setEdition($json['editionDescription'])
            ->setMonthsValid($json['monthsValid'])
            ->setAmount($json['amount'])
            ->setRenewalAmount($json['renewalAmount']);

        return $this;
    }
}
