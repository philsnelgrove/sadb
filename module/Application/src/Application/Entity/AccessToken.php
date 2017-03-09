<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="access_token", schema="public")
 **/
class AccessToken {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $token;
    
    /** @ORM\OneToOne(targetEntity="SocialMediaGateway") */
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
     * @return AccessToken
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
     * @return AccessToken
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
     * Get dateCreated
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->created;
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
