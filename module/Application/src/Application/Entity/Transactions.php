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
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="rulebook_id", type="integer", nullable=false)
     */
    private $rulebookId;

    /**
     * @var string
     *
     * @ORM\Column(name="sap_transaction_id", type="string", length=50, nullable=false)
     */
    protected $sapTransactionId;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_default_transaction", type="boolean", nullable=false)
     */
    private $isDefaultTransaction = '0';

    
    /**
     * Set description
     *
     * @param integer $description
     * @return Transactions
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get rulebookId
     *
     * @return integer 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set isSapDefaultTransaction
     *
     * @param boolean $isDefaultTransaction
     * @return Transactions
     */
    public function setIsDefaultTransaction($isDefaultTransaction)
    {
        $this->isDefaultTransaction = $isDefaultTransaction;

        return $this;
    }

    /**
     * Get isSapDefaultTransaction
     *
     * @return boolean 
     */
    public function getIsDefaultTransaction()
    {
        return $this->isDefaultTransaction;
    }
    
     /**
     * Set isSapDefaultTransaction
     *
     * @param boolean $sapTransactionId
     * @return Transactions
     */
    public function setSapTransactionId($sapTransactionId)
    {
        $this->sapTransactionId = $sapTransactionId;

        return $this;
    }

    /**
     * Get isSapDefaultTransaction
     *
     * @return boolean 
     */
    public function getSapTransactionId()
    {
        return $this->sapTransactionId;
    }
    
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
     * Set userId
     *
     * @param integer $userId
     * @return Rulebook
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
    
}
