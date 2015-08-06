<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;

/**
 * AnalysisRequest
 *
 * @ORM\Table(name="activity")
 * @ORM\Entity
 * 
 */
class Activity extends AbstractEntity
{
    
    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="string", length=100, nullable=true)
     * 
     */
    private $type;
    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="code", type="string", length=10, nullable=true)
     * 
     */
    private $code;
                  
    /**
     * Set userId
     *
     * @param integer $userId
     * @return AnalysisRequest
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set rulebookId
     *
     * @param integer $rulebookId
     * @return AnalysisRequest
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get rulebookId
     *
     * @return integer 
     */
    public function getCode()
    {
        return $this->code;
    }
    
    
}
