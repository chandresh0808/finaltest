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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    protected $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=45, nullable=true)
     */
    private $type;
      
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
    
}
