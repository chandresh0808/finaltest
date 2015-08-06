<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Accessor;

/**
 * RulebookHasRisk
 *
 * @ORM\Table(name="rulebook_has_risk", indexes={@ORM\Index(name="rulebook_id", columns={"rulebook_id", "risk_id"})})
 * @ORM\Entity
 */
class RulebookHasRisk extends AbstractEntity
{
    
    function __construct()
    {
        $arrayCollection =   new \Doctrine\Common\Collections\ArrayCollection();
        $this->setRiskHasJobFunctionList($arrayCollection);
    }
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="rulebook_id", type="integer", nullable=false)
     * @Expose
     */
    private $rulebookId;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="risk_id", type="integer", nullable=false)
     * @Expose
     */
    private $riskId;    

    
    /**
     * Set rulebookId
     *
     * @param integer $rulebookId
     * @return RulebookHasRisk
     */
    public function setrulebookId($rulebookId)
    {
        $this->rulebookId = $rulebookId;

        return $this;
    }

    /**
     * Get rulebookId
     *
     * @return integer 
     */
    public function getrulebookId()
    {
        return $this->rulebookId;
    }
    
    
    
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
   
}
