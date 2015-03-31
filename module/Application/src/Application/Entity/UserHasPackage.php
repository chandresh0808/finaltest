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
   
}
