<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * DrillSchema
 *
 * @ORM\Table(name="drill_schema")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\DrillSchemaRepository")
 */
class DrillSchema
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="addon_key", type="string", length=255)
     */
    private $addonKey;

    /**
     * @ORM\OneToMany(targetEntity="DrillSchemaEvent", mappedBy="drillSchema")
     */
    protected $drillSchemaEvents;

    public function __construct()
    {
        $this->drillSchemaEvents = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return DrillSchema
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set addonKey
     *
     * @param string $addonKey
     * @return DrillSchema
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
     * Add drillSchemaEvents
     *
     * @param \AppBundle\Entity\DrillSchemaEvent $drillSchemaEvents
     * @return DrillSchema
     */
    public function addDrillSchemaEvent(\AppBundle\Entity\DrillSchemaEvent $drillSchemaEvents)
    {
        $this->drillSchemaEvents[] = $drillSchemaEvents;

        return $this;
    }

    /**
     * Remove drillSchemaEvents
     *
     * @param \AppBundle\Entity\DrillSchemaEvent $drillSchemaEvents
     */
    public function removeDrillSchemaEvent(\AppBundle\Entity\DrillSchemaEvent $drillSchemaEvents)
    {
        $this->drillSchemaEvents->removeElement($drillSchemaEvents);
    }

    /**
     * Get drillSchemaEvents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDrillSchemaEvents()
    {
        return $this->drillSchemaEvents;
    }
}
