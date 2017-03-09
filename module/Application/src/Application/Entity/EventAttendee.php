<?php
namespace Application\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="event_attendee", schema="public")
 **/
class EventAttendee {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $social_media_service_id;
    
    /** 
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="attendees")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    protected $event;
    
    /** @ORM\Column(type="string") */
    protected $status;
    
    /** @ORM\Column(type="datetime") */
    protected $last_updated;
    
    /** @ORM\Column(type="datetime", options={"default": 0})*/
    protected $created;

    // getters/setters
    
    public function __construct(){
        $this->created = new \DateTime();
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
     * Set socialMediaServiceId
     *
     * @param string $socialMediaServiceId
     *
     * @return Attendee
     */
    public function setSocialMediaServiceId($socialMediaServiceId)
    {
        $this->social_media_service_id = $socialMediaServiceId;
        $this->last_updated = new \DateTime();

        return $this;
    }

    /**
     * Get socialMediaServiceId
     *
     * @return string
     */
    public function getSocialMediaServiceId()
    {
        return $this->social_media_service_id;
    }

    /**
     * Set event
     *
     * @param string $event
     *
     * @return Attendee
     */
    public function setEvent($event)
    {
        $this->event = $event;
        $this->last_updated = new \DateTime();

        return $this;
    }

    /**
     * Get event
     *
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Attendee
     */
    public function setStatus($status)
    {
        $this->status = $status;
        $this->last_updated = new \DateTime();

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
     * Get lastUpdated
     *
     * @return \DateTime
     */
    public function getLastUpdated()
    {
        return $this->last_updated;
    }
    
    /**
     * Get dateCreated
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->created;
    }
    
    /**
     * Stub to prevent auto-generation
     */
    private function setLastUpdated()
    {
    }

    /**
     * Stub to prevent auto-generation
     */
    private function setCreated()
    {
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }
}
