<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\EventRepository")
 */
class Event
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
     * @ORM\OneToMany(targetEntity="ScheduledEvent", mappedBy="event")
     */
    private $scheduledEvents;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="addon_key", type="string", length=255, nullable=true)
     */
    private $addonKey;

    /**
     * @var string
     *
     * @ORM\Column(name="topic", type="string", length=255, nullable=true)
     */
    private $topic;

    /**
     * @var string
     *
     * @ORM\Column(name="from", type="string", length=255)
     */
    private $from;

    /**
     * @var string
     *
     * @ORM\Column(name="template", type="text", length=65536)
     */
    private $template;

    /**
     * @var string
     *
     * @ORM\Column(name="license_type", type="string", length=255)
     */
    private $licenseType;

    /**
     * @var string
     *
     * @ORM\Column(name="license_field", type="string", length=255)
     */
    private $licenseField;

    /**
     * @var string
     *
     * @ORM\Column(name="shift_days", type="string", length=255)
     */
    private $shiftDays;


    public function __construct()
    {
        $this->scheduledEvents = new ArrayCollection();
    }

    /**
     * @return ScheduledEvent[]
     */
    public function getScheduledEvents()
    {
        return $this->scheduledEvents;
    }

    public function hasScheduledForLicense(License $license)
    {
        foreach ($this->getScheduledEvents() as $scheduledEvent) {
            if ($scheduledEvent->getLicenseId() == $license->getLicenseId() &&
                $scheduledEvent->getAddonKey() == $license->getAddonKey() &&
                $scheduledEvent->getName() == $this->getName()
            ) {

                return true;
            }
        }

        return false;
    }

    /**
     * @param ScheduledEvent $scheduledEvent
     */
    public function addScheduledEvent(ScheduledEvent $scheduledEvent)
    {
        $this->scheduledEvents[] = $scheduledEvent;
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
     * @return Event
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
     * Set template
     *
     * @param string $template
     * @return Event
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return string 
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set licenseType
     *
     * @param string $licenseType
     * @return Event
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
     * Set licenseField
     *
     * @param string $licenseField
     * @return Event
     */
    public function setLicenseField($licenseField)
    {
        $this->licenseField = $licenseField;

        return $this;
    }

    /**
     * Get licenseField
     *
     * @return string 
     */
    public function getLicenseField()
    {
        return $this->licenseField;
    }

    /**
     * Set shiftDays
     *
     * @param string $shiftDays
     * @return Event
     */
    public function setShiftDays($shiftDays)
    {
        $this->shiftDays = $shiftDays;

        return $this;
    }

    /**
     * Get shiftDays
     *
     * @return string 
     */
    public function getShiftDays()
    {
        return $this->shiftDays;
    }

    /**
     * @return string
     */
    public function getAddonKey()
    {
        return $this->addonKey;
    }

    /**
     * @param string $addonKey
     */
    public function setAddonKey($addonKey)
    {
        $this->addonKey = $addonKey;
    }

    /**
     * @return string
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * @param string $topic
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }
}
