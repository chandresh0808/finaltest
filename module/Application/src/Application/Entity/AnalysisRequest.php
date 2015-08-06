<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Accessor;

/**
 * AnalysisRequest
 *
 * @ORM\Table(name="analysis_request", indexes={@ORM\Index(name="analysis_request_guid", columns={"analysis_request_guid", "rulebook_id", "status"})})
 * @ORM\Entity
 * @ExclusionPolicy("all")
 */
class AnalysisRequest extends AbstractEntity
{
    
    
        
    function __construct()
    {
        $arrayCollection =   new \Doctrine\Common\Collections\ArrayCollection();
        $this->setAnalysisRequestReportFile($arrayCollection);
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
     * @var integer
     *
     * @ORM\Column(name="rulebook_id", type="integer", nullable=true)
     * @Expose
     */
    private $rulebookId;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="extract_id", type="integer", nullable=true)
     * @Expose
     */
    private $extractId;

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
     * @var string
     *
     * @ORM\Column(name="status", type="string", nullable=false)
     * @Expose
     */
    private $status = 'Pending';
        
    
    /**
     * @var integer
     *
     * @ORM\Column(name="is_free_trial_request", type="boolean", nullable=false)
     */
    private $isFreeTrialRequest;
    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="file_expire_dt_tm", type="datetime", nullable=false)
     * @Expose
     */
    private $fileExpireDtTm;

    
    /**
     * @var integer
     *
     * @ORM\Column(name="file_created_dt_tm", type="datetime", nullable=false)
     * @Expose
     */
    private $fileCreatedDtTm;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="analysis_request_name", type="string", length=255, nullable=true)
     * @Expose
     */
    private $analysisRequestName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="analysis_request_description", type="text", nullable=true)
     * @Expose
     */
    private $analysisRequestDescription;
    
    
    
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
     * @param integer $rulebookId
     * @return AnalysisRequest
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
    
    
    /*
     * set free trial flag
     * @param int $isFreeTrialRequest
     * @return AnalysisRequest
     */
    public function setIsFreeTrialRequest($isFreeTrialRequest)
    {
        $this->isFreeTrialRequest = $isFreeTrialRequest;

        return $this;
    }
    
    /*
     * get free trial flag
     * 
     * @return int
     */
    public function getIsFreeTrialRequest()
    {
        return $this->isFreeTrialRequest;
    }
    
    
        
    /**
     * Collection for Rule book list 
     * 
     * @var \Application\Entity\AnalysisRequestReportFile Description
     *
     * @param \Application\Entity\AnalysisRequestReportFile Rule Book List
     * @ORM\OneToMany(targetEntity="AnalysisRequestReportFile", mappedBy="analysisRequestId")
     * @Expose
     * @Accessor(getter="getAnalysisRequestReportFile",setter="setAnalysisRequestReportFile")
     */
    private $analysisRequestReportFile;

    public function setAnalysisRequestReportFile($arrayCollection)
    {
        $this->analysisRequestReportFile = $arrayCollection;
        return $this;
    }

    public function getAnalysisRequestReportFile()
    {
        return $this->setConditionActiveFlag($this->analysisRequestReportFile);
    }
    
    
    
    /*
     * set free trial flag
     * @param int $isFreeTrialRequest
     * @return AnalysisRequest
     */
    public function setFileExpireDtTm($isFreeTrialRequest)
    {
        $this->fileExpireDtTm = $isFreeTrialRequest;

        return $this;
    }
    
    /*
     * get free trial flag
     * 
     * @return int
     */
    public function getFileExpireDtTm()
    {
        return $this->fileExpireDtTm;
    }
    
     /*
     * set free trial flag
     * @param int $isFreeTrialRequest
     * @return AnalysisRequest
     */
    public function setFileCreatedDtTm($fileCreatedDtTm)
    {
        $this->fileCreatedDtTm = $fileCreatedDtTm;

        return $this;
    }
    
    /*
     * get free trial flag
     * 
     * @return int
     */
    public function getFileCreatedDtTm()
    {
        return $this->fileCreatedDtTm;
    }
    
    
    /*
     * set free trial flag
     * @param int $isFreeTrialRequest
     * @return AnalysisRequest
     */
    public function setExtractId($fileCreatedDtTm)
    {
        $this->extractId = $fileCreatedDtTm;

        return $this;
    }
    
    /*
     * get free trial flag
     * 
     * @return int
     */
    public function getExtractId()
    {
        return $this->extractId;
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
     * Set rulebookId
     *
     * @param integer $parentUserId
     * @return AnalysisRequest
     */
    public function setAnalysisRequestDescription($parentUserId)
    {
        $this->analysisRequestDescription = $parentUserId;

        return $this;
    }

    /**
     * Get rulebookId
     *
     * @return integer 
     */
    public function getAnalysisRequestDescription()
    {
        return $this->analysisRequestDescription;
    }
    
    
        
    /**
     * Set rulebookId
     *
     * @param integer $parentUserId
     * @return AnalysisRequest
     */
    public function setAnalysisRequestName($parentUserId)
    {
        $this->analysisRequestName = $parentUserId;

        return $this;
    }

    /**
     * Get rulebookId
     *
     * @return integer 
     */
    public function getAnalysisRequestName()
    {
        return $this->analysisRequestName;
    }
    
}
