<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;

/**
 * JobFunctionHasTransaction
 *
 * @ORM\Table(name="job_function_has_transaction", indexes={@ORM\Index(name="job_function_id", columns={"job_function_id", "transaction_id"})})
 * @ORM\Entity
 */
class JobFunctionHasTransaction extends AbstractEntity
{

    /**
     * @var integer
     *
     * @ORM\Column(name="job_function_id", type="integer", nullable=false)
     */
    private $jobFunctionId;

    /**
     * @var integer
     *
     * @ORM\Column(name="transaction_id", type="integer", nullable=false)
     */
    private $transactionId;
    
    /**
     * Set jobFunctionId
     *
     * @param integer $jobFunctionId
     * @return JobFunctionHasTransaction
     */
    public function setJobFunctionId($jobFunctionId)
    {
        $this->jobFunctionId = $jobFunctionId;

        return $this;
    }

    /**
     * Get jobFunctionId
     *
     * @return integer 
     */
    public function getJobFunctionId()
    {
        return $this->jobFunctionId;
    }

    /**
     * Set transactionId
     *
     * @param integer $transactionId
     * @return JobFunctionHasTransaction
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Get transactionId
     *
     * @return integer 
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }
   
}
