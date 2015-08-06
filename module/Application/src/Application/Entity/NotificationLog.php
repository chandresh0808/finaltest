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
 * @ORM\Table(name="notification_log")
 * @ORM\Entity
 * @ExclusionPolicy("all")
 */
class NotificationLog extends AbstractEntity
{
        
    /**
     * @var integer
     *
     * @ORM\Column(name="analysis_request_id", type="integer", nullable=false)
     * @Expose
     */
    private $analysisRequestId;


    /**
     * @var string
     *
     * @ORM\Column(name="system_param_key", type="string", length=150, nullable=true)
     * @Expose
     */
    private $systemParamKey;
    


    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", nullable=false)
     * @Expose
     */
    private $status = 'Pending';
           
    
    /**
     * Set $analysisRequestId
     *
     * @param integer $analysisRequestId
     * @return AnalysisRequest
     */
    public function setAnalysisRequestId($analysisRequestId)
    {
        $this->analysisRequestId = $analysisRequestId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getAnalysisRequestId()
    {
        return $this->analysisRequestId;
    }

    /**
     * Set systemParamKey
     *
     * @param integer $rulebookId
     * @return AnalysisRequest
     */
    public function setSystemParamKey($systemParamKey)
    {
        $this->systemParamKey = $systemParamKey;

        return $this;
    }

    /**
     * Get rulebookId
     *
     * @return integer 
     */
    public function getSystemParamKey()
    {
        return $this->systemParamKey;
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
