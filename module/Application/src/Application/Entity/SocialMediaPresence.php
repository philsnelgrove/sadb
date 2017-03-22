<?php
namespace Application\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="social_media_presence", schema="public")
 **/
class SocialMediaPresence {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $name;
    
    /** @ORM\OneToOne(targetEntity="SocialMediaGateway", cascade={"all"}) */
    protected $socialMediaGateway;
    
    /** @ORM\OneToMany(targetEntity="Post", mappedBy="social_media_presence") */
    protected $posts;
    
    /** @ORM\OneToMany(targetEntity="Page", mappedBy="social_media_presence") */
    protected $pages;
            
    /** 
     * @ORM\ManyToOne(targetEntity="Enterprise", inversedBy="socialMediaPresences", cascade={"persist"})
     * @ORM\JoinColumn(name="parent_enterprise_id", referencedColumnName="id")
     */
    protected $parentEnterprise;
    
    /** @ORM\Column(type="datetime") */
    protected $last_updated;
    
    /** @ORM\Column(type="datetime", options={"default": 0})*/
    protected $created;

    // getters/setters
    
    public function __construct(){
        $this->created = new \DateTime();
//         $this->posts = new ArrayCollection();
//         $this->events = new ArrayCollection();
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
     * Add post
     *
     * @param \Application\Entity\Post $post
     *
     * @return SocialMediaPresence
     */
    public function addPost(\Application\Entity\Post $post)
    {
        $this->posts[] = $post;
        $this->last_updated = new \DateTime();

        return $this;
    }

    /**
     * Remove post
     *
     * @param \Application\Entity\Post $post
     */
    public function removePost(\Application\Entity\Post $post)
    {
        $this->posts->removeElement($post);
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
     * Set parentEnterprise
     *
     * @param \Application\Entity\Enterprise $parentEnterprise
     *
     * @return SocialMediaPresence
     */
    public function setParentEnterprise(\Application\Entity\Enterprise $parentEnterprise = null)
    {
        $this->parentEnterprise = $parentEnterprise;
        $this->last_updated = new \DateTime();

        return $this;
    }

    /**
     * Get parentEnterprise
     *
     * @return \Application\Entity\Enterprise
     */
    public function getParentEnterprise()
    {
        return $this->parentEnterprise;
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
     * Set socialMediaGateway
     *
     * @param \Application\Entity\SocialMediaGateway $socialMediaGateway
     * @return SocialMediaPresence
     */
    public function setSocialMediaGateway(\Application\Entity\SocialMediaGateway $socialMediaGateway = null)
    {
        $this->socialMediaGateway = $socialMediaGateway;
        $this->last_updated = new \DateTime();

        return $this;
    }

    /**
     * Get socialMediaGateway
     *
     * @return \Application\Entity\SocialMediaGateway 
     */
    public function getSocialMediaGateway()
    {
        return $this->socialMediaGateway;
    }
}
