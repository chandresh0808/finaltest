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

    public function createUpdateEntity($dataList)
    {
        $entityObject = $this->exchangeArray($dataList);
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
    
}
