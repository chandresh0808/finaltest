<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;

/**
 * Risk
 *
 * @ORM\Table(name="risk", indexes={@ORM\Index(name="rulebook_id", columns={"rulebook_id"})})
 * @ORM\Entity
 */
class Risk extends AbstractEntity
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
     * @ORM\Column(name="is_sap_default_risk", type="boolean", nullable=false)
     */
    private $isSapDefaultRisk = '0';
    
    /**
     * Set rulebookId
     *
     * @param integer $rulebookId
     * @return Risk
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
     * Set isSapDefaultRisk
     *
     * @param boolean $isSapDefaultRisk
     * @return Risk
     */
    public function setIsSapDefaultRisk($isSapDefaultRisk)
    {
        $this->isSapDefaultRisk = $isSapDefaultRisk;

        return $this;
    }

    /**
     * Get isSapDefaultRisk
     *
     * @return boolean 
     */
    public function getIsSapDefaultRisk()
    {
        return $this->isSapDefaultRisk;
    }
    
}
