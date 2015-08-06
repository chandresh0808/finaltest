<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;

/**
 * PaymentHistory
 *
 * @ORM\Table(name="payment_method")
 * @ORM\Entity
 */
class PaymentMethod extends AbstractEntity
{

    /**
     * @var integer
     *
     * @ORM\Column(name="order_id", type="integer", nullable=true)
     */
    private $orderId;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=true)
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="last_four_digits", type="string", length=255, nullable=true)
     */
    private $lastFourDigits;

    /**
     * @var string
     *
     * @ORM\Column(name="credit_card_type", type="string", length=255, nullable=true)
     */
    private $creditCardType;
    
    /**
     * @var string
     *
     * @ORM\Column(name="card_holder_name", type="string", length=255, nullable=true)
     */
    private $creditHolderName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="expiry_year_month", type="string", length=255, nullable=true)
     */
    private $expiryYearMonth;

  
    /**
     * Set orderId
     *
     * @param integer $orderId
     * @return PaymentHistory
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * Get orderId
     *
     * @return integer 
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Set paymentRequest
     *
     * @param string $userId
     * @return PaymentHistory
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get paymentRequest
     *
     * @return string 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set paymentResponse
     *
     * @param string $lastFourDigits
     * @return PaymentHistory
     */
    public function setLastFourDigits($lastFourDigits)
    {
        $this->lastFourDigits = $lastFourDigits;

        return $this;
    }

    /**
     * Get paymentResponse
     *
     * @return string 
     */
    public function getLastFourDigits()
    {
        return $this->lastFourDigits;
    }
    
    /**
     * Set paymentResponse
     *
     * @param string $creditCartType
     * @return PaymentHistory
     */
    public function setCreditCardType($creditCartType)
    {
        $this->creditCardType = $creditCartType;

        return $this;
    }

    /**
     * Get paymentResponse
     *
     * @return string 
     */
    public function getCreditCardType()
    {
        return $this->creditCardType;
    }
    
    /**
     * Set paymentResponse
     *
     * @param string $lastFourDigits
     * @return PaymentHistory
     */
    public function setCreditHolderName($creditHolderName)
    {
        $this->creditHolderName = $creditHolderName;

        return $this;
    }

    /**
     * Get paymentResponse
     *
     * @return string 
     */
    public function getCreditHolderName()
    {
        return $this->creditHolderName;
    }
    
    /**
     * Set paymentResponse
     *
     * @param string $expiryYearMonth
     * @return PaymentHistory
     */
    public function setExpiryYearMonth($expiryYearMonth)
    {
        $this->expiryYearMonth = $expiryYearMonth;

        return $this;
    }

    /**
     * Get paymentResponse
     *
     * @return string 
     */
    public function getExpiryYearMonth()
    {
        return $this->expiryYearMonth;
    }
    
    
}
