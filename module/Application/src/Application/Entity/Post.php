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
    
    /** 
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="pages")
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
}
