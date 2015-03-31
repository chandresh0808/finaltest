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
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="session_guid", type="string", length=50, nullable=true)
     */
    private $sessionGuid;

    /**
     * @var string
     *
     * @ORM\Column(name="analysis_request_guid", type="string", length=50, nullable=true)
     */
    private $analysisRequestGuid;

    
    /**
     * Set userId
     *
     * @param integer $userId
     * @return UserSession
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

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
     * Set analysisRequestGuid
     *
     * @param string $analysisRequestGuid
     * @return UserSession
     */
    public function setAnalysisRequestGuid($analysisRequestGuid)
    {
        $this->analysisRequestGuid = $analysisRequestGuid;

        return $this;
    }

    /**
     * Get analysisRequestGuid
     *
     * @return string 
     */
    public function getAnalysisRequestGuid()
    {
        return $this->analysisRequestGuid;
    }
   
}
