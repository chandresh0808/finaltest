<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;

/**
 * RiskHasJobFunction
 *
 * @ORM\Table(name="risk_has_job_function", indexes={@ORM\Index(name="risk_id", columns={"risk_id", "job_function_id"})})
 * @ORM\Entity
 */
class RiskHasJobFunction extends AbstractEntity
{

    /**
     * @var integer
     *
     * @ORM\Column(name="risk_id", type="integer", nullable=false)
     */
    private $riskId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="job_function_id", type="datetime", nullable=false)
     */
    private $jobFunctionId;

    
    /**
     * Set riskId
     *
     * @param integer $riskId
     * @return RiskHasJobFunction
     */
    public function setRiskId($riskId)
    {
        $this->riskId = $riskId;

        return $this;
    }

    /**
     * Get riskId
     *
     * @return integer 
     */
    public function getRiskId()
    {
        return $this->riskId;
    }

    /**
     * Set jobFunctionId
     *
     * @param \DateTime $jobFunctionId
     * @return RiskHasJobFunction
     */
    public function setJobFunctionId($jobFunctionId)
    {
        $this->jobFunctionId = $jobFunctionId;

        return $this;
    }

    /**
     * Get jobFunctionId
     *
     * @return \DateTime 
     */
    public function getJobFunctionId()
    {
        return $this->jobFunctionId;
    }
   
}
