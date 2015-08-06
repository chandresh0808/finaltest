<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;

/**
 * Item
 *
 * @ORM\Table(name="item", indexes={@ORM\Index(name="cart_id", columns={"cart_id"}), @ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="package_id", columns={"package_id"})})
 * @ORM\Entity
 */
class Item extends AbstractEntity
{
    
    function __construct()
    {
        $arrayCollection =   new \Doctrine\Common\Collections\ArrayCollection();
        //$this->setPackage($arrayCollection);

    }

    /**
     * @var integer
     *
     * @ORM\Column(name="cart_id", type="integer", nullable=false)
     */
    private $cartId;

    /**
     * @var \Application\Entity\Package
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Package")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="package_id", referencedColumnName="id")
     * })
     * 
     */
    
    private $package;
    
    /**
     * @var \Application\Entity\PackageHasCredits
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\PackageHasCredits")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="package_has_credits_id", referencedColumnName="id")
     * })
     * 
     */
    private $packageHasCredits;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="item_price", type="string", length=45, nullable=true)
     */
    private $itemPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="quantity", type="string", length=45, nullable=true)
     */
    private $quantity;
   

    /**
     * Set cartId
     *
     * @param integer $cartId
     * @return Item
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
     * Set packageId
     *
     * @param integer $packageId
     * @return Item
     */
    public function setPackage($package)
    {
        $this->package = $package;

        return $this;
    }

    /**
     * Get packageId
     *
     * @return integer 
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return Item
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
     * Set itemPrice
     *
     * @param string $itemPrice
     * @return Item
     */
    public function setItemPrice($itemPrice)
    {
        $this->itemPrice = $itemPrice;

        return $this;
    }

    /**
     * Get itemPrice
     *
     * @return string 
     */
    public function getItemPrice()
    {
        return $this->itemPrice;
    }

    /**
     * Set quantity
     *
     * @param string $quantity
     * @return Item
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return string 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
    
    /**
     * Set packageHasCreditsId
     *
     * @param integer $packageHasCreditsId
     * @return Item
     */
    public function setPackageHasCredits($packageHasCredits)
    {
        $this->packageHasCredits = $packageHasCredits;

        return $this;
    }

    /**
     * Get packageHasCreditsId
     *
     * @return integer 
     */
    public function getPackageHasCredits()
    {
        return $this->packageHasCredits;
    }
}
