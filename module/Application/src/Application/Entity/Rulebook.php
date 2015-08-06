<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Accessor;
use Doctrine\Common\Collections\Criteria;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * Rulebook
 *
 * @ORM\Table(name="rulebook", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 * @ExclusionPolicy("all")
 */
class Rulebook extends AbstractEntity
{
    
    function __construct()
    {
        $arrayCollection =   new \Doctrine\Common\Collections\ArrayCollection();
        $this->setAnalysisRequestList($arrayCollection);
        $this->setRuleBookHasRiskList($arrayCollection);
    }

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Expose
     */
    protected $name;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;
    
    /**
     * @var text
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     * @Expose
     */
    private $description;
    
    /**
     * @var text
     *
     * @ORM\Column(name="copied_from_rulebook_id", type="integer", nullable=true)
     * @Expose
     */
    private $copiedFromRulebookId;

   
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
    
    
    /**
     * Set copiedFromRulebookId
     *
     * @param integer $copiedFromRulebookId
     * @return Rulebook copiedFromRulebookId
     */
    public function setCopiedFromRulebookId($copiedFromRulebookId)
    {
        $this->copiedFromRulebookId = $copiedFromRulebookId;

        return $this;
    }

    /**
     * Get copiedFromRulebookId
     *
     * @return integer 
     */
    public function getCopiedFromRulebookId()
    {
        return $this->copiedFromRulebookId;
    }
    
    /**
     * Set description
     *
     * @param text $description
     * @return Rulebook description
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Collection for Rule book list 
     * 
     * @var \Application\Entity\AnalysisRequest Description
     *
     * @param \Application\Entity\AnalysisRequest Analysis List
     * @ORM\OneToMany(targetEntity="AnalysisRequest", mappedBy="rulebookId")
     * @Expose
     * @Accessor(getter="getAnalysisRequestList",setter="setAnalysisRequestList")
     */
    private $analysisRequestList;

    public function setAnalysisRequestList($arrayCollection)
    {
        $this->analysisRequestList = $arrayCollection;
        return $this;
    }

    public function getAnalysisRequestList()
    {
        return $this->getFirstRecordOfAnalysisRequest($this->analysisRequestList);
    }
    
    
    /**
     * get recently extracted report
     * 
     *@param Object $entityObject Description
     * 
     *@return Object 
     **/
    public function filterUpdateDtTm($entityObject)
    {                
        $currentDtTm = new \DateTime("now");
        if (is_object($entityObject)) {
            $criteria = Criteria::create()
                ->where(Criteria::expr()->eq("deleteFlag", 0))                
                ->orderBy(array('updatedDtTm' => Criteria::DESC));                
            $entityObjectWithFilter = $entityObject->matching($criteria);

            return $entityObjectWithFilter;
        }

    }
    
    public function getFirstRecordOfAnalysisRequest($entityObject) {
        
         $entityObjectWithFilter = $this->filterUpdateDtTm($entityObject);
         return $entityObjectWithFilter->first();
    }
    
    
    /**
     * Collection for Rulebook has risk list 
     * 
     * @var \Application\Entity\RulebookHasRisk Description
     *
     * @param \Application\Entity\RulebookHasRisk RulebookHasRisk List
     * @ORM\OneToMany(targetEntity="RulebookHasRisk", mappedBy="rulebookId")
     *  
     * @Accessor(getter="getRulebookHasRiskList",setter="setRulebookHasRiskList")     * 
     */  
    private $rulebookHasRiskList;

    public function setRulebookHasRiskList($arrayCollection)
    {
        $this->rulebookHasRiskList = $arrayCollection;
        return $this;
    }

    public function getRulebookHasRiskList()
    {
        return $this->rulebookHasRiskList;
    }    
    
}
