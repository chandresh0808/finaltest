<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * AnalysisRequestReportFile
 *
 * @ORM\Table(name="analysis_request_report_file", indexes={@ORM\Index(name="analysis_request_id", columns={"analysis_request_id"})})
 * @ORM\Entity
 * @ExclusionPolicy("all")
 */
class AnalysisRequestReportFile extends AbstractEntity
{

    /**
     * @var string
     *
     * @ORM\Column(name="analysis_request_id", type="string", length=50, nullable=false)
     * @Expose
     */
    private $analysisRequestId;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="package_has_credits_id", type="integer", nullable=false)
     */
    private $packageHasCreditsId;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="file_name", type="string", length=100, nullable=true)
     * @Expose
     */
    private $fileName;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expire_date", type="datetime", nullable=true)
     * @Expose
     */
    protected $expireDate;
    
   
    /**
     * Set analysisRequestId
     *
     * @param string $analysisRequestId
     * @return AnalysisRequestReportFile
     */
    public function setAnalysisRequestId($analysisRequestId)
    {
        $this->analysisRequestId = $analysisRequestId;

        return $this;
    }

    /**
     * Get analysisRequestId
     *
     * @return string 
     */
    public function getAnalysisRequestId()
    {
        return $this->analysisRequestId;
    }
    
     /**
     * Set packageHasCreditsId
     *
     * @param integer $packageHasCreditsId
     * @return AnalysisRequestReportFile
     */
    public function setPackageHasCreditsId($packageHasCreditsId)
    {
        $this->packageHasCreditsId = $packageHasCreditsId;

        return $this;
    }

    /**
     * Get packageHasCreditsId
     *
     * @return integer 
     */
    public function getPackageHasCreditsId()
    {
        return $this->packageHasCreditsId;
    }
    
    
    /**
     * Set packageHasCreditsId
     *
     * @param integer $fileName
     * @return AnalysisRequestReportFile
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get packageHasCreditsId
     *
     * @return integer 
     */
    public function getFileName()
    {
        return $this->fileName;
    }
    
    
        /**
     * Set packageHasCreditsId
     *
     * @param integer $expireDate
     * @return AnalysisRequestReportFile
     */
    public function setExpireDate($expireDate)
    {
        $this->expireDate = $expireDate;

        return $this;
    }

    /**
     * Get packageHasCreditsId
     *
     * @return integer 
     */
    public function getExpireDate()
    {
        return $this->expireDate;
    }
}
