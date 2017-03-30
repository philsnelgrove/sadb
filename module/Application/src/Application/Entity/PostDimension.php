<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="post_dimension", schema="public")
 **/
class PostDimension {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    
    /** 
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="insights")
     * @ORM\JoinColumn(name="social_media_presence_id", referencedColumnName="id")
     */
    protected $post;
    
    /** @ORM\Column(type="string") */
    protected $dimension;
        
    /** @ORM\Column(type="string") */
    protected $value;
    
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
     * Set value
     *
     * @param string $value
     * @return PostDimension
     */
    public function setValue($value)
    {
        $this->value = $value;
        $this->last_updated = new \DateTime();

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
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
     * Set post
     *
     * @param \Application\Entity\Post $post
     * @return PostDimension
     */
    public function setPost(\Application\Entity\Post $post = null)
    {
        $this->post = $post;
        $this->last_updated = new \DateTime();

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

    /**
     * Set dimension
     *
     * @param string $dimension
     * @return PostDimension
     */
    public function setDimension($dimension)
    {
        $this->dimension = $dimension;
        $this->last_updated = new \DateTime();

        return $this;
    }

    /**
     * Get dimension
     *
     * @return \Application\Entity\Dimension 
     */
    public function getDimension()
    {
        return $this->dimension;
    }
}
