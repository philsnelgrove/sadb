<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="post", schema="public")
 **/
class Post {
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
     * @ORM\ManyToOne(targetEntity="SocialMediaPresence", inversedBy="posts")
     * @ORM\JoinColumn(name="social_media_presence_id", referencedColumnName="id")
     */
    protected $social_media_presence;
    
    /** @ORM\OneToOne(targetEntity="PostType") */
    protected $type;
    
    /** @ORM\OneToMany(targetEntity="PostData", mappedBy="post") */
    protected $metadata;
    
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
     * Set social_media_service_id
     *
     * @param string $socialMediaServiceId
     * @return Post
     */
    public function setSocialMediaServiceId($socialMediaServiceId)
    {
        $this->social_media_service_id = $socialMediaServiceId;

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
     * Set title
     *
     * @param string $title
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;

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
     * @param string $rawTextContent
     * @return Post
     */
    public function setRawTextContent($rawTextContent)
    {
        $this->raw_text_content = $rawTextContent;

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
     * Set social_media_presence
     *
     * @param \Application\Entity\SocialMediaPresence $socialMediaPresence
     * @return Post
     */
    public function setSocialMediaPresence(\Application\Entity\SocialMediaPresence $socialMediaPresence = null)
    {
        $this->social_media_presence = $socialMediaPresence;

        return $this;
    }

    /**
     * Get social_media_presence
     *
     * @return \Application\Entity\SocialMediaPresence 
     */
    public function getSocialMediaPresence()
    {
        return $this->social_media_presence;
    }

    /**
     * Set type
     *
     * @param \Application\Entity\PostType $type
     * @return Post
     */
    public function setType(\Application\Entity\PostType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Application\Entity\PostType 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Add metadata
     *
     * @param \Application\Entity\PostData $metadata
     * @return Post
     */
    public function addMetadatum(\Application\Entity\PostData $metadata)
    {
        $this->metadata[] = $metadata;

        return $this;
    }

    /**
     * Remove metadata
     *
     * @param \Application\Entity\PostData $metadata
     */
    public function removeMetadatum(\Application\Entity\PostData $metadata)
    {
        $this->metadata->removeElement($metadata);
    }

    /**
     * Get metadata
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}
