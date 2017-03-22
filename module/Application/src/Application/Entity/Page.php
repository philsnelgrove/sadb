<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="page", schema="public")
 **/
class Page {
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

    /** 
     * @ORM\ManyToOne(targetEntity="SocialMediaPresence", inversedBy="pages")
     * @ORM\JoinColumn(name="social_media_presence_id", referencedColumnName="id")
     */
    protected $social_media_presence;
    
    /** @ORM\OneToMany(targetEntity="Post", mappedBy="page") */
    protected $posts;
    
    /** @ORM\OneToMany(targetEntity="PageDimension", mappedBy="page") */
    protected $insights;
    
    /** @ORM\Column(type="datetime") */
    protected $last_updated;
    
    /** @ORM\Column(type="datetime", options={"default": 0})*/
    protected $created;

    // getters/setters
    
    public function __construct(){
        $this->created = new \DateTime();
        $this->last_updated = new \DateTime();
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
     * @param string $socialMediaServiceId
     * @return Page
     */
    public function setSocialMediaServiceId($socialMediaServiceId)
    {
        $this->social_media_service_id = $socialMediaServiceId;
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
     * Set title
     *
     * @param string $title
     * @return Page
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
     * Get last_updated
     *
     * @return \DateTime 
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
     * Set social_media_presence
     *
     * @param \Application\Entity\SocialMediaPresence $socialMediaPresence
     * @return Page
     */
    public function setSocialMediaPresence(\Application\Entity\SocialMediaPresence $socialMediaPresence = null)
    {
        $this->social_media_presence = $socialMediaPresence;
        $this->last_updated = new \DateTime();

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
     * Add posts
     *
     * @param \Application\Entity\Post $posts
     * @return Page
     */
    public function addPost(\Application\Entity\Post $posts)
    {
        $this->posts[] = $posts;
        $this->last_updated = new \DateTime();

        return $this;
    }

    /**
     * Remove posts
     *
     * @param \Application\Entity\Post $posts
     */
    public function removePost(\Application\Entity\Post $posts)
    {
        $this->posts->removeElement($posts);
        $this->last_updated = new \DateTime();
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Add insights
     *
     * @param \Application\Entity\PageDimension $insights
     * @return Page
     */
    public function addInsight(\Application\Entity\PageDimension $insights)
    {
        $this->insights[] = $insights;
        $this->last_updated = new \DateTime();

        return $this;
    }

    /**
     * Remove insights
     *
     * @param \Application\Entity\PageDimension $insights
     */
    public function removeInsight(\Application\Entity\PageDimension $insights)
    {
        $this->insights->removeElement($insights);
        $this->last_updated = new \DateTime();
    }

    /**
     * Get insights
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInsights()
    {
        return $this->insights;
    }
}
