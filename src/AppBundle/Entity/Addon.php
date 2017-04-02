<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Addon
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AddonRepository")
 */
class Addon
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
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $addonKey;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $addonName;

    /**
     * @ORM\OneToMany(targetEntity="License", mappedBy="addon")
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
     * Set addonKey
     *
     * @param string $addonKey
     * @return Addon
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
     * Set addonName
     *
     * @param string $addonName
     * @return Addon
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
     * Constructor
     */
    public function __construct()
    {
        $this->licenses = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add licenses
     *
     * @param \AppBundle\Entity\License $licenses
     * @return Addon
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
