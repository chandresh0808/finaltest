<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;

/**
 * AnalysisRequest
 *
 * @ORM\Table(name="analysis_request", indexes={@ORM\Index(name="analysis_request_guid", columns={"analysis_request_guid", "rulebook_id", "status"})})
 * @ORM\Entity
 */
class AnalysisRequest extends AbstractEntity
{
    
    /**
     * @var string
     *
     * @ORM\Column(name="analysis_request_guid", type="string", length=50, nullable=false)
     */
    private $analysisRequestGuid;

    /**
     * @var integer
     *
     * @ORM\Column(name="rulebook_id", type="integer", nullable=true)
     */
    private $rulebookId;

    /**
     * @var string
     *
     * @ORM\Column(name="extract_name", type="string", length=100, nullable=true)
     */
    private $extractName;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", nullable=false)
     */
    private $status = 'Pending';

    
    /**
     * Set analysisRequestGuid
     *
     * @param string $analysisRequestGuid
     * @return AnalysisRequest
     */
    public function setAnalysisRequestGuid($analysisRequestGuid)
    {
        $this->analysisRequestGuid = $analysisRequestGuid;

        return $this;
    }

    /**
     * Get analysisRequestGuid
     *
     * @return string 
     */
    public function getAnalysisRequestGuid()
    {
        return $this->analysisRequestGuid;
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
    
}
