<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;

/**
 * UserFreeTrialPerDayCount
 *
 * @ORM\Table(name="user_free_trial_per_day_count", indexes={@ORM\Index(name="user_id", columns={"user_id", "date_str"})})
 * @ORM\Entity
 */
class UserFreeTrialPerDayCount extends AbstractEntity
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
     * @ORM\Column(name="date_str", type="string", length=255, nullable=true)
     */
    private $dateStr;

    /**
     * @var integer
     *
     * @ORM\Column(name="reports_generation_count", type="integer", nullable=false)
     */
    private $reportsGenerationCount = '0';

    
    /**
     * Set userId
     *
     * @param integer $userId
     * @return UserFreeTrialPerDayCount
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
     * Set dateStr
     *
     * @param string $dateStr
     * @return UserFreeTrialPerDayCount
     */
    public function setDateStr($dateStr)
    {
        $this->dateStr = $dateStr;

        return $this;
    }

    /**
     * Get dateStr
     *
     * @return string 
     */
    public function getDateStr()
    {
        return $this->dateStr;
    }

    /**
     * Set reportsGenerationCount
     *
     * @param integer $reportsGenerationCount
     * @return UserFreeTrialPerDayCount
     */
    public function setReportsGenerationCount($reportsGenerationCount)
    {
        $this->reportsGenerationCount = $reportsGenerationCount;

        return $this;
    }

    /**
     * Get reportsGenerationCount
     *
     * @return integer 
     */
    public function getReportsGenerationCount()
    {
        return $this->reportsGenerationCount;
    }
    
}
