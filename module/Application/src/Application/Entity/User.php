<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AbstractEntity as AbstractEntity;
use Doctrine\Common\Collections\Criteria;
/**
 * User
 *
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="username_UNIQUE", columns={"username"})})
 * @ORM\Entity
 */
class User extends AbstractEntity
{

    
    function __construct()
    {
        $arrayCollection =   new \Doctrine\Common\Collections\ArrayCollection();
        $this->setRoleBookList($arrayCollection);
        $this->setUserHasPackageList($arrayCollection);
    }
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=50, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=50, nullable=true)
     */
    private $lastName;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="free_trial_flag", type="boolean", nullable=false)
     */
    private $freeTrialFlag = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="active_package_flag", type="boolean", nullable=false)
     */
    private $activePackageFlag = '0';

    
    /**
     * @var string
     *
     * @ORM\Column(name="activation_code", type="string", length=50, nullable=true)
     */
    private $activationCode;

    
    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }
   
    
     /**
     * Set freeTrialFlag
     *
     * @param boolean $freeTrialFlag
     * @return User
     */
    public function setFreeTrialFlag($freeTrialFlag)
    {
        $this->freeTrialFlag = $freeTrialFlag;

        return $this;
    }

    /**
     * Get freeTrialFlag
     *
     * @return boolean 
     */
    public function getFreeTrialFlag()
    {
        return $this->freeTrialFlag;
    }

    /**
     * Set activePackageFlag
     *
     * @param boolean $activePackageFlag
     * @return User
     */
    public function setActivePackageFlag($activePackageFlag)
    {
        $this->activePackageFlag = $activePackageFlag;

        return $this;
    }

    /**
     * Get activePackageFlag
     *
     * @return boolean 
     */
    public function getActivePackageFlag()
    {
        return $this->activePackageFlag;
    }
    
    /**
     * Set activationCode
     *
     * @param string $activationCode
     * @return User
     */
    public function setActivationCode($activationCode)
    {
        $this->activationCode = $activationCode;

        return $this;
    }

    /**
     * Get activationCode
     *
     * @return string 
     */
    public function getActivationCode()
    {
        return $this->activationCode;
    }
   
    
    /**
     * Collection for Rule book list 
     * 
     * @var \Application\Entity\RuleBook Description
     *
     * @param \Application\Entity\RuleBook Rule Book List
     * @ORM\OneToMany(targetEntity="Rulebook", mappedBy="userId")
     */
    private $ruleBookList;

    public function setRoleBookList($arrayCollection)
    {
        $this->ruleBookList = $arrayCollection;
        return $this;
    }

    public function getRoleBookList()
    {
        return $this->setConditionActiveFlag($this->ruleBookList);
    }
    
    /**
     * Collection for User has package
     * 
     * @var \Application\Entity\UserHasPackage Description
     *
     * @param \Application\Entity\UserHasPackage Rule Book List
     * @ORM\OneToMany(targetEntity="UserHasPackage", mappedBy="userId")
     */
    private $userHasPackageList;

    public function setUserHasPackageList($arrayCollection)
    {
        $this->userHasPackageList = $arrayCollection;
        return $this;
    }

    public function getUserHasPackageList()
    {
        return $this->filterExpiredPackage($this->userHasPackageList);
    }
    
    /**
     * Set the condition for deleteFlag
     * 
     *@param Object $entityObject Description
     * 
     *@return Object 
     **/
    public function filterExpiredPackage($entityObject)
    {                
        $currentDtTm = new \DateTime("now");
        if (is_object($entityObject)) {
            $criteria = Criteria::create()
                ->where(Criteria::expr()->eq("deleteFlag", 0))
                ->where(Criteria::expr()->lte("packageExpiryDtTm", $currentDtTm));                    
            $entityObjectWithFilter = $entityObject->matching($criteria);

            return $entityObjectWithFilter;
        }

    }
    
}
