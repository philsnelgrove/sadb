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
    
    /** @ORM\Column(type="text") */
    protected $title;
    
    /** 
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="posts")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     */
    protected $page;
    
    /** @ORM\OneToOne(targetEntity="PostType") */
    protected $type;
    
    /** @ORM\OneToMany(targetEntity="PostDimension", mappedBy="post") */
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
     * @return Post
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
     * Set page
     *
     * @param \Application\Entity\Page $page
     * @return Post
     */
    public function setPage(\Application\Entity\Page $page = null)
    {
        $this->page = $page;
        $this->last_updated = new \DateTime();

        return $this;
    }

    /**
     * Get page
     *
     * @return \Application\Entity\Page 
     */
    public function getPage()
    {
        return $this->page;
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
        $this->last_updated = new \DateTime();

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
     * Add insights
     *
     * @param \Application\Entity\PostDimension $insights
     * @return Post
     */
    public function addInsight(\Application\Entity\PostDimension $insights)
    {
        $this->insights[] = $insights;
        $this->last_updated = new \DateTime();

        return $this;
    }

    /**
     * Remove insights
     *
     * @param \Application\Entity\PostDimension $insights
     */
    public function removeInsight(\Application\Entity\PostDimension $insights)
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
