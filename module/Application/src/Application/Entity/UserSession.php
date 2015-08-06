<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;

/**
 * UserSession
 *
 * @ORM\Table(name="user_session", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class UserSession extends AbstractEntity
{

    /**
     * @var string
     *
     * @ORM\Column(name="session_guid", type="string", length=50, nullable=true)
     */
    private $sessionGuid;

    /**
     * @var integer
     *
     * @ORM\Column(name="last_request_dt_tm", type="integer", nullable=true)
     */
    private $lastRequestDtTm;
    
    /**
     * Set sessionGuid
     *
     * @param string $sessionGuid
     * @return UserSession
     */
    public function setSessionGuid($sessionGuid)
    {
        $this->sessionGuid = $sessionGuid;

        return $this;
    }

    /**
     * Get sessionGuid
     *
     * @return string 
     */
    public function getSessionGuid()
    {
        return $this->sessionGuid;
    }

    /**
     * Set lastRequestDtTm
     *
     * @param integer $lastRequestDtTm
     * @return UserSession
     */
    public function setLastRequestDtTm($lastRequestDtTm)
    {
        $this->lastRequestDtTm = $lastRequestDtTm;

        return $this;
    }

    /**
     * Get lastRequestDtTm
     *
     * @return integer 
     */
    public function getLastRequestDtTm()
    {
        return $this->lastRequestDtTm;
    }
   
    
     
    /**
     * Create User
     * 
     * @var Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     * 
     */
    private $user;
    
    public function getUser()
    {
        return $this->user;
    }
    
    public function setUser($user)
    {
        return $this->user = $user;
    }
    
    
}
