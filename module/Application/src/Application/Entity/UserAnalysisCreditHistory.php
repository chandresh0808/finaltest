<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;

/**
 * UserAnalysisCreditHistory
 *
 * @ORM\Table(name="user_analysis_credit_history", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class UserAnalysisCreditHistory extends AbstractEntity
{

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="user_has_package_id", type="integer", nullable=false)
     */
    private $userHasPackageId;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_credit_analysis_points", type="integer", nullable=true)
     */
    private $totalCreditAnalysisPoints;

    /**
     * @var integer
     *
     * @ORM\Column(name="credit_analysis_points_used", type="integer", nullable=true)
     */
    private $creditAnalysisPointsUsed;

    
    /**
     * Set userId
     *
     * @param integer $userId
     * @return UserAnalysisCreditHistory
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
     * Set totalCreditAnalysisPoints
     *
     * @param integer $totalCreditAnalysisPoints
     * @return UserAnalysisCreditHistory
     */
    public function setTotalCreditAnalysisPoints($totalCreditAnalysisPoints)
    {
        $this->totalCreditAnalysisPoints = $totalCreditAnalysisPoints;

        return $this;
    }

    /**
     * Get totalCreditAnalysisPoints
     *
     * @return integer 
     */
    public function getTotalCreditAnalysisPoints()
    {
        return $this->totalCreditAnalysisPoints;
    }

    /**
     * Set creditAnalysisPointsUsed
     *
     * @param integer $creditAnalysisPointsUsed
     * @return UserAnalysisCreditHistory
     */
    public function setCreditAnalysisPointsUsed($creditAnalysisPointsUsed)
    {
        $this->creditAnalysisPointsUsed = $creditAnalysisPointsUsed;

        return $this;
    }

    /**
     * Get creditAnalysisPointsUsed
     *
     * @return integer 
     */
    public function getCreditAnalysisPointsUsed()
    {
        return $this->creditAnalysisPointsUsed;
    }
   
    
    /**
     * Set creditAnalysisPointsUsed
     *
     * @param integer $userHasPackageId
     * @return userHasPackageId
     */
    public function setUserHasPackageId($userHasPackageId)
    {
        $this->userHasPackageId = $userHasPackageId;

        return $this;
    }

    /**
     * Get creditAnalysisPointsUsed
     *
     * @return integer 
     */
    public function getUserHasPackageId()
    {
        return $this->userHasPackageId;
    }
}
