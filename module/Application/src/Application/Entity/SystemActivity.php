<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;

/**
 * AnalysisRequest
 *
 * @ORM\Table(name="system_activity")
 * @ORM\Entity
 * 
 */
class SystemActivity extends AbstractEntity
{
    
    /**
     * @var integer
     *
     * @ORM\Column(name="activity_id", type="integer", nullable=false)
     * 
     */
    private $activityId;
    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     * 
     */
    private $userId;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;
                  
    /**
     * Set userId
     *
     * @param integer $userId
     * @return AnalysisRequest
     */
    public function setActivityId($activityId)
    {
        $this->activityId = $activityId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getActivityId()
    {
        return $this->activityId;
    }

        /**
     * Set userId
     *
     * @param integer $userId
     * @return AnalysisRequest
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getComment()
    {
        return $this->comment;
    }

    
    /**
     * Set userId
     *
     * @param integer $userId
     * @return AnalysisRequest
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

    
    
}
