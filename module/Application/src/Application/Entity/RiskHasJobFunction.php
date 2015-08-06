<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Accessor;

/**
 * RiskHasJobFunction
 *
 * @ORM\Table(name="risk_has_job_function", indexes={@ORM\Index(name="risk_id", columns={"risk_id", "job_function_id"})})
 * @ORM\Entity
 */
class RiskHasJobFunction extends AbstractEntity
{
    
    function __construct()
    {
        $arrayCollection =   new \Doctrine\Common\Collections\ArrayCollection();
        $this->setJobFunctionHasTransactionList($arrayCollection);
    }
    
    /**
     * @var integer
     *
     * @ORM\Column(name="risk_id", type="integer", nullable=false)
     * @Expose
     */
    private $riskId;

    /**
     * @var integer
     *
     * @ORM\Column(name="job_function_id", type="integer", nullable=false)
     * @Expose
     */
    private $jobFunctionId;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="rulebook_id", type="integer", nullable=false)
     */
    private $rulebookId;

    
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
     * @param integer $jobFunctionId
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
     * @return integer 
     */
    public function getJobFunctionId()
    {
        return $this->jobFunctionId;
    }
    
    /**
     * Collection for job function has transactions list 
     * 
     * @var \Application\Entity\JobFunctionHasTransaction Description
     *
     * @param \Application\Entity\JobFunctionHasTransaction JobFunctionHasTransaction List
     * @ORM\OneToMany(targetEntity="JobFunctionHasTransaction", mappedBy="jobFunctionId")
     * @Expose
     * @Accessor(getter="getJobFunctionHasTransactionList",setter="setJobFunctionHasTransactionList")
     */
    private $jobFunctionHasTransactionList;

    public function setJobFunctionHasTransactionList($arrayCollection)
    {
        $this->jobFunctionHasTransactionList = $arrayCollection;
        return $this;
    }

    public function getJobFunctionHasTransactionList()
    {
        return $this->jobFunctionHasTransactionList;
    }    
    
     /**
     * Set rulebookId
     *
     * @param integer $rulebookId
     * @return Risk
     */
    public function setRulebookId($rulebookId)
    {
        $this->rulebookId = $rulebookId;

        return $this;
    }

    /**
     * Get rulebookId
     *
     * @return integer 
     */
    public function getRulebookId()
    {
        return $this->rulebookId;
    }
    
   
}
