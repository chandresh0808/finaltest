<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * States
 *
 * @ORM\Table(name="states")
 * @ORM\Entity
 */
class States 
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * 
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="state_cd", type="string", length=2, nullable=false)
     */
    private $stateCd;

    /**
     * @var string
     *
     * @ORM\Column(name="state_name", type="string", length=45, nullable=false)
     */
    private $stateName;

    /**
     * @var integer
     *
     * @ORM\Column(name="country_id", type="integer", nullable=false)
     */
    private $countryId;


    /**
     * Set stateCd
     *
     * @param string $stateCd
     * @return States
     */
    public function setStateCd($stateCd)
    {
        $this->stateCd = $stateCd;

        return $this;
    }

    /**
     * Get stateCd
     *
     * @return string 
     */
    public function getStateCd()
    {
        return $this->stateCd;
    }

    /**
     * Set stateName
     *
     * @param string $stateName
     * @return States
     */
    public function setStateName($stateName)
    {
        $this->stateName = $stateName;

        return $this;
    }

    /**
     * Get stateName
     *
     * @return string 
     */
    public function getStateName()
    {
        return $this->stateName;
    }

    /**
     * Set countryId
     *
     * @param integer $countryId
     * @return States
     */
    public function setCountryId($countryId)
    {
        $this->countryId = $countryId;

        return $this;
    }

    /**
     * Get countryId
     *
     * @return integer 
     */
    public function getCountryId()
    {
        return $this->countryId;
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
