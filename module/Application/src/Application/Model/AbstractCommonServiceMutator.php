<?php

/**
 * Abstract class which will be inheritted by DOAs.
 * Contains common properties & functions used by DAOs
 * 
 * PHP version 5
 * 
 * @category   Module
 * @package    Application
 * @subpackage Model
 * @author     Costrategix Team <team@costrategix.com>
 * @copyright  2015 CoS
 * @license    http://www.costrategix.com Proprietary 
 * @version    GIT: 1.7
 * @link       http://www.costrategix.com 
 * 
 */

namespace Application\Model;

use Doctrine\Common\Collections\Criteria;
use Zend\Session\Container as SessionContainer;

/**
 * Abstract class which will be inheritted by DOAs.
 * Contains common properties & functions used by DAOs
 * 
 * PHP version 5
 * 
 * @category   Module
 * @package    Application
 * @subpackage Model
 * @author     Costrategix Team <team@costrategix.com>
 * @copyright  2015 CoS
 * @license    http://www.costrategix.com Proprietary 
 * @version    GIT: 1.7
 * @link       http://www.costrategix.com 
 * 
 */
abstract class AbstractCommonServiceMutator
{

    /**
     * Entity manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    protected $_entityManager;

    /**
     * Log Service instance
     *
     * @var Log\Model\Log
     */
    protected $_logService;

    /**
     * Api Manager Service instance
     *
     * @var Api\Model\ApiManager
     */
    protected $_apiManagerService;

    /**
     * Auth Manager Service instance
     *
     * @var Auth\Model\AuthManager
     */
    protected $_authManagerService;

    /**
     * Auth Dao Service instance
     *
     * @var Auth\Model\AuthManager
     */
    protected $_authDaoService;

    /**
     * Api Service instance
     *
     * @var Api\Model\Api
     */
    protected $_apiService;

    /**
     * userSessionDao Service instance
     *
     * @var Api\Model\Api
     */
    protected $_userSessionDaoService;

    /**
     * userManagerService Service instance
     *
     * @var Api\Model\Api
     */
    protected $_userManagerService;

    /**
     * userManagerService Service instance
     *
     * @var Api\Model\Api
     */
    protected $_userHasSaltService;

    /**
     * userManagerService Service instance
     *
     * @var Api\Model\Api
     */
    protected $_s3BucketConfiguration;

    /**
     * userSessionDao Service instance
     *
     * @var Api\Model\Api
     */
    protected $_systemSaltDaoService;

    /**
     * Analysis request instance
     *
     * @var Api\Model\Api
     */
    protected $_analysisRequestService;
    
     /**
     * jms serializer Service instance
     *
     * @var Api\Model\Api
     */
    protected $_jmsSerializerService;
    
     /**
     * Analytics manager instance
     *
     * @var Api\Model\Api
     */
    protected $_analyticsManagerService;
    
    
    /**
     * Analytics manager instance
     *
     * @var Api\Model\Api
     */
    protected $_userDaoService;
    
    /**
     * Analytics manager instance
     *
     * @var Api\Model\Api
     */
    protected $_userService;
    
    /**
     * Mail template service instance
     *
     * @var Api\Model\Api
     */
    protected $_mailTemplateService;
    
    /*
     * Mail service
     */
    protected $_mailService;
    
    /**
     * userSessionDao Service instance
     *
     * @var Api\Model\Api
     */
    protected $_roleDaoService;
    
    /**
     * userManagerService Service instance
     *
     * @var Api\Model\Api
     */
    protected $_userHasRoleService;
    
    /*
     * authentication service instance
     */
    private $authService;
    
    
    /*
     * Authenticatio adapter instance 
     */
    private $authenticationAdapter;
    
    
    /*
     * Authenticatio adapter instance 
     */
    private $authenticationService;
    
    /*
     * Package Dao service
     */
    private $packageDaoService;
    
    /*
     * Package Manager service
     */
    private $packageManagerService;
    
    /*
     * Cart Dao service
     */
    private $cartDaoService;
    
    /*
     * Item Dao service
     */
    private $itemDaoService;
    
    /**
     * userManagerService Service instance
     *
     * @var Api\Model\Api
     */
    protected $_paymentManagerService;
       
    /**
     * orderDaoService Service instance
     *
     * @var Api\Model\Api
     */
    protected $_orderDaoService;
        
    /**
     * orderDaoService Service instance
     *
     * @var Api\Model\Api
     */
    protected $_orderDetailsDaoService;
    
    /**
     * orderDaoService Service instance
     *
     * @var Api\Model\Api
     */
    protected $_paymentMethodDaoService;
    
    /**
     * orderDaoService Service instance
     *
     * @var Api\Model\Api
     */
    protected $_paymentService;
    
    
    /**
     * orderDaoService Service instance
     *
     * @var Api\Model\Api
     */
    protected $_paymentHistoryDaoService;
    
    
        /**
     * orderDaoService Service instance
     *
     * @var Api\Model\Api
     */
    protected $_userHasPackageDaoService;
    
    
    /**
     * orderDaoService Service instance
     *
     * @var Api\Model\Api
     */
    protected $_userCreditHistoryDaoService;
    
    /**
     * System param service
     *
     * @var Application\Model\SystemParam
     */
    protected $_systemParamService;
    
    
    /**
     * System param service
     *
     * @var Application\Model\SystemParamDao
     */
    protected $_systemParamDaoService;
    
    /**
     * ruleBookManagerService Service instance
     *
     * @var RuleBook\Model\RuleBookManager
     */
    protected $_ruleBookManagerSevice;
    
    /**
     * ruleBookDaoService Service instance
     *
     * @var RuleBook\Model\RuleBookDao
     */
    protected $_ruleBookDaoService;
    
    /**
     * rulebookHasRiskDaoService Service instance
     *
     * @var RuleBook\Model\RulebookHasRiskDao
     */
    protected $_rulebookHasRiskDaoService;

    /**
     * ruleBookDaoService Service instance
     *
     * @var RuleBook\Model\RuleBookDao
     */
    protected $_excelService;
    
    /**
     * ruleBookDaoService Service instance
     *
     * @var RuleBook\Model\RuleBookDao
     */

    protected $_riskDaoService;
    
    /**
     * ruleBookDaoService Service instance
     *
     * @var RuleBook\Model\RuleBookDao
     */
    protected $_jobFunctionDaoService;
    



    /**
     * ruleBookDaoService Service instance
     *
     * @var RuleBook\Model\RiskHasJobFunctionDao
     */
    protected $_riskHasJobFunctionDaoService;
    
    
    /**
     * ruleBookDaoService Service instance
     *
     * @var RuleBook\Model\RiskHasJobFunctionDao
     */
    protected $_transactionsDaoService;
    
    
    /**
     * ruleBookDaoService Service instance
     *
     * @var RuleBook\Model\RiskHasJobFunctionDao
     */
    protected $_jobFunctionHasTransaction;
    
    
    /**
     * ruleBookDaoService Service instance
     *
     * @var RuleBook\Model\RiskHasJobFunctionDao
     */
    protected $_notificationLogDao;
    
    
    /**
     * ruleBookDaoService Service instance
     *
     * @var RuleBook\Model\RiskHasJobFunctionDao
     */
    protected $_extractsLogDao;
    
    
    /**
     * ruleBookDaoService Service instance
     *
     * @var RuleBook\Model\RiskHasJobFunctionDao
     */
    protected $_awsService;    
    
    /**
     * ruleBookDaoService Service instance
     *
     * @var RuleBook\Model\RiskHasJobFunctionDao
     */
    protected $_packageHasCredits;
    
    
    /**
     * ruleBookDaoService Service instance
     *
     * @var RuleBook\Model\RiskHasJobFunctionDao
     */
    protected $_activityDao;
    
    /**
     * ruleBookDaoService Service instance
     *
     * @var RuleBook\Model\RiskHasJobFunctionDao
     */
    protected $_systemActivityDao;
    
    /**
     * ruleBookDaoService Service instance
     *
     * @var RuleBook\Model\RiskHasJobFunctionDao
     */
    protected $_applicationManagerService;
    
    
    /**
     * Set or Returns an instance of the Doctrine entity manager
     * 
     * @param Doctrine\ORM\EntityManager $entityManager EntityManager
     * 
     * @return Doctrine\ORM\EntityManager $entityManager EntityManager
     *  
     */
    public function setEntityManager($entityManager)
    {
        if (!isset($this->_entityManager)) {
            $this->_entityManager = $entityManager;
        }
        return $this->_entityManager;
    }

    /**
     * Get instance of the Doctrine entity manager
     * 
     * @return Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->_entityManager;
    }

    /**
     * Persist and flush
     * 
     * @param Object $inputObject Description
     *  
     * @return void
     * 
     * */
    function save($inputObject)
    {
        if (is_object($inputObject)) {

            $this->getEntityManager()->persist($inputObject);
            $this->getEntityManager()->flush();
            return $inputObject;
        } else {
            throw new \Exception('Its not an object');
        }
    }

    /**
     * Set the services in the arrayList
     * 
     * @param List $serviceList Names of the services 
     * */
    public function setServiceList($serviceList, $sm)
    {
        foreach ($serviceList as $serviceName) {
            $tempName = str_replace(' ', '', ucwords(str_replace('_', ' ', $serviceName)));
            $functionName = 'set' . $tempName;
            $this->$functionName($sm->get($serviceName));
        }
    }

    /**
     * Get Log service instance
     *
     * @return Log\Model\Log
     */
    public function getLogService()
    {
        return $this->_logService;
    }

    /**
     * Set Log service instance
     *
     * @param \Zend\Log $logService LogService
     *
     * @return currentObject
     */
    public function setLogService($logService)
    {
        $this->_logService = $logService;
        return $this;
    }

    /**
     * Get Api Manager Service instance
     *
     * @return Api\Model\ApiManager
     */
    public function getApiManagerService()
    {
        return $this->_apiManagerService;
    }

    /**
     * Set Api Manager Service instance
     *
     * @param Api\Model\ApiManager $apiManagerService 
     *
     * @return currentObject
     */
    public function setApiManagerService($apiManagerService)
    {
        $this->_apiManagerService = $apiManagerService;
        return $this;
    }

    /**
     * Get Api Manager Service instance
     *
     * @return Auth\Model\AuthManager
     */
    public function getAuthManagerService()
    {
        return $this->_authManagerService;
    }

    /**
     * Set Api Manager Service instance
     *
     * @param Auth\Model\AuthManager $authManagerService 
     *
     * @return currentObject
     */
    public function setAuthManagerService($authManagerService)
    {
        $this->_authManagerService = $authManagerService;
        return $this;
    }

    /**
     * Get Api Manager Service instance
     *
     * @return Auth\Model\AuthManager
     */
    public function getAuthDaoService()
    {
        return $this->_authDaoService;
    }

    /**
     * Set Api Manager Service instance
     *
     * @param Auth\Model\AuthManager $authDaoService 
     *
     * @return currentObject
     */
    public function setAuthDaoService($authDaoService)
    {
        $this->_authDaoService = $authDaoService;
        return $this;
    }

    /**
     * Get Api Manager Service instance
     *
     * @return Auth\Model\AuthManager
     */
    public function getApiService()
    {
        return $this->_apiService;
    }

    /**
     * Set Api Manager Service instance
     *
     * @param Api\Model\Api $apiService 
     *
     * @return currentObject
     */
    public function setApiService($apiService)
    {
        $this->_apiService = $apiService;
        return $this;
    }

    /**
     * Get Api Manager Service instance
     *
     * @return Auth\Model\AuthManager
     */
    public function getUserSessionDaoService()
    {
        return $this->_userSessionDaoService;
    }

    /**
     * Set Api Manager Service instance
     *
     * @param Api\Model\Api $userSessionDaoService 
     *
     * @return currentObject
     */
    public function setUserSessionDaoService($userSessionDaoService)
    {
        $this->_userSessionDaoService = $userSessionDaoService;
        return $this;
    }

    /**
     * Get Api Manager Service instance
     *
     * @return Auth\Model\AuthManager
     */
    public function getUserManagerService()
    {
        return $this->_userManagerService;
    }

    /**
     * Set Api Manager Service instance
     *
     * @param Api\Model\Api $userManagerService 
     *
     * @return currentObject
     */
    public function setUserManagerService($userManagerService)
    {
        $this->_userManagerService = $userManagerService;
        return $this;
    }

    /**
     * Get Api Manager Service instance
     *
     * @return Auth\Model\AuthManager
     */
    public function getUserHasSaltDaoService()
    {
        return $this->_userHasSaltService;
    }

    /**
     * Set Api Manager Service instance
     *
     * @param Api\Model\Api $userHasSaltService 
     *
     * @return currentObject
     */
    public function setUserHasSaltDaoService($userHasSaltService)
    {
        $this->_userHasSaltService = $userHasSaltService;
        return $this;
    }

    /*
     * Create or unpdate the given entity
     * @param array $dataList
     * 
     * @return object $entityObject
     */

    public function createUpdateEntity($dataList, $dataObject = null)
    {
        $entityObject = $this->exchangeArray($dataList, $dataObject);
        return $this->persistFlush($entityObject);
    }

    /**
     * Get s3BucketConfiguration
     *
     * @return array 
     */
    public function getS3BucketConfiguration()
    {
        return $this->_s3BucketConfiguration;
    }

    /**
     * Set s3BucketConfiguration
     *
     * @param array $s3BucketConfiguration 
     *
     * @return array
     */
    public function setS3BucketConfiguration($s3BucketConfiguration)
    {
        $this->_s3BucketConfiguration = $s3BucketConfiguration;
        return $this;
    }

    /**
     * Get Api Manager Service instance
     *
     * @return Auth\Model\AuthManager
     */
    public function getSystemSaltDaoService()
    {
        return $this->_systemSaltDaoService;
    }

    /**
     * Set Api Manager Service instance
     *
     * @param Api\Model\Api $systemSaltDaoService 
     *
     * @return currentObject
     */
    public function setSystemSaltDaoService($systemSaltDaoService)
    {
        $this->_systemSaltDaoService = $systemSaltDaoService;
        return $this;
    }

    /**
     * Get Jms serializer Service instance
     *
     * @return Auth\Model\AuthManager
     */
    public function getJmsSerializerService()
    {
        return $this->_jmsSerializerService;
    }

    /**
     * Set Jms serializer Service instance
     *
     * @param Api\Model\Api $jmsSerializerService 
     *
     * @return currentObject
     */
    public function setJmsSerializerService($jmsSerializerService)
    {
        $this->_jmsSerializerService = $jmsSerializerService;
        return $this;
    }

    /*
     * Convert object to array using jms serializer
     * @param object $inputObject
     * 
     * @return array $responseArray
     * 
     */

    protected function convertObjectToArrayUsingJmsSerializer($inputObject)
    {
        $serializer = $this->getJmsSerializerService();
        $serializedString = $serializer->serialize($inputObject, 'json');

        /* Decoding here coz jsonmodel whil encode again while returning */
        $serializedData = json_decode($serializedString);
        return $serializedData;
    }

    
    /**
     * Set Api Manager Service instance
     *
     * @param Api\Model\Api $analysisRequestDaoService 
     *
     * @return currentObject
     */
    protected function setAnalysisRequestDaoService($analysisRequestDaoService)
    {
        $this->_analysisRequestService = $analysisRequestDaoService;
        return $this;
    }

    /**
     * Get Jms serializer Service instance
     *
     * @return Auth\Model\AuthManager
     */
    protected function getAnalysisRequestDaoService()
    {
        return $this->_analysisRequestService;
    }
    
    /**
     * Set Api Manager Service instance
     *
     * @param Api\Model\Api $analyticsManagerService 
     *
     * @return currentObject
     */
    protected function setAnalyticsManagerService($analyticsManagerService)
    {
        $this->_analyticsManagerService = $analyticsManagerService;
        return $this;
    }

    /**
     * Get Jms serializer Service instance
     *
     * @return Auth\Model\AuthManager
     */
    protected function getAnalyticsManagerService()
    {
        return $this->_analyticsManagerService;
    }
    
    
    /**
     * Set Api Manager Service instance
     *
     * @param Api\Model\Api $userDaoService 
     *
     * @return currentObject
     */
    protected function setUserDaoService($userDaoService)
    {
        $this->_userDaoService = $userDaoService;
        return $this;
    }

    /**
     * Get Jms serializer Service instance
     *
     * @return Auth\Model\AuthManager
     */
    protected function getUserDaoService()
    {
        return $this->_userDaoService;
    }
    
    
    /**
     * Set Api Manager Service instance
     *
     * @param Api\Model\Api $userService 
     *
     * @return currentObject
     */
    protected function setUserService($userService)
    {
        $this->_userService = $userService;
        return $this;
    }

    /**
     * Get Jms serializer Service instance
     *
     * @return Auth\Model\AuthManager
     */
    protected function getUserService()
    {
        return $this->_userService;
    }
    
    /**
     * Get Template service instance
     * 
     * @return Mail\Model\Template
     */
    public function getMailTemplateService()
    {
        return $this->_mailTemplateService;
    }

    /**
     * set mail template service
     *
     * @param \Mail\Model\Template $mailTemplateService
     *
     * @return \Mail\Model\Template
     */
    public function setMailTemplateService($mailTemplateService)
    {
        $this->_mailTemplateService = $mailTemplateService;
        return $this;
    }
    
    /**
     * Get Mail service instance
     * 
     * @return \Mail\Model\Mail
     */
    public function getMailService()
    {
        return $this->_mailService;
    }
    
    /**
     * Set Mail service instance
     *
     * @param \Mail\Model\Mail $mailService
     *
     * @return \Mail\Model\Mail
     */
    public function setMailService($mailService)
    {
        $this->_mailService = $mailService;
        return $this;
    }
    
    
    /*
     * Gets authentication service
     */
    public function getAuthenticationService()
    {
        return $this->authenticationService;
    }

    /*
     * set authentication services
     */
    public function setAuthenticationService($authenticationService)
    {
        $this->authenticationService = $authenticationService;
          return $this;
    }
    
    /*
     * sets authentication adapter
     */
    public function setAuthenticationAdapterService($authenticationAdapter)
    {
        $this->authenticationAdapter = $authenticationAdapter;
          return $this;
    }
    
    /*
     * gets authentication adapter
     */
    public function getAuthenticationAdapterService()
    {
        return $this->authenticationAdapter;
    }
    
     /*
     * Gets authentication service
     */
    public function getAuthService()
    {
        return $this->authService;
    }

    /*
     * set authentication services
     */
    public function setAuthService($authService)
    {
        $this->authService = $authService;
          return $this;
    }
    
    /*
     * Gets packageDaoService service
     */
    public function getPackageDaoService()
    {
        return $this->packageDaoService;
    }

    /*
     * set authentication services
     */
    public function setPackageDaoService($packageDaoService)
    {
        $this->packageDaoService = $packageDaoService;
          return $this;
    }
    
    /*
     * Gets package Manager Service service
     */
    public function getPackageManagerService()
    {
        return $this->packageManagerService;
    }

    /*
     * set authentication services
     */
    public function setPackageManagerService($packageDaoService)
    {
        $this->packageManagerService = $packageDaoService;
          return $this;
    }
    
    /*
     * Gets package Manager Service service
     */
    public function getCartDaoService()
    {
        return $this->cartDaoService;
    }

    /*
     * set authentication services
     */
    public function setCartDaoService($cartDaoService)
    {
        $this->cartDaoService = $cartDaoService;
          return $this;
    }
    
    
    /*
     * Gets package Manager Service service
     */
    public function getItemDaoService()
    {
        return $this->itemDaoService;
    }

    /*
     * set authentication services
     */
    public function setItemDaoService($itemDaoService)
    {
        $this->itemDaoService = $itemDaoService;
          return $this;
    }    
    
     /**
     * Get Api Manager Service instance
     *
     * @return Auth\Model\AuthManager
     */
    public function getPaymentManagerService()
    {
        return $this->_paymentManagerService;
    }

    /**
     * Set Api Manager Service instance
     *
     * @param Api\Model\Api $paymentManagerService 
     *
     * @return currentObject
     */
    public function setPaymentManagerService($paymentManagerService)
    {
        $this->_paymentManagerService = $paymentManagerService;
        return $this;
    }
    
    
    
     /**
     * Get Api Manager Service instance
     *
     * @return Auth\Model\AuthManager
     */
    public function getOrderDaoService()
    {
        return $this->_orderDaoService;
    }

    /**
     * Set Api Manager Service instance
     *
     * @param Api\Model\Api $orderDaoService 
     *
     * @return currentObject
     */
    public function setOrderDaoService($orderDaoService)
    {
        $this->_orderDaoService = $orderDaoService;
        return $this;
    }
    
    
     /**
     * Get Api Manager Service instance
     *
     * @return Auth\Model\AuthManager
     */
    public function getOrderDetailsDaoService()
    {
        return $this->_orderDetailsDaoService;
    }

    /**
     * Set Api Manager Service instance
     *
     * @param Api\Model\Api $orderDetailsDaoService 
     *
     * @return currentObject
     */
    public function setOrderDetailsDaoService($orderDetailsDaoService)
    {
        $this->_orderDetailsDaoService = $orderDetailsDaoService;
        return $this;
    }
    
     /**
     * Get Api Manager Service instance
     *
     * @return Auth\Model\AuthManager
     */
    public function getPaymentMethodDaoService()
    {
        return $this->_paymentMethodDaoService;
    }

    /**
     * Set Api Manager Service instance
     *
     * @param Api\Model\Api $orderDetailsDaoService 
     *
     * @return currentObject
     */
    public function setPaymentMethodDaoService($paymentMethodDaoService)
    {
        $this->_paymentMethodDaoService = $paymentMethodDaoService;
        return $this;
    }
    
    
    /**
     * Get Api Manager Service instance
     *
     * @return Auth\Model\AuthManager
     */
    public function getPaymentService()
    {
        return $this->_paymentService;
    }

    /**
     * Set Api Manager Service instance
     *
     * @param Api\Model\Api $paymentService 
     *
     * @return currentObject
     */
    public function setPaymentService($paymentService)
    {
        $this->_paymentService = $paymentService;
        return $this;
    }
    
    
    /**
     * Get Api Manager Service instance
     *
     * @return Auth\Model\AuthManager
     */
    public function getPaymentHistoryDaoService()
    {
        return $this->_paymentHistoryDaoService;
    }

    /**
     * Set Api Manager Service instance
     *
     * @param Api\Model\Api $paymentService 
     *
     * @return currentObject
     */
    public function setPaymentHistoryDaoService($paymentHistoryService)
    {
        $this->_paymentHistoryDaoService = $paymentHistoryService;
        return $this;
    }
    

    /**
     * Get Api Manager Service instance
     *
     * @return Auth\Model\AuthManager
     */

    public function getUserHasPackageDaoService()
    {
        return $this->_userHasPackageDaoService;
    }
    
    public function getRoleDaoService()
    {
        return $this->_roleDaoService;

    }

    /**
     * Set Api Manager Service instance
     *

     * @param Api\Model\Api $userHasPackageDaoService 
     *
     * @return currentObject
     */
    public function setUserHasPackageDaoService($userHasPackageDaoService)
    {
        $this->_userHasPackageDaoService = $userHasPackageDaoService;
          return $this;
    }

    /*
     * @param Api\Model\Api $systemSaltDaoService 
     *
     * @return currentObject
     */
    public function setRoleDaoService($roleDaoService)
    {
        $this->_roleDaoService = $roleDaoService;

        return $this;
    }
    
    
    /**
     * Get Api Manager Service instance
     *
     * @return Auth\Model\AuthManager
     */

    public function getUserCreditHistoryDaoService()
    {
        return $this->_userCreditHistoryDaoService;

    }
    
    public function getUserHasRoleDaoService()
    {
        return $this->_userHasRoleService;

    }

    /**
     * Set Api Manager Service instance
     *

     * @param Api\Model\Api $userCreditHistoryDaoService 
     *
     * @return currentObject
     */
    public function setUserCreditHistoryDaoService($userCreditHistoryDaoService)
    {
        $this->_userCreditHistoryDaoService = $userCreditHistoryDaoService;
          return $this;
    }
    
    /*
     * @param Api\Model\Api $userHasSaltService 
     *
     * @return currentObject
     */
    public function setUserHasRoleDaoService($userHasRoleService)
    {
        $this->_userHasRoleService = $userHasRoleService;
        return $this;
    }
    /**
      * @param Application\Model\SystemParam $systemParamService
      *
      * @return currentObject
    */
    public function setSystemParamService($systemParamService)
    {
        $this->_systemParamService = $systemParamService;
    
        return $this;
    }
    
    /**
     * Get System param Service instance
     *
     * @return Application\Model\SystemParam
     */
    public function getSystemParamService()
    {
        return $this->_systemParamService;
    }
    
    /**
     * Get System param Service instance
     *
     * @return Application\Model\SystemParam
     */
    public function getSystemParamDaoService()
    {
        return $this->_systemParamDaoService;
    }
    
    /**
     * Set System param Service instance
     *
     * @param Application\Model\SystemParam $systemParamDaoService
     *
     * @return currentObject
     */
    public function setSystemParamDaoService($systemParamDaoService)
    {
        $this->_systemParamDaoService = $systemParamDaoService;
        return $this;
    }
    
    
    /**
     * Get Rulebook Manager Service instance
     *
     * @return Auth\Model\AuthManager
     */
    public function getRuleBookManagerService()
    {
        return $this->_ruleBookManagerService;
    }

    /**
     * Set Rulebook Manager Service instance
     *
     * @param Api\Model\Api $ruleBookManagerService 
     *
     * @return currentObject
     */
    public function setRuleBookManagerService($ruleBookManagerService)
    {
        $this->_ruleBookManagerService = $ruleBookManagerService;
        return $this;
    }
    
    
    /**
     * Get Rulebook Dao Service instance
     *
     * @return Auth\Model\AuthManager
     */
    public function getRuleBookDaoService()
    {
        return $this->_ruleBookDaoService;
    }

    /**
     * Set Rulebook Dao Service instance
     *
     * @param Api\Model\Api $ruleBookDaoService 
     *
     * @return currentObject
     */
    public function setRuleBookDaoService($ruleBookDaoService)
    {
        $this->_ruleBookDaoService = $ruleBookDaoService;
        return $this;
    }    

    /**
     * Get RulebookHasRisk Dao Service instance
     *
     * @return Auth\Model\AuthManager
     */
    public function getRulebookHasRiskDaoService()
    {
        return $this->_rulebookHasRiskDaoService;
    }

    /**
     * Set RulebookHasRisk Dao Service instance
     *
     * @param Api\Model\Api $rulebookHasRiskDaoService
     *
     * @return currentObject
     */
    public function setRulebookHasRiskDaoService($rulebookHasRiskDaoService)
    {
        $this->_rulebookHasRiskDaoService = $rulebookHasRiskDaoService;
        return $this;
    }   

    
     /**
     * Get Rulebook Dao Service instance
     *
     * @return Auth\Model\AuthManager
     */
    public function getExcelService()
    {
        return $this->_excelService;
    }

    /**
     * Set Rulebook Dao Service instance
     *
     * @param Api\Model\Api $ruleBookDaoService 
     *
     * @return currentObject
     */
    public function setExcelService($excelService)
    {
        $this->_excelService = $excelService;
        return $this;
    }
    
    /**
     * Get Rulebook Dao Service instance
     *
     * @return Auth\Model\AuthManager
     */
    public function getRiskDaoService()
    {
        return $this->_riskDaoService;
    }

    /**
     * Set Rulebook Dao Service instance
     *
     * @param Api\Model\Api $riskDaoService 
     *
     * @return currentObject
     */
    public function setRiskDaoService($riskDaoService)
    {
        $this->_riskDaoService = $riskDaoService;
        return $this;
    }  
    

    /**
     * Get Rulebook Dao Service instance
     *
     * @return Auth\Model\AuthManager
     */

    public function getRiskHasJobFunctionDaoService()
    {
        return $this->_riskHasJobFunctionDaoService;
    }
    
    
    /**
     * Set Rulebook Dao Service instance
     * @param Api\Model\Api $riskHasJobFunctionDaoService 
     *
     * @return currentObject
     */
    public function setRiskHasJobFunctionDaoService($riskHasJobFunctionDaoService)
    {
        $this->_riskHasJobFunctionDaoService = $riskHasJobFunctionDaoService;
        return $this;
    }   
    
    
    /**
     * Get Rulebook Dao Service instance
     *
     * @return Auth\Model\AuthManager
     */
    
    public function getJobFunctionDaoService()
    {
        return $this->_jobFunctionDaoService;
    }    

    /**
     * Set Rulebook Dao Service instance
     * @param Api\Model\Api $riskDaoService 
     *
     * @return currentObject
     */
    
    public function setJobFunctionDaoService($riskDaoService)
    {
        $this->_jobFunctionDaoService = $riskDaoService;
        return $this;
    }    
    
    
    /**
     * Get Rulebook Dao Service instance
     *
     * @return Auth\Model\AuthManager
     */
    
    public function getTransactionsDaoService()
    {
        return $this->_transactionsDaoService;
    }    

    /**
     * Set Rulebook Dao Service instance
     * @param Api\Model\Api $transactionsDaoService 
     *
     * @return currentObject
     */
    
    public function setTransactionsDaoService($transactionsDaoService)
    {
        $this->_transactionsDaoService = $transactionsDaoService;
        return $this;
    }    
    
    
    /**
     * Get Rulebook Dao Service instance
     *
     * @return Auth\Model\AuthManager
     */
    
    public function getJobFunctionHasTransactionsDaoService()
    {
        return $this->_jobFunctionHasTransaction;
    }    

    /**
     * Set Rulebook Dao Service instance
     * @param Api\Model\Api $jobFunctionHasTransaction 
     *
     * @return currentObject
     */
    
    public function setJobFunctionHasTransactionsDaoService($jobFunctionHasTransaction)
    {
        $this->_jobFunctionHasTransaction = $jobFunctionHasTransaction;
        return $this;
    }   
 
    
    /**
     * Get Rulebook Dao Service instance
     *
     * @return Auth\Model\AuthManager
     */
    
    public function getNotificationLogDaoService()
    {
        return $this->_notificationLogDao;
    }    

    /**
     * Set Rulebook Dao Service instance
     * @param Api\Model\Api $notificationLogDao
     *
     * @return currentObject
     */
    
    public function setNotificationLogDaoService($notificationLogDao)
    {
        $this->_notificationLogDao = $notificationLogDao;
        return $this;
    }   
    
    
    /**
     * Get Rulebook Dao Service instance
     *
     * @return Auth\Model\AuthManager
     */
    
    public function getExtractsDaoService()
    {
        return $this->_extractsLogDao;
    }    

    /**
     * Set Rulebook Dao Service instance
     * @param Api\Model\Api $extractsLogDao
     *
     * @return currentObject
     */
    
    public function setExtractsDaoService($extractsLogDao)
    {
        $this->_extractsLogDao = $extractsLogDao;
        return $this;
    }   
    
    
    /**
     * Get Rulebook Dao Service instance
     *
     * @return Auth\Model\AuthManager
     */
    
    public function getAwsService()
    {
        return $this->_awsService;
    }    

    /**
     * Set Rulebook Dao Service instance
     * @param Api\Model\Api $extractsLogDao
     *
     * @return currentObject
     */
    
    public function setAwsService($awsService)
    {
        $this->_awsService = $awsService;
        return $this;
    }   
    
    
    
    /**
     * Get Rulebook Dao Service instance
     *
     * @return Auth\Model\AuthManager
     */
    
    public function getPackageHasCreditsDaoService()
    {
        return $this->_packageHasCredits;
    }    

    /**
     * Set Rulebook Dao Service instance
     * @param Api\Model\Api $extractsLogDao
     *
     * @return currentObject
     */
    
    public function setPackageHasCreditsDaoService($packageHasCreditsService)
    {
        $this->_packageHasCredits = $packageHasCreditsService;
        return $this;
    }  
    
    
     /**
     * Get Rulebook Dao Service instance
     *
     * @return Auth\Model\AuthManager
     */
    
    public function getActivityDaoService()
    {
        return $this->_activityDao;
    }    

    /**
     * Set Rulebook Dao Service instance
     * @param Api\Model\Api $extractsLogDao
     *
     * @return currentObject
     */
    
    public function setActivityDaoService($activityDaoService)
    {
        $this->_activityDao = $activityDaoService;
        return $this;
    }  
   
    
    /**
     * Get Rulebook Dao Service instance
     *
     * @return Auth\Model\AuthManager
     */
    
    public function getSystemActivityDaoService()
    {
        return $this->_systemActivityDao;
    }    

    /**
     * Set Rulebook Dao Service instance
     * @param Api\Model\Api $extractsLogDao
     *
     * @return currentObject
     */
    
    public function setSystemActivityDaoService($systemActivityDaoService)
    {
        $this->_systemActivityDao = $systemActivityDaoService;
        return $this;
    }  
    
    
    /**
     * Get Rulebook Dao Service instance
     *
     * @return Auth\Model\AuthManager
     */
    
    public function getApplicationManagerService()
    {
        return $this->_applicationManagerService;
    }    

    /**
     * Set Rulebook Dao Service instance
     * @param Api\Model\Api $extractsLogDao
     *
     * @return currentObject
     */
    
    public function setApplicationManagerService($applicationManagerService)
    {
        $this->_applicationManagerService = $applicationManagerService;
        return $this;
    }  
    
}
