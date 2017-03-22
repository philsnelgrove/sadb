<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="page_dimension", schema="public")
 **/
class PageDimension {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    
    /** 
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="posts")
     * @ORM\JoinColumn(name="social_media_presence_id", referencedColumnName="id")
     */
    protected $page;
    
    /**
     * @ORM\OneToOne(targetEntity="Dimension")
     */
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
     * Set value
     *
     * @param string $value
     * @return PageDimension
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
     * Set page
     *
     * @param \Application\Entity\Page $page
     * @return PageDimension
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
     * Set dimension
     *
     * @param \Application\Entity\Dimension $dimension
     * @return PageDimension
     */
    public function setDimension(\Application\Entity\Dimension $dimension = null)
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
