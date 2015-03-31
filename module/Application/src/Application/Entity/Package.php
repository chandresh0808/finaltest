<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;

/**
 * Package
 *
 * @ORM\Table(name="package")
 * @ORM\Entity
 */
class Package extends AbstractEntity
{

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=45, nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="expiry_duration", type="string", length=45, nullable=false)
     */
    private $expiryDuration;

        
    /**
     * Set type
     *
     * @param string $type
     * @return Package
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
     * Set expiryDuration
     *
     * @param string $expiryDuration
     * @return Package
     */
    public function setExpiryDuration($expiryDuration)
    {
        $this->expiryDuration = $expiryDuration;

        return $this;
    }

    /**
     * Get expiryDuration
     *
     * @return string 
     */
    public function getExpiryDuration()
    {
        return $this->expiryDuration;
    }
    
}
