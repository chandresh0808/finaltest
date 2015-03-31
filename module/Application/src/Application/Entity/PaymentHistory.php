<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;

/**
 * PaymentHistory
 *
 * @ORM\Table(name="payment_history", indexes={@ORM\Index(name="order_id", columns={"order_id"})})
 * @ORM\Entity
 */
class PaymentHistory extends AbstractEntity
{

    /**
     * @var integer
     *
     * @ORM\Column(name="order_id", type="integer", nullable=true)
     */
    private $orderId;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_request", type="text", length=65535, nullable=true)
     */
    private $paymentRequest;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_response", type="text", length=65535, nullable=true)
     */
    private $paymentResponse;

  
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
     * @param string $paymentRequest
     * @return PaymentHistory
     */
    public function setPaymentRequest($paymentRequest)
    {
        $this->paymentRequest = $paymentRequest;

        return $this;
    }

    /**
     * Get paymentRequest
     *
     * @return string 
     */
    public function getPaymentRequest()
    {
        return $this->paymentRequest;
    }

    /**
     * Set paymentResponse
     *
     * @param string $paymentResponse
     * @return PaymentHistory
     */
    public function setPaymentResponse($paymentResponse)
    {
        $this->paymentResponse = $paymentResponse;

        return $this;
    }

    /**
     * Get paymentResponse
     *
     * @return string 
     */
    public function getPaymentResponse()
    {
        return $this->paymentResponse;
    }
    
}
