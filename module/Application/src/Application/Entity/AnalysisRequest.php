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
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

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
     */
    private $status = 'Pending';

    
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
    
}
