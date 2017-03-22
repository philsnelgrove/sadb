<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
use ZfcUser\Entity\User as ZfcUser;
/**
 * @ORM\Entity
 * @ORM\Table(name="users", schema="public")
 **/

class User extends ZfcUser {
        
    /** 
     * @ORM\ManyToOne(targetEntity="Enterprise", inversedBy="users", cascade={"all"})
     * @ORM\JoinColumn(name="enterprise_id", referencedColumnName="id")
     */
    protected $enterprise;
    
    /** @ORM\Column(type="datetime") */
    protected $last_updated;
    
    /** @ORM\OneToOne(targetEntity="AccessToken") */
    protected $accessToken;
    
    /** @ORM\Column(type="datetime", options={"default": 0})*/
    protected $created;
    
    protected $tableName  = 'users';

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
     * Set enterprise
     *
     * @param \Application\Entity\Enterprise $enterprise
     * @return User
     */
    public function setEnterprise(\Application\Entity\Enterprise $enterprise = null)
    {
        $this->enterprise = $enterprise;

        return $this;
    }

    /**
     * Get enterprise
     *
     * @return \Application\Entity\Enterprise 
     */
    public function getEnterprise()
    {
        return $this->enterprise;
    }

    /**
     * Set accessToken
     *
     * @param \Application\Entity\AccessToken $accessToken
     * @return User
     */
    public function setAccessToken(\Application\Entity\AccessToken $accessToken = null)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Get accessToken
     *
     * @return \Application\Entity\AccessToken 
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }
}
