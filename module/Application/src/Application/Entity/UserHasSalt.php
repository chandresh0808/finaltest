<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;

/**
 * UserHasRole
 *
 * @ORM\Table(name="user_has_salt", uniqueConstraints={@ORM\UniqueConstraint(name="salt", columns={"salt"})})
 * @ORM\Entity
 */
class UserHasSalt extends AbstractEntity
{

    
    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=false)
     */
    private $salt;
           
    /**
     * Set salt
     *
     * @param string $userId
     * @return UserHasSalt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }
   
    
}
