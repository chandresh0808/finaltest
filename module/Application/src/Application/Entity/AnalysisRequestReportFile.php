<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;

/**
 * AnalysisRequestReportFile
 *
 * @ORM\Table(name="analysis_request_report_file", indexes={@ORM\Index(name="analysis_request_id", columns={"analysis_request_id"})})
 * @ORM\Entity
 */
class AnalysisRequestReportFile extends AbstractEntity
{

    /**
     * @var string
     *
     * @ORM\Column(name="analysis_request_id", type="string", length=50, nullable=false)
     */
    private $analysisRequestId;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="package_has_credits_id", type="integer", nullable=false)
     */
    private $packageHasCreditsId;
   
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
}
