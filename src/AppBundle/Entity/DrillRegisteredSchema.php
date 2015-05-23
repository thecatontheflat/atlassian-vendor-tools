<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * DrillRegisteredSchema
 *
 * @ORM\Table(name="drill_registered_schema")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\DrillRegisteredSchemaRepository")
 */
class DrillRegisteredSchema
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
     * @ORM\Column(name="license_id", type="string", length=255)
     */
    private $licenseId;

    /**
     * @var string
     *
     * @ORM\Column(name="addon_key", type="string", length=255)
     */
    private $addonKey;

    /**
     * @ORM\OneToMany(targetEntity="DrillRegisteredEvent", mappedBy="drillRegisteredSchema")
     */
    protected $drillRegisteredEvents;

    public function __construct()
    {
        $this->drillRegisteredEvents = new ArrayCollection();
    }

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
     * @return DrillRegisteredSchema
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
     * Set addonKey
     *
     * @param string $addonKey
     * @return DrillRegisteredSchema
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
     * Add drillRegisteredEvents
     *
     * @param \AppBundle\Entity\DrillRegisteredEvent $drillRegisteredEvents
     * @return DrillRegisteredSchema
     */
    public function addDrillRegisteredEvent(\AppBundle\Entity\DrillRegisteredEvent $drillRegisteredEvents)
    {
        $this->drillRegisteredEvents[] = $drillRegisteredEvents;

        return $this;
    }

    /**
     * Remove drillRegisteredEvents
     *
     * @param \AppBundle\Entity\DrillRegisteredEvent $drillRegisteredEvents
     */
    public function removeDrillRegisteredEvent(\AppBundle\Entity\DrillRegisteredEvent $drillRegisteredEvents)
    {
        $this->drillRegisteredEvents->removeElement($drillRegisteredEvents);
    }

    /**
     * Get drillRegisteredEvents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDrillRegisteredEvents()
    {
        return $this->drillRegisteredEvents;
    }
}
