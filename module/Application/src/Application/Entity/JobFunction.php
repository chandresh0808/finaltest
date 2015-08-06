<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Accessor;

/**
 * JobFunction
 *
 * @ORM\Table(name="job_function", indexes={@ORM\Index(name="risk_id", columns={"risk_id"})})
 * @ORM\Entity
 */
class JobFunction extends AbstractEntity
{
    
    
    function __construct()
    {
        $arrayCollection =   new \Doctrine\Common\Collections\ArrayCollection();
        $this->setJobFunctionHasTransactionList($arrayCollection);
    }
    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="rulebook_id", type="integer", nullable=false)
     */
    private $rulebookId;
    
    /**
     * @var string
     *
     * @ORM\Column(name="sap_job_function_id", type="string", length=50, nullable=false)
     */
    protected $sapJobFunctionId;

    /**
     * @var integer
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_default_job_function", type="boolean", nullable=false)
     */
    private $isDefaultJobFunction = '0';
    
    /**
     * Set isSapDefaultJobFunction
     *
     * @param boolean $isSapDefaultJobFunction
     * @return JobFunction
     */
    public function setIsDefaultJobFunction($isDefaultJobFunction)
    {
        $this->isDefaultJobFunction = $isDefaultJobFunction;

        return $this;
    }

    /**
     * Get isSapDefaultJobFunction
     *
     * @return boolean 
     */
    public function getIsDefaultJobFunction()
    {
        return $this->isDefaultJobFunction;
    }
    
    
    /**
     * Set isSapDefaultJobFunction
     *
     * @param boolean $isSapDefaultJobFunction
     * @return JobFunction
     */
    public function setSapJobFunctionId($sapJobFunctionId)
    {
        $this->sapJobFunctionId = $sapJobFunctionId;

        return $this;
    }

    /**
     * Get isSapDefaultJobFunction
     *
     * @return boolean 
     */
    public function getSapJobFunctionId()
    {
        return $this->sapJobFunctionId;
    }
    
        /**
     * Set isSapDefaultJobFunction
     *
     * @param boolean $description
     * @return JobFunction
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get isSapDefaultJobFunction
     *
     * @return boolean 
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    
    /**
     * Collection for job function has transaction list 
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
    
        /**
     * Set userId
     *
     * @param integer $userId
     * @return Rulebook
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
