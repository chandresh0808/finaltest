<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;

/**
 * Transactions
 *
 * @ORM\Table(name="transactions", indexes={@ORM\Index(name="rulebook_id", columns={"rulebook_id"})})
 * @ORM\Entity
 */
class Transactions extends AbstractEntity
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
     * @ORM\Column(name="is_sap_default_transaction", type="boolean", nullable=false)
     */
    private $isSapDefaultTransaction = '0';

    
    /**
     * Set rulebookId
     *
     * @param integer $rulebookId
     * @return Transactions
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
     * Set isSapDefaultTransaction
     *
     * @param boolean $isSapDefaultTransaction
     * @return Transactions
     */
    public function setIsSapDefaultTransaction($isSapDefaultTransaction)
    {
        $this->isSapDefaultTransaction = $isSapDefaultTransaction;

        return $this;
    }

    /**
     * Get isSapDefaultTransaction
     *
     * @return boolean 
     */
    public function getIsSapDefaultTransaction()
    {
        return $this->isSapDefaultTransaction;
    }
    
}
