<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Accessor;
use Doctrine\Common\Collections\Criteria;

/**
 * AnalysisRequest
 *
 * @ORM\Table(name="extracts", indexes={@ORM\Index(name="analysis_request_guid", columns={"analysis_request_guid", "parent_user_id", "status"})})
 * @ORM\Entity
 * @ExclusionPolicy("all")
 */
class Extracts extends AbstractEntity
{
    
    
        function __construct()
    {
        $arrayCollection = new \Doctrine\Common\Collections\ArrayCollection();       
        $this->setAnalysisRequestList($arrayCollection);
    }
        
    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     * @Expose
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="parent_user_id", type="integer", nullable=true)
     * @Expose
     */
    private $parentUserId;

    /**
     * @var string
     *
     * @ORM\Column(name="extract_name", type="string", length=100, nullable=true)
     * @Expose
     */
    private $extractName;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="extract_file_name", type="text", nullable=true)
     */
    private $extractFileName;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="system_salt_id", type="integer", nullable=false)
     */
    private $systemSaltId;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", nullable=false)
     * @Expose
     */
    private $status = 'Pending';
    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="job_id", type="bigint", nullable=false)
     */
    private $jobId;            
    
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

    /**
     * Set rulebookId
     *
     * @param integer $parentUserId
     * @return AnalysisRequest
     */
    public function setParentUserId($parentUserId)
    {
        $this->parentUserId = $parentUserId;

        return $this;
    }

    /**
     * Get rulebookId
     *
     * @return integer 
     */
    public function getParentUserId()
    {
        return $this->parentUserId;
    }

    /**
     * Set extractName
     *
     * @param string $extractName
     * @return AnalysisRequest
     */
    public function setExtractName($extractName)
    {
        $this->extractName = $extractName;

        return $this;
    }

    /**
     * Get extractName
     *
     * @return string 
     */
    public function getExtractName()
    {
        return $this->extractName;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return AnalysisRequest
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    
    /**
     * Set systemSaltId
     *
     * @param integer $systemSaltId
     * @return AnalysisRequest
     */
    public function setSystemSaltId($systemSaltId)
    {
        $this->systemSaltId = $systemSaltId;

        return $this;
    }

    /**
     * Get systemSaltId
     *
     * @return integer 
     */
    public function getSystemSaltId()
    {
        return $this->systemSaltId;
    }

    
    /**
     * Set extractName
     *
     * @param string $extractFileName
     * @return AnalysisRequest
     */
    public function setExtractFileName($extractFileName)
    {
        $this->extractFileName = $extractFileName;

        return $this;
    }

    /**
     * Get extractName
     *
     * @return string 
     */
    public function getExtractFileName()
    {
        return $this->extractFileName;
    }
    
     
    /**
     * Set Job id
     *
     * @param string $extractFileName
     * @return AnalysisRequest
     */
    public function setJobId($jobId)
    {
        $this->jobId = $jobId;

        return $this;
    }

    /**
     * Get job id
     *
     * @return string 
     */
    public function getJobId()
    {
        return $this->jobId;
    }
    
    
    /**
     * Collection for Rule book list 
     * 
     * @var \Application\Entity\AnalysisRequest Description
     *
     * @param \Application\Entity\AnalysisRequest Analysis List
     * @ORM\OneToMany(targetEntity="AnalysisRequest", mappedBy="extractId")
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
        return $this->filterUpdateDtTm($this->analysisRequestList);
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
        if (is_object($entityObject)) {
            $criteria = Criteria::create()
                ->where(Criteria::expr()->eq("deleteFlag", 0))                
                ->orderBy(array('fileExpireDtTm' => Criteria::DESC));                
            $entityObjectWithFilter = $entityObject->matching($criteria);

            return $entityObjectWithFilter;
        }

    }
        
}
