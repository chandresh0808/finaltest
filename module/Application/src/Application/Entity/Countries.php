<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;

/**
 * Countries
 *
 * @ORM\Table(name="countries")
 * @ORM\Entity
 */
class Countries extends AbstractEntity
{

    /**
     * @var string
     *
     * @ORM\Column(name="country_name", type="string", length=65, nullable=true)
     */
    private $countryName;

    /**
     * @var string
     *
     * @ORM\Column(name="country_iso_code2", type="string", length=2, nullable=true)
     */
    private $countryIsoCode2;

    /**
     * @var string
     *
     * @ORM\Column(name="country_iso_code3", type="string", length=3, nullable=true)
     */
    private $countryIsoCode3;

  
    /**
     * Set countryName
     *
     * @param string $countryName
     * @return Countries
     */
    public function setCountryName($countryName)
    {
        $this->countryName = $countryName;

        return $this;
    }

    /**
     * Get countryName
     *
     * @return string 
     */
    public function getCountryName()
    {
        return $this->countryName;
    }

    /**
     * Set countryIsoCode2
     *
     * @param string $countryIsoCode2
     * @return Countries
     */
    public function setCountryIsoCode2($countryIsoCode2)
    {
        $this->countryIsoCode2 = $countryIsoCode2;

        return $this;
    }

    /**
     * Get countryIsoCode2
     *
     * @return string 
     */
    public function getCountryIsoCode2()
    {
        return $this->countryIsoCode2;
    }

    /**
     * Set countryIsoCode3
     *
     * @param string $countryIsoCode3
     * @return Countries
     */
    public function setCountryIsoCode3($countryIsoCode3)
    {
        $this->countryIsoCode3 = $countryIsoCode3;

        return $this;
    }

    /**
     * Get countryIsoCode3
     *
     * @return string 
     */
    public function getCountryIsoCode3()
    {
        return $this->countryIsoCode3;
    }
}
