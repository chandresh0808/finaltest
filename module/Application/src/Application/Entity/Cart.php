<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;

/**
 * Cart
 *
 * @ORM\Table(name="cart")
 * @ORM\Entity
 */
class Cart extends AbstractEntity
{
    
    
    function __construct()
    {
        $arrayCollection =   new \Doctrine\Common\Collections\ArrayCollection();
        $this->setItemList($arrayCollection);      
        $this->setOrder($arrayCollection); 
    }

    /**
     * @var string
     *
     * @ORM\Column(name="cart_ip_address", type="string", length=45, nullable=false)
     */
    private $cartIpAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="cart_session_id", type="string", length=45, nullable=false)
     */
    private $cartSessionId;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="email_address", type="string", length=255, nullable=true)
     */
    private $emailAddress;

    /**
     * Set cartIpAddress
     *
     * @param string $cartIpAddress
     * @return Cart
     */
    public function setCartIpAddress($cartIpAddress)
    {
        $this->cartIpAddress = $cartIpAddress;

        return $this;
    }

    /**
     * Get cartIpAddress
     *
     * @return string 
     */
    public function getCartIpAddress()
    {
        return $this->cartIpAddress;
    }

    /**
     * Set cartSessionId
     *
     * @param string $cartSessionId
     * @return Cart
     */
    public function setCartSessionId($cartSessionId)
    {
        $this->cartSessionId = $cartSessionId;

        return $this;
    }

    /**
     * Get cartSessionId
     *
     * @return string 
     */
    public function getCartSessionId()
    {
        return $this->cartSessionId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return Cart
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
     * Set emailAddress
     *
     * @param string $emailAddress
     * @return Cart
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    /**
     * Get emailAddress
     *
     * @return string 
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }
    
    
    
    /**
     * Collection for item
     * 
     * @var \Application\Entity\Item Description
     *
     * @param \Application\Entity\Item Item
     * @ORM\OneToMany(targetEntity="Item", mappedBy="cartId")
     */
    private $itemList;

    public function setItemList($arrayCollection)
    {
        $this->itemList = $arrayCollection;
        return $this;
    }

    public function getItemList()
    {
        return $this->setConditionActiveFlag($this->itemList);
    }
    
    
     /**
     * Collection for item
     * 
     * @var \Application\Entity\Order Description
     *
     * @param \Application\Entity\Order Item
     * @ORM\OneToMany(targetEntity="Order", mappedBy="cartId")
     */
    private $order;

    public function setOrder($arrayCollection)
    {
        $this->order = $arrayCollection;
        return $this;
    }

    public function getOrder()
    {
        return $this->setConditionActiveFlag($this->order);
    }

}
    
