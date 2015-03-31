<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;

/**
 * UserHasRole
 *
 * @ORM\Table(name="user_has_role", uniqueConstraints={@ORM\UniqueConstraint(name="user_id_UNIQUE", columns={"user_id"})})
 * @ORM\Entity
 */
class UserHasRole extends AbstractEntity
{

    /**
     * @var string
     *
     * @ORM\Column(name="user_id", type="string", length=45, nullable=false)
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="role_id", type="string", length=45, nullable=false)
     */
    private $roleId;
    

    /**
     * Set userId
     *
     * @param string $userId
     * @return UserHasRole
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
     * Set roleId
     *
     * @param string $roleId
     * @return UserHasRole
     */
    public function setRoleId($roleId)
    {
        $this->roleId = $roleId;

        return $this;
    }

    /**
     * Get roleId
     *
     * @return string 
     */
    public function getRoleId()
    {
        return $this->roleId;
    }
    
}
