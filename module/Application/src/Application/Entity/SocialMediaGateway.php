<?php
namespace Application\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="social_media_gateway", schema="public")
 **/
class SocialMediaGateway {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $name;
    
    /** @ORM\OneToOne(targetEntity="AccessToken") */
    protected $access_token;
    
    /** @ORM\OneToMany(targetEntity="GuestAccessToken", mappedBy="social_media_gateway") */
    protected $guest_access_tokens;
    
    /** @ORM\OneToOne(targetEntity="SocialMediaPresence") */
    protected $social_media_presence;
    
    /** @ORM\Column(type="string") */
    protected $app_id;
    
    /** @ORM\Column(type="string") */
    protected $app_secret;
    
    /** @ORM\Column(type="datetime") */
    protected $last_updated;
    
    /** @ORM\Column(type="datetime", options={"default": 0})*/
    protected $created;

    // getters/setters
    
    public function __construct(){
        $this->created = new \DateTime();
        $this->last_updated = new \DateTime();
        $this->guest_access_tokens = new ArrayCollection();
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
     * Set SocialMediaPresence
     *
     * @param SocialMediaPresence $presence
     *
     * @return SocialMediaGateway
     */
    public function setSocialMediaPresence($presence)
    {
        $this->social_media_presence = $presence;
        $this->last_updated = new \DateTime();
    
        return $this;
    }
    
    /**
     * Get socialMediaPresence
     *
     * @return SocialMediaPresence
     */
    public function getSocialMediaPresence()
    {
        return $this->social_media_presence;
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
     * Set accessToken
     *
     * @param \Application\Entity\AccessToken $accessToken
     *
     * @return SocialMediaGateway
     */
    public function setAccessToken(\Application\Entity\AccessToken $accessToken = null)
    {
        $this->access_token = $accessToken;
        $this->last_updated = new \DateTime();

        return $this;
    }

    /**
     * Get accessToken
     *
     * @return \Application\Entity\AccessToken
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }

    /**
     * Add guestAccessToken
     *
     * @param \Application\Entity\GuestAccessToken $guestAccessToken
     *
     * @return SocialMediaGateway
     */
    public function addGuestAccessToken(\Application\Entity\GuestAccessToken $guestAccessToken)
    {
        $this->guest_access_tokens[] = $guestAccessToken;

        return $this;
    }

    /**
     * Remove guestAccessToken
     *
     * @param \Application\Entity\GuestAccessToken $guestAccessToken
     */
    public function removeGuestAccessToken(\Application\Entity\GuestAccessToken $guestAccessToken)
    {
        $this->guest_access_tokens->removeElement($guestAccessToken);
    }

    /**
     * Get guestAccessTokens
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGuestAccessTokens()
    {
        return $this->guest_access_tokens;
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
     * Set app_id
     *
     * @param string $appId
     * @return SocialMediaGateway
     */
    public function setAppId($appId)
    {
        $this->app_id = $appId;
        $this->last_updated = new \DateTime();

        return $this;
    }

    /**
     * Get app_id
     *
     * @return string 
     */
    public function getAppId()
    {
        return $this->app_id;
    }

    /**
     * Set app_secret
     *
     * @param string $appSecret
     * @return SocialMediaGateway
     */
    public function setAppSecret($appSecret)
    {
        $this->app_secret = $appSecret;
        $this->last_updated = new \DateTime();

        return $this;
    }

    /**
     * Get app_secret
     *
     * @return string 
     */
    public function getAppSecret()
    {
        return $this->app_secret;
    }
}
