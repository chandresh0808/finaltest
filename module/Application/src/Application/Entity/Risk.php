<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Accessor;

/**
 * Risk
 *
 * @ORM\Table(name="risk", indexes={@ORM\Index(name="rulebook_id", columns={"rulebook_id"})})
 * @ORM\Entity
 */
class Risk extends AbstractEntity
{
    
    function __construct()
    {
        $arrayCollection =   new \Doctrine\Common\Collections\ArrayCollection();
        $this->setRiskHasJobFunctionList($arrayCollection);
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
     * @ORM\Column(name="sap_risk_id", type="string", length=50, nullable=false)
     */
    private $sapRiskId;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="rulebook_id", type="integer", nullable=false)
     */
    private $rulebookId;

   
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="single_function_risk", type="string", length=10, nullable=false)
     */
    private $singleFunctionRisk;
    
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="risk_category", type="string", length=255, nullable=false)
     */
    private $riskCategory;
    
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="risk_level", type="string", length=255, nullable=false)
     */
    private $riskLevel;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;
    
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_default_risk", type="boolean", nullable=false)
     */
    private $isDefaultRisk;
    
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
     * Collection for risk has job function list 
     * 
     * @var \Application\Entity\RiskHasJobFunction Description
     *
     * @param \Application\Entity\RiskHasJobFunction RiskHasJobFunction List
     * @ORM\OneToMany(targetEntity="RiskHasJobFunction", mappedBy="riskId")
     * @Expose
     * @Accessor(getter="getRiskHasJobFunctionList",setter="setRiskHasJobFunctionList")
     */
    private $riskHasJobFunctionList;

    public function setRiskHasJobFunctionList($arrayCollection)
    {
        $this->riskHasJobFunctionList = $arrayCollection;
        return $this;
    }

    public function getRiskHasJobFunctionList()
    {
        return $this->riskHasJobFunctionList;
    }
    
    /**
     * Set isSapDefaultRisk
     *
     * @param boolean $sapRiskId
     * @return Risk
     */
    public function setSapRiskId($sapRiskId)
    {
        $this->sapRiskId = $sapRiskId;

        return $this;
    }

    /**
     * Get isSapDefaultRisk
     *
     * @return boolean 
     */
    public function getSapRiskId()
    {
        return $this->sapRiskId;
    }
    
    
     
    /**
     * Set isSapDefaultRisk
     *
     * @param boolean $sapRiskId
     * @return Risk
     */
    public function setSingleFunctionRisk($singleFunctionRisk)
    {
        $this->singleFunctionRisk = $singleFunctionRisk;

        return $this;
    }

    /**
     * Get isSapDefaultRisk
     *
     * @return boolean 
     */
    public function getSingleFunctionRisk()
    {
        return $this->singleFunctionRisk;
    }
    
    /**
     * Set isSapDefaultRisk
     *
     * @param boolean $sapRiskId
     * @return Risk
     */
    public function setRiskCategory($riskCategory)
    {
        $this->riskCategory = $riskCategory;

        return $this;
    }

    /**
     * Get isSapDefaultRisk
     *
     * @return boolean 
     */
    public function getRiskCategory()
    {
        return $this->riskCategory;
    }
    
    /**
     * Set isSapDefaultRisk
     *
     * @param boolean $sapRiskId
     * @return Risk
     */
    public function setRiskLevel($riskLevel)
    {
        $this->riskLevel = $riskLevel;

        return $this;
    }

    /**
     * Get isSapDefaultRisk
     *
     * @return boolean 
     */
    public function getRiskLevel()
    {
        return $this->riskLevel;
    }
    
     /**
     * Set isSapDefaultRisk
     *
     * @param boolean $sapRiskId
     * @return Risk
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get isSapDefaultRisk
     *
     * @return boolean 
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    
         /**
     * Set isSapDefaultRisk
     *
     * @param boolean $sapRiskId
     * @return Risk
     */
    public function setIsDefaultRisk($isDefaultRisk)
    {
        $this->isDefaultRisk = $isDefaultRisk;

        return $this;
    }

    /**
     * Get isSapDefaultRisk
     *
     * @return boolean 
     */
    public function getIsDefaultRisk()
    {
        return $this->isDefaultRisk;
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
