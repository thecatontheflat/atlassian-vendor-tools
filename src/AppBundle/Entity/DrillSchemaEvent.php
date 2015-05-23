<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * DrillSchemaEvent
 *
 * @ORM\Table(name="drill_schema_event")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\DrillSchemaEventRepository")
 */
class DrillSchemaEvent
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
     * @ORM\Column(name="date_shift", type="string", length=255)
     */
    private $dateShift;

    /**
     * @var string
     *
     * @ORM\Column(name="date_field", type="string", length=255)
     */
    private $dateField;

    /**
     * @var string
     *
     * @ORM\Column(name="license_type_condition", type="string", length=255)
     */
    private $licenseTypeCondition;

    /**
     * @var string
     *
     * @ORM\Column(name="email_template", type="string", length=64000)
     */
    private $emailTemplate;

    /**
     * @var string
     *
     * @ORM\Column(name="email_subject", type="string", length=255)
     */
    private $emailSubject;

    /**
     * @var string
     *
     * @ORM\Column(name="email_from", type="string", length=255)
     */
    private $emailFrom;

    /**
     * @ORM\ManyToOne(targetEntity="DrillSchema", inversedBy="drillSchemaEvents")
     * @ORM\JoinColumn(name="drill_schema_id", referencedColumnName="id")
     */
    protected $drillSchema;

    /**
     * @ORM\OneToMany(targetEntity="DrillRegisteredEvent", mappedBy="drillSchemaEvent")
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
     * Set name
     *
     * @param string $name
     * @return DrillSchemaEvent
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
     *
     * @param string $dateShift
     * @return DrillSchemaEvent
     */
    public function setDateShift($dateShift)
    {
        $this->dateShift = $dateShift;

        return $this;
    }

    /**
     *
     * @return string 
     */
    public function getDateShift()
    {
        return $this->dateShift;
    }

    /**
     * Set licenseTypeCondition
     *
     * @param string $licenseTypeCondition
     * @return DrillSchemaEvent
     */
    public function setLicenseTypeCondition($licenseTypeCondition)
    {
        $this->licenseTypeCondition = $licenseTypeCondition;

        return $this;
    }

    /**
     * Get licenseTypeCondition
     *
     * @return string 
     */
    public function getLicenseTypeCondition()
    {
        return $this->licenseTypeCondition;
    }

    /**
     * Set emailTemplate
     *
     * @param string $emailTemplate
     * @return DrillSchemaEvent
     */
    public function setEmailTemplate($emailTemplate)
    {
        $this->emailTemplate = $emailTemplate;

        return $this;
    }

    /**
     * Get emailTemplate
     *
     * @return string 
     */
    public function getEmailTemplate()
    {
        return $this->emailTemplate;
    }

    /**
     * Set emailSubject
     *
     * @param string $emailSubject
     * @return DrillSchemaEvent
     */
    public function setEmailSubject($emailSubject)
    {
        $this->emailSubject = $emailSubject;

        return $this;
    }

    /**
     * Get emailSubject
     *
     * @return string 
     */
    public function getEmailSubject()
    {
        return $this->emailSubject;
    }

    /**
     * Set emailFrom
     *
     * @param string $emailFrom
     * @return DrillSchemaEvent
     */
    public function setEmailFrom($emailFrom)
    {
        $this->emailFrom = $emailFrom;

        return $this;
    }

    /**
     * Get emailFrom
     *
     * @return string 
     */
    public function getEmailFrom()
    {
        return $this->emailFrom;
    }

    /**
     * Set drillSchema
     *
     * @param \AppBundle\Entity\DrillSchema $drillSchema
     * @return DrillSchemaEvent
     */
    public function setDrillSchema(\AppBundle\Entity\DrillSchema $drillSchema = null)
    {
        $this->drillSchema = $drillSchema;

        return $this;
    }

    /**
     * Get drillSchema
     *
     * @return \AppBundle\Entity\DrillSchema 
     */
    public function getDrillSchema()
    {
        return $this->drillSchema;
    }

    /**
     * Add drillRegisteredEvents
     *
     * @param \AppBundle\Entity\DrillRegisteredEvent $drillRegisteredEvents
     * @return DrillSchemaEvent
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

    /**
     * Set dateField
     *
     * @param string $dateField
     * @return DrillSchemaEvent
     */
    public function setDateField($dateField)
    {
        $this->dateField = $dateField;

        return $this;
    }

    /**
     * Get dateField
     *
     * @return string 
     */
    public function getDateField()
    {
        return $this->dateField;
    }
}
