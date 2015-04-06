<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;

/**
 * PackageHasCredits
 *
 * @ORM\Table(name="package_has_credits", uniqueConstraints={@ORM\UniqueConstraint(name="package_id", columns={"package_id"})})
 * @ORM\Entity
 */
class PackageHasCredits extends AbstractEntity
{
   
    /**
     * @var integer
     *
     * @ORM\Column(name="package_id", type="integer", nullable=false)
     */
    private $packageId;

    /**
     * @var string
     *
     * @ORM\Column(name="package_duration", type="string", length=255, nullable=false)
     */
    private $packageDuration;

    /**
     * @var string
     *
     * @ORM\Column(name="total_credits", type="string", length=255, nullable=false)
     */
    private $totalCredits;

    /**
     * @var string
     *
     * @ORM\Column(name="package_amount", type="string", length=255, nullable=false)
     */
    private $packageAmount;

    
    /**
     * Set packageId
     *
     * @param integer $packageId
     * @return PackageHasCredits
     */
    public function setPackageId($packageId)
    {
        $this->packageId = $packageId;

        return $this;
    }

    /**
     * Get packageId
     *
     * @return integer 
     */
    public function getPackageId()
    {
        return $this->packageId;
    }

    /**
     * Set packageDuration
     *
     * @param string $packageDuration
     * @return PackageHasCredits
     */
    public function setPackageDuration($packageDuration)
    {
        $this->packageDuration = $packageDuration;

        return $this;
    }

    /**
     * Get packageDuration
     *
     * @return string 
     */
    public function getPackageDuration()
    {
        return $this->packageDuration;
    }

    /**
     * Set totalCredits
     *
     * @param string $totalCredits
     * @return PackageHasCredits
     */
    public function setTotalCredits($totalCredits)
    {
        $this->totalCredits = $totalCredits;

        return $this;
    }

    /**
     * Get totalCredits
     *
     * @return string 
     */
    public function getTotalCredits()
    {
        return $this->totalCredits;
    }

    /**
     * Set packageAmount
     *
     * @param string $packageAmount
     * @return PackageHasCredits
     */
    public function setPackageAmount($packageAmount)
    {
        $this->packageAmount = $packageAmount;

        return $this;
    }

    /**
     * Get packageAmount
     *
     * @return string 
     */
    public function getPackageAmount()
    {
        return $this->packageAmount;
    }
    
}
