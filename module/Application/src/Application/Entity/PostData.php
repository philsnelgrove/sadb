<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="post_data", schema="public")
 **/
class PostData {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $social_media_service_id;
    
    /** 
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="posts")
     * @ORM\JoinColumn(name="social_media_presence_id", referencedColumnName="id")
     */
    protected $post;
    
    /** @ORM\Column(type="string") */
    protected $title;
    
    /** @ORM\Column(type="string") */
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
     * Set metadata
     *
     * @param string $metadata
     * @return PostData
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * Get metadata
     *
     * @return string 
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Set post
     *
     * @param \Application\Entity\Post $post
     * @return PostData
     */
    public function setPost(\Application\Entity\Post $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \Application\Entity\Post 
     */
    public function getPost()
    {
        return $this->post;
    }
}
