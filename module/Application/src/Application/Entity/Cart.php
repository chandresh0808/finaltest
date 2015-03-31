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
    
}
