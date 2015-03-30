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
     **/
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
     *Set the services in the arrayList
     * 
     * @param List $serviceList Names of the services 
     **/
    public function setServiceList($serviceList, $sm)
    {
        foreach ($serviceList as $serviceName) {
            $tempName = str_replace(' ', '', ucwords(str_replace('_', ' ', $serviceName)));
            $functionName ='set' . $tempName;
            $this->$functionName($sm->get($serviceName));
        }
        $test = 1;
    }
    
    /**
     * Log Service instance
     *
     * @var Log\Model\Log
     */
    protected $_logService;

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
    
    
    
  
   
}
