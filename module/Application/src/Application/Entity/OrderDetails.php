<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;

/**
 * OrderDetails
 *
 * @ORM\Table(name="order_details", indexes={@ORM\Index(name="order_id", columns={"order_id"})})
 * @ORM\Entity
 */
class OrderDetails extends AbstractEntity
{

    /**
     * @var string
     *
     * @ORM\Column(name="order_id", type="string", length=45, nullable=true)
     */
    private $orderId;

    /**
     * @var string
     *
     * @ORM\Column(name="order_first_name", type="string", length=50, nullable=true)
     */
    private $orderFirstName;

    /**
     * @var string
     *
     * @ORM\Column(name="order_last_name", type="string", length=50, nullable=true)
     */
    private $orderLastName;

    /**
     * @var string
     *
     * @ORM\Column(name="order_address_1", type="string", length=255, nullable=true)
     */
    private $orderAddress1;

    /**
     * @var string
     *
     * @ORM\Column(name="order_address_2", type="string", length=255, nullable=true)
     */
    private $orderAddress2;

    /**
     * @var string
     *
     * @ORM\Column(name="order_state", type="string", length=45, nullable=true)
     */
    private $orderState;

    /**
     * @var string
     *
     * @ORM\Column(name="order_country", type="string", length=45, nullable=true)
     */
    private $orderCountry;

    /**
     * @var string
     *
     * @ORM\Column(name="order_zipcode", type="string", length=45, nullable=true)
     */
    private $orderZipcode;

    /**
     * @var string
     *
     * @ORM\Column(name="order_phone", type="string", length=45, nullable=true)
     */
    private $orderPhone;
    
    /**
     * Set orderId
     *
     * @param string $orderId
     * @return OrderDetails
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * Get orderId
     *
     * @return string 
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Set orderFirstName
     *
     * @param string $orderFirstName
     * @return OrderDetails
     */
    public function setOrderFirstName($orderFirstName)
    {
        $this->orderFirstName = $orderFirstName;

        return $this;
    }

    /**
     * Get orderFirstName
     *
     * @return string 
     */
    public function getOrderFirstName()
    {
        return $this->orderFirstName;
    }

    /**
     * Set orderLastName
     *
     * @param string $orderLastName
     * @return OrderDetails
     */
    public function setOrderLastName($orderLastName)
    {
        $this->orderLastName = $orderLastName;

        return $this;
    }

    /**
     * Get orderLastName
     *
     * @return string 
     */
    public function getOrderLastName()
    {
        return $this->orderLastName;
    }

    /**
     * Set orderAddress1
     *
     * @param string $orderAddress1
     * @return OrderDetails
     */
    public function setOrderAddress1($orderAddress1)
    {
        $this->orderAddress1 = $orderAddress1;

        return $this;
    }

    /**
     * Get orderAddress1
     *
     * @return string 
     */
    public function getOrderAddress1()
    {
        return $this->orderAddress1;
    }

    /**
     * Set orderAddress2
     *
     * @param string $orderAddress2
     * @return OrderDetails
     */
    public function setOrderAddress2($orderAddress2)
    {
        $this->orderAddress2 = $orderAddress2;

        return $this;
    }

    /**
     * Get orderAddress2
     *
     * @return string 
     */
    public function getOrderAddress2()
    {
        return $this->orderAddress2;
    }

    /**
     * Set orderState
     *
     * @param string $orderState
     * @return OrderDetails
     */
    public function setOrderState($orderState)
    {
        $this->orderState = $orderState;

        return $this;
    }

    /**
     * Get orderState
     *
     * @return string 
     */
    public function getOrderState()
    {
        return $this->orderState;
    }

    /**
     * Set orderCountry
     *
     * @param string $orderCountry
     * @return OrderDetails
     */
    public function setOrderCountry($orderCountry)
    {
        $this->orderCountry = $orderCountry;

        return $this;
    }

    /**
     * Get orderCountry
     *
     * @return string 
     */
    public function getOrderCountry()
    {
        return $this->orderCountry;
    }

    /**
     * Set orderZipcode
     *
     * @param string $orderZipcode
     * @return OrderDetails
     */
    public function setOrderZipcode($orderZipcode)
    {
        $this->orderZipcode = $orderZipcode;

        return $this;
    }

    /**
     * Get orderZipcode
     *
     * @return string 
     */
    public function getOrderZipcode()
    {
        return $this->orderZipcode;
    }

    /**
     * Set orderPhone
     *
     * @param string $orderPhone
     * @return OrderDetails
     */
    public function setOrderPhone($orderPhone)
    {
        $this->orderPhone = $orderPhone;

        return $this;
    }

    /**
     * Get orderPhone
     *
     * @return string 
     */
    public function getOrderPhone()
    {
        return $this->orderPhone;
    }
    
}
