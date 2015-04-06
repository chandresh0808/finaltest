<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;

/**
 * SystemSalt
 *
 * @ORM\Table(name="system_salt", uniqueConstraints={@ORM\UniqueConstraint(name="salt", columns={"salt"})})
 * @ORM\Entity
 */
class SystemSalt extends AbstractEntity
{

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", nullable=false)
     */
    private $type = 'auth';

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=false)
     */
    private $salt;
    

    /**
     * Set type
     *
     * @param string $type
     * @return SystemSalt
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return SystemSalt
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
