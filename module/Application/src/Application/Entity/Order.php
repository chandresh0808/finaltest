<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;

/**
 * Order
 *
 * @ORM\Table(name="order", indexes={@ORM\Index(name="cart_id", columns={"cart_id"}), @ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class Order extends AbstractEntity
{

    /**
     * @var integer
     *
     * @ORM\Column(name="cart_id", type="integer", nullable=false)
     */
    private $cartId;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var float
     *
     * @ORM\Column(name="order_total", type="float", precision=10, scale=0, nullable=false)
     */
    private $orderTotal;

    /**
     * @var string
     *
     * @ORM\Column(name="order_session_id", type="string", length=45, nullable=true)
     */
    private $orderSessionId;

    /**
     * @var string
     *
     * @ORM\Column(name="order_ip_address", type="string", length=45, nullable=true)
     */
    private $orderIpAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="order_status", type="string", nullable=false)
     */
    private $orderStatus = 'Pending';

    /**
     * @var string
     *
     * @ORM\Column(name="order_email", type="string", length=100, nullable=true)
     */
    private $orderEmail;
   
    /**
     * Set cartId
     *
     * @param integer $cartId
     * @return Order
     */
    public function setCartId($cartId)
    {
        $this->cartId = $cartId;

        return $this;
    }

    /**
     * Get cartId
     *
     * @return integer 
     */
    public function getCartId()
    {
        return $this->cartId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return Order
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
     * Set orderTotal
     *
     * @param float $orderTotal
     * @return Order
     */
    public function setOrderTotal($orderTotal)
    {
        $this->orderTotal = $orderTotal;

        return $this;
    }

    /**
     * Get orderTotal
     *
     * @return float 
     */
    public function getOrderTotal()
    {
        return $this->orderTotal;
    }

    /**
     * Set orderSessionId
     *
     * @param string $orderSessionId
     * @return Order
     */
    public function setOrderSessionId($orderSessionId)
    {
        $this->orderSessionId = $orderSessionId;

        return $this;
    }

    /**
     * Get orderSessionId
     *
     * @return string 
     */
    public function getOrderSessionId()
    {
        return $this->orderSessionId;
    }

    /**
     * Set orderIpAddress
     *
     * @param string $orderIpAddress
     * @return Order
     */
    public function setOrderIpAddress($orderIpAddress)
    {
        $this->orderIpAddress = $orderIpAddress;

        return $this;
    }

    /**
     * Get orderIpAddress
     *
     * @return string 
     */
    public function getOrderIpAddress()
    {
        return $this->orderIpAddress;
    }

    /**
     * Set orderStatus
     *
     * @param string $orderStatus
     * @return Order
     */
    public function setOrderStatus($orderStatus)
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }

    /**
     * Get orderStatus
     *
     * @return string 
     */
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

    /**
     * Set orderEmail
     *
     * @param string $orderEmail
     * @return Order
     */
    public function setOrderEmail($orderEmail)
    {
        $this->orderEmail = $orderEmail;

        return $this;
    }

    /**
     * Get orderEmail
     *
     * @return string 
     */
    public function getOrderEmail()
    {
        return $this->orderEmail;
    }
    
}
