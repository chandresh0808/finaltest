<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;

/**
 * UserHasPackage
 *
 * @ORM\Table(name="user_has_package")
 * @ORM\Entity
 */
class UserHasPackage extends AbstractEntity
{

    
    function __construct()
    {
        $arrayCollection =   new \Doctrine\Common\Collections\ArrayCollection();
        $this->setUserCreditHistory($arrayCollection);
    }
    
    /**
     * @var string
     *
     * @ORM\Column(name="user_id", type="string", length=45, nullable=true)
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="package_id", type="integer", nullable=false)
     */
    private $packageId;

     /**
     * @var integer
     *
     * @ORM\Column(name="package_has_credits_id", type="integer", nullable=false)
     */
    private $packageHasCreditsId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="package_effective_dt_tm", type="datetime", nullable=false)
     */
    private $packageEffectiveDtTm;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="package_expiry_dt_tm", type="datetime", nullable=false)
     */
    private $packageExpiryDtTm;
    
    /**
     * Set userId
     *
     * @param string $userId
     * @return UserHasPackage
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return string 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set packageId
     *
     * @param integer $packageId
     * @return UserHasPackage
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
     * Set packageHasCreditsId
     *
     * @param integer $packageHasCreditsId
     * @return UserHasPackage
     */
    public function setPackageHasCreditsId($packageHasCreditsId)
    {
        $this->packageHasCreditsId = $packageHasCreditsId;

        return $this;
    }

    /**
     * Get packageHasCreditsId
     *
     * @return integer 
     */
    public function getPackageHasCreditsId()
    {
        return $this->packageHasCreditsId;
    }

    /**
     * Set packageEffectiveDtTm
     *
     * @param \DateTime $packageEffectiveDtTm
     * @return UserHasPackage
     */
    public function setPackageEffectiveDtTm($packageEffectiveDtTm)
    {
        $this->packageEffectiveDtTm = $packageEffectiveDtTm;

        return $this;
    }

    /**
     * Get packageEffectiveDtTm
     *
     * @return \DateTime 
     */
    public function getPackageEffectiveDtTm()
    {
        return $this->packageEffectiveDtTm;
    }

    /**
     * Set packageExpiryDtTm
     *
     * @param \DateTime $packageExpiryDtTm
     * @return UserHasPackage
     */
    public function setPackageExpiryDtTm($packageExpiryDtTm)
    {
        $this->packageExpiryDtTm = $packageExpiryDtTm;

        return $this;
    }

    /**
     * Get packageExpiryDtTm
     *
     * @return \DateTime 
     */
    public function getPackageExpiryDtTm()
    {
        return $this->packageExpiryDtTm;
    }
   
    
    /**
     * Collection for User has package
     * 
     * @var \Application\Entity\UserHasPackage Description
     *
     * @param \Application\Entity\UserHasPackage Rule Book List
     * @ORM\OneToMany(targetEntity="UserAnalysisCreditHistory", mappedBy="userHasPackageId")
     */
    private $userCreditHistory;

    public function setUserCreditHistory($arrayCollection)
    {
        $this->userCreditHistory = $arrayCollection;
        return $this;
    }

    public function getUserCreditHistory()
    {
        return $this->setConditionActiveFlag($this->userCreditHistory);
    }
    
}
