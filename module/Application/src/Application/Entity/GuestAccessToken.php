<?php
namespace Application\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="guest_access_token", schema="public")
 **/
class GuestAccessToken {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $token;
    
    /** 
     * @ORM\ManyToOne(targetEntity="SocialMediaGateway", inversedBy="guest_access_tokens")
     * @ORM\JoinColumn(name="social_media_gateway_id", referencedColumnName="id")
     */
    protected $social_media_gateway;
    
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
     * Set token
     *
     * @param string $token
     *
     * @return GuestAccessToken
     */
    public function setToken($token)
    {
        $this->token = $token;

        $this->last_updated = new \DateTime();

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set socialMediaGateway
     *
     * @param string $socialMediaGateway
     *
     * @return GuestAccessToken
     */
    public function setSocialMediaGateway($socialMediaGateway)
    {
        $this->social_media_gateway = $socialMediaGateway;
        $this->last_updated = new \DateTime();

        return $this;
    }

    /**
     * Get socialMediaGateway
     *
     * @return string
     */
    public function getSocialMediaGateway()
    {
        return $this->social_media_gateway;
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
}
