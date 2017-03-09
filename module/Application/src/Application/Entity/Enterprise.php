<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="enterprise", schema="public")
 **/
class Enterprise {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $name;
    
    /** @ORM\OneToMany(targetEntity="SocialMediaPresence", mappedBy="parentEnterprise") */
    protected $socialMediaPresences;
    
    /** @ORM\OneToMany(targetEntity="User", mappedBy="enterprise") */
    protected $users;
    
    /** @ORM\Column(type="datetime") */
    protected $last_updated;
    
    /** @ORM\Column(type="datetime", options={"default": 0})*/
    protected $created;

    // getters/setters
    
    public function __construct(){
        $this->created = new \DateTime();
        // $this->users = new ArrayCollection();
        // $this->socialMediaPresences[] = new ArrayCollection();
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
     * @return Enterprise
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
     * Add user
     *
     * @param \Application\Entity\User $user
     *
     * @return Enterprise
     */
    public function addUser(\Application\Entity\User $user)
    {
        $this->users[] = $user;
        $this->last_updated = new \DateTime();

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Application\Entity\User $user
     */
    public function removeUser(\Application\Entity\User $user)
    {
        $this->users->removeElement($user);
        $this->last_updated = new \DateTime();
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add socialMediaPresence
     *
     * @param \Application\Entity\SocialMediaPresence $socialMediaPresence
     *
     * @return Enterprise
     */
    public function addSocialMediaPresence(\Application\Entity\SocialMediaPresence $socialMediaPresence)
    {
        $this->socialMediaPresences[] = $socialMediaPresence;
        $this->last_updated = new \DateTime();
        
//         echo ("Enterprise: added social media presence is: ");
//         var_dump($this->socialMediaPresences);
        
        return $this;
    }

    /**
     * Remove socialMediaPresence
     *
     * @param \Application\Entity\SocialMediaPresence $socialMediaPresence
     */
    public function removeSocialMediaPresence(\Application\Entity\SocialMediaPresence $socialMediaPresence)
    {
        $this->socialMediaPresences->removeElement($socialMediaPresence);
        $this->last_updated = new \DateTime();
    }

    /**
     * Get socialMediaPresences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSocialMediaPresences()
    {
        return $this->socialMediaPresences;
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
