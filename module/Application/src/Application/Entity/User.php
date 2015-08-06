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
        $arrayCollection = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setRoleBookList($arrayCollection);
        $this->setUserHasPackageList($arrayCollection);
        $this->setAnalysisRequestList($arrayCollection);
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
     * @var string
     *
     * @ORM\Column(name="active_flag", type="boolean", nullable=false)
     * 
     */
    private $activeFlag;

    /**
     * @var string
     *
     * @ORM\Column(name="address_1", type="string", length=255, nullable=true)
     */
    private $address1;

    /**
     * @var string
     *
     * @ORM\Column(name="address_2", type="string", length=255, nullable=true)
     */
    private $address2;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=50, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=50, nullable=true)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=50, nullable=true)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="zipcode", type="string", length=15, nullable=true)
     */
    private $zipcode;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="parent_user_id", type="integer", length=32, nullable=true)
     */
    private $parentUserId;
    
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    private $lastLogin;
    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="is_blocked", type="integer", nullable=true)
     */
    private $isBlocked;
    
    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", nullable=true)
     */
    private $phoneNumber;

    
    
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
     * @param Object $entityObject Description
     * 
     * @return Object 
     * */
    public function filterExpiredPackage($entityObject)
    {
        $currentDtTm = new \DateTime("now");
        if (is_object($entityObject)) {
            $criteria = Criteria::create()
                    ->where(Criteria::expr()->eq("deleteFlag", 0))
                    ->where(Criteria::expr()->gte("packageExpiryDtTm", $currentDtTm))
                    ->orderBy(array('packageExpiryDtTm' => Criteria::ASC));
            $entityObjectWithFilter = $entityObject->matching($criteria);

            return $entityObjectWithFilter;
        }
    }

    /**
     * Set active
     *
     * @param int $activeFlag
     * @return User
     */
    public function setActiveFlag($activeFlag)
    {
        $this->activeFlag = $activeFlag;
        return $this;
    }

    /**
     * Get active
     *
     * @return string 
     */
    public function getActiveFlag()
    {
        return $this->activeFlag;
    }

    /**
     * Set address1
     *
     * @param string $address1
     * @return User
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;

        return $this;
    }

    /**
     * Get address1
     *
     * @return string 
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * Set address2
     *
     * @param string $address2
     * @return User
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * Get address2
     *
     * @return string 
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return User
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return User
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set zipcode
     *
     * @param string $zipcode
     * @return User
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get phone number
     *
     * @return string 
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }
    
    /**
     * Set phone number
     *
     * @param string $phoneNumber
     * @return User
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return string 
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }
    

    /**
     * Collection for Rule book list 
     * 
     * @var \Application\Entity\AnalysisRequest Description
     *
     * @param \Application\Entity\AnalysisRequest Analysis List
     * @ORM\OneToMany(targetEntity="AnalysisRequest", mappedBy="userId")
     */
    private $analysisRequestList;

    public function setAnalysisRequestList($arrayCollection)
    {
        $this->analysisRequestList = $arrayCollection;
        return $this;
    }

    public function getAnalysisRequestList()
    {
        return $this->setConditionActiveFlag($this->analysisRequestList);
    }


    public function setParentUserId($parentUserId)
    {
        $this->parentUserId = $parentUserId;

        return $this;
    }


    public function getParentUserId()
    {
        return $this->parentUserId;
    }
    
    
    public function getLastLogin()
    {
        return $this->lastLogin;
    }
    
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;
        
        return $this;
    }
    
    
    public function getIsBlocked()
    {
        return $this->isBlocked;
    }
    
    public function setIsBlocked($isBlocked)
    {
        $this->isBlocked = $isBlocked;
        
        return $this;
    }    


}
