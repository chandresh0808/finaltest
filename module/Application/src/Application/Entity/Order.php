<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;

/**
 * Order
 *
 * @ORM\Table(name="`order`", indexes={@ORM\Index(name="cart_id", columns={"cart_id"}), @ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class Order extends AbstractEntity
{
    
    
    function __construct()
    {
        $arrayCollection =   new \Doctrine\Common\Collections\ArrayCollection();
        $this->setPaymentMethod($arrayCollection);
        $this->setOrderDetails($arrayCollection);
    }
    

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
    
    
    /**
     * Collection for Rule book list 
     * 
     * @var \Application\Entity\OrderDetails Description
     *
     * @param \Application\Entity\OrderDetails Rule Book List
     * @ORM\OneToMany(targetEntity="OrderDetails", mappedBy="orderId")
     */
    private $orderDetails;

    public function setOrderDetails($arrayCollection)
    {
        $this->orderDetails = $arrayCollection;
        return $this;
    }

    public function getOrderDetails()
    {
        return $this->setConditionActiveFlag($this->orderDetails);
    }
    
    
    /**
     * Collection for Rule book list 
     * 
     * @var \Application\Entity\PaymentMethod Description
     *
     * @param \Application\Entity\PaymentMethod PaymentMethod
     * @ORM\OneToMany(targetEntity="PaymentMethod", mappedBy="orderId")
     */
    private $paymentMethod;

    public function setPaymentMethod($arrayCollection)
    {
        $this->paymentMethod = $arrayCollection;
        return $this;
    }

    public function getPaymentMethod()
    {
        return $this->setConditionActiveFlag($this->paymentMethod);
    }
    
    
}
