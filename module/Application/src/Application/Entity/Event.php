<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="event", schema="public")
 **/
class Event {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $social_media_service_id;
    
    /** @ORM\Column(type="string") */
    protected $title;
    
    /** @ORM\Column(type="string") */
    protected $raw_text_content;
     
    /** 
     * @ORM\ManyToOne(targetEntity="SocialMediaPresence", inversedBy="events")
     * @ORM\JoinColumn(name="social_media_presence_id", referencedColumnName="id")
     */
    protected $social_media_presence;
    
    /** @ORM\OneToMany(targetEntity="EventLike", mappedBy="event") */
    protected $likes;
    
    /** @ORM\OneToMany(targetEntity="EventComment", mappedBy="event") */
    protected $comments;
    
    /** @ORM\OneToMany(targetEntity="EventReaction", mappedBy="event") */
    protected $reactions;
    
    /** @ORM\OneToMany(targetEntity="EventAttendee", mappedBy="event") */
    protected $attendees;
    
    /** @ORM\Column(type="datetime") */
    protected $last_updated;
    
    /** @ORM\Column(type="datetime", options={"default": 0})*/
    protected $created;

    // getters/setters
    
    public function __construct(){
        $this->created = new \DateTime();
        $this->likes = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->reactions = new ArrayCollection();
        $this->attendees = new ArrayCollection();
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
     * Set social_media_service_id
     *
     * @param string $id
     *
     * @return Post
     */
    public function setSocialMediaServiceId($id)
    {
        $this->social_media_service_id = $id;
        $this->last_updated = new \DateTime();

        return $this;
    }

    /**
     * Get social_media_service_id
     *
     * @return string
     */
    public function getSocialMediaServiceId()
    {
        return $this->social_media_service_id;
    }
    
    /**
     * Set social_media_presence
     *
     * @param string $presence
     *
     * @return Post
     */
    public function setSocialMediaPresence($presence)
    {
        $this->social_media_presence = $presence;
        $this->last_updated = new \DateTime();
    
        return $this;
    }
    
    /**
     * Get social_media_presence
     *
     * @return SocialMediaPresence
     */
    public function getSocialMediaPresence()
    {
        return $this->social_media_presence;
    }
    
    /**
     * Set title
     *
     * @param string $title
     *
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;
        $this->last_updated = new \DateTime();
    
        return $this;
    }
    
    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Set raw_text_content
     *
     * @param string $content
     *
     * @return Post
     */
    public function setRawTextContent($content)
    {
        $this->raw_text_content = $content;
        $this->last_updated = new \DateTime();
    
        return $this;
    }
    
    /**
     * Get raw_text_content
     *
     * @return string
     */
    public function getRawTextContent()
    {
        return $this->raw_text_content;
    }
    
    /**
     * Set name
     *
     * @param string $name
     *
     * @return SocialMediaPresence
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->last_updated = new \DateTime();
    
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
     * Increment num_likes
     *
     * @return Post
     */
    public function addLike(\Application\Entity\EventLike $like)
    {
        $this->likes[] = $like;
        $this->last_updated = new \DateTime();
    
        return $this;
    }
    
    /**
     * Add Comment
     *
     * @param Comment $comment
     *
     * @return Post
     */
    public function addComment(\Application\Entity\EventComment $comment)
    {
        $this->comments[] = $comment;
        $this->last_updated = new \DateTime();
    
        return $this;
    }
    
    /**
     * Get comments
     *
     * @return array(Comment)
     */
    public function getComments()
    {
        //return $this->comments;
    }
    
    /**
     * Add Reaction
     *
     * @param Reaction $reaction
     *
     * @return Post
     */
    public function addReaction(\Application\Entity\EventReaction $reaction)
    {
        $this->reactions[] = $reaction;
        $this->last_updated = new \DateTime();
    
        return $this;
    }
    
    /**
     * Get reactions
     *
     * @return array(Reaction)
     */
    public function getReactions()
    {
        return $this->reactions;
    }
    
    /**
     * Get last updated
     *
     * @return string
     */
    public function getLastUpdated()
    {
        return $this->last_updated;
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

    /**
     * Remove like
     *
     * @param \Application\Entity\EventLike $like
     */
    public function removeLike(\Application\Entity\EventLike $like)
    {
        $this->last_updated = new \DateTime();
        $this->likes->removeElement($like);
    }

    /**
     * Get likes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * Remove comment
     *
     * @param \Application\Entity\EventComment $comment
     */
    public function removeComment(\Application\Entity\EventComment $comment)
    {
        $this->last_updated = new \DateTime();
        $this->comments->removeElement($comment);
    }

    /**
     * Remove reaction
     *
     * @param \Application\Entity\EventReaction $reaction
     */
    public function removeReaction(\Application\Entity\EventReaction $reaction)
    {
        $this->last_updated = new \DateTime();
        $this->reactions->removeElement($reaction);
    }

    /**
     * Add attendee
     *
     * @param \Application\Entity\EventAttendee $attendee
     *
     * @return Event
     */
    public function addAttendee(\Application\Entity\EventAttendee $attendee)
    {
        $this->last_updated = new \DateTime();
        $this->attendees[] = $attendee;

        return $this;
    }

    /**
     * Remove attendee
     *
     * @param \Application\Entity\EventAttendee $attendee
     */
    public function removeAttendee(\Application\Entity\EventAttendee $attendee)
    {
        $this->last_updated = new \DateTime();
        $this->attendees->removeElement($attendee);
    }

    /**
     * Get attendees
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAttendees()
    {
        return $this->attendees;
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
}
