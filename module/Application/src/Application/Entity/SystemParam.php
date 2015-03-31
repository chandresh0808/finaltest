<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;

/**
 * SystemParam
 *
 * @ORM\Table(name="system_param")
 * @ORM\Entity
 */
class SystemParam extends AbstractEntity
{

    /**
     * @var string
     *
     * @ORM\Column(name="param_key", type="string", length=45, nullable=true)
     */
    private $paramKey;

    /**
     * @var string
     *
     * @ORM\Column(name="param_value", type="string", length=45, nullable=true)
     */
    private $paramValue;

    
    /**
     * Set paramKey
     *
     * @param string $paramKey
     * @return SystemParam
     */
    public function setParamKey($paramKey)
    {
        $this->paramKey = $paramKey;

        return $this;
    }

    /**
     * Get paramKey
     *
     * @return string 
     */
    public function getParamKey()
    {
        return $this->paramKey;
    }

    /**
     * Set paramValue
     *
     * @param string $paramValue
     * @return SystemParam
     */
    public function setParamValue($paramValue)
    {
        $this->paramValue = $paramValue;

        return $this;
    }

    /**
     * Get paramValue
     *
     * @return string 
     */
    public function getParamValue()
    {
        return $this->paramValue;
    }
    
}
