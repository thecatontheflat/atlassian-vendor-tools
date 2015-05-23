<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DrillRegisteredEvent
 *
 * @ORM\Table(name="drill_registered_event")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\DrillRegisteredEventRepository")
 */
class DrillRegisteredEvent
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
     * @ORM\ManyToOne(targetEntity="DrillSchemaEvent", inversedBy="drillRegisteredEvents")
     * @ORM\JoinColumn(name="drill_schema_event_id", referencedColumnName="id")
     */
    protected $drillSchemaEvent;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="send_date", type="datetime")
     */
    private $sendDate;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;


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
     * Set sendDate
     *
     * @param \DateTime $sendDate
     * @return DrillRegisteredEvent
     */
    public function setSendDate($sendDate)
    {
        $this->sendDate = $sendDate;

        return $this;
    }

    /**
     * Get sendDate
     *
     * @return \DateTime 
     */
    public function getSendDate()
    {
        return $this->sendDate;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return DrillRegisteredEvent
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set drillSchemaEvent
     *
     * @param \AppBundle\Entity\DrillSchemaEvent $drillSchemaEvent
     * @return DrillRegisteredEvent
     */
    public function setDrillSchemaEvent(\AppBundle\Entity\DrillSchemaEvent $drillSchemaEvent = null)
    {
        $this->drillSchemaEvent = $drillSchemaEvent;

        return $this;
    }

    /**
     * Get drillSchemaEvent
     *
     * @return \AppBundle\Entity\DrillSchemaEvent 
     */
    public function getDrillSchemaEvent()
    {
        return $this->drillSchemaEvent;
    }
}
