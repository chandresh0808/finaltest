<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;

/**
 * JobFunction
 *
 * @ORM\Table(name="job_function", indexes={@ORM\Index(name="rulebook_id", columns={"rulebook_id"})})
 * @ORM\Entity
 */
class JobFunction extends AbstractEntity
{
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="rulebook_id", type="integer", nullable=false)
     */
    private $rulebookId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_sap_default_job_function", type="boolean", nullable=false)
     */
    private $isSapDefaultJobFunction = '0';
    
    /**
     * Set rulebookId
     *
     * @param integer $rulebookId
     * @return JobFunction
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
     * Set isSapDefaultJobFunction
     *
     * @param boolean $isSapDefaultJobFunction
     * @return JobFunction
     */
    public function setIsSapDefaultJobFunction($isSapDefaultJobFunction)
    {
        $this->isSapDefaultJobFunction = $isSapDefaultJobFunction;

        return $this;
    }

    /**
     * Get isSapDefaultJobFunction
     *
     * @return boolean 
     */
    public function getIsSapDefaultJobFunction()
    {
        return $this->isSapDefaultJobFunction;
    }
    
}
