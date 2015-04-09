<?php

/**
* 
*
* PHP version 5
*
* @category   Module
* @package    Application
* @subpackage Entity
* @author     Costrategix Team <team@costrategix.com>
* @copyright  2015 CoS
* @license    http://www.costrategix.com 
* @version    GIT: 1.7
* @link      http://www.costrategix.com
*
*/
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;


/**
* 
* Contains the common features across all Entities
* Ensure the Entity class has the follwoing annotations & call the constructor:
*
* @category   Module
* @package    Application
* @subpackage Entity
* @author     Costrategix Team <team@costrategix.com>
* @copyright  2015 CoS
* @license    http://www.costrategix.com 
* @version    GIT: 1.7
* @link      http://www.costrategix.com
* @ExclusionPolicy("all")
*/

abstract class AbstractEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Expose
     */
    protected $id;
    
     /**
     * @var boolean
     *
     * @ORM\Column(name="delete_flag", type="boolean", nullable=false)
     */
    protected $deleteFlag = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_dt_tm", type="datetime", nullable=true)
     */
    protected $createdDtTm;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_dt_tm", type="datetime", nullable=true)
     */
    protected $updatedDtTm;
    
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    
    /**
     * Set deleteFlag
     *
     * @param boolean $deleteFlag
     * @return Role
     */
    public function setDeleteFlag($deleteFlag)
    {
        $this->deleteFlag = $deleteFlag;

        return $this;
    }

    /**
     * Get deleteFlag
     *
     * @return boolean 
     */
    public function getDeleteFlag()
    {
        return $this->deleteFlag;
    }

    /**
     * Set createdDtTm
     *
     * @param \DateTime $createdDtTm
     * @return Role
     */
    public function setCreatedDtTm($createdDtTm)
    {
        $this->createdDtTm = $createdDtTm;

        return $this;
    }

    /**
     * Get createdDtTm
     *
     * @return \DateTime 
     */
    public function getCreatedDtTm()
    {
        return $this->createdDtTm;
    }

    /**
     * Set updatedDtTm
     *
     * @param \DateTime $updatedDtTm
     * @return Role
     */
    public function setUpdatedDtTm($updatedDtTm)
    {
        $this->updatedDtTm = $updatedDtTm;

        return $this;
    }

    /**
     * Get updatedDtTm
     *
     * @return \DateTime 
     */
    public function getUpdatedDtTm()
    {
        return $this->updatedDtTm;
    }
    
    
    /**
     * Set name
     *
     * @param string $name
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    
    
    /**
     * Set the condition for deleteFlag
     * 
     *@param Object $entityObject Description
     * 
     *@return Object 
     **/
    public function setConditionActiveFlag($entityObject)
    {
        if (is_object($entityObject)) {
            $criteria = Criteria::create()
                ->where(Criteria::expr()->eq("deleteFlag", 0));
            $entityObjectWithFilter = $entityObject->matching($criteria);

            return $entityObjectWithFilter;
        }

    }
    
    
}
