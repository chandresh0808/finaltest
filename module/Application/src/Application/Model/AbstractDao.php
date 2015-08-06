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

abstract class AbstractDao extends \Application\Model\AbstractCommonServiceMutator
{
                              
    /**
     *Persist and flush
     * 
     * @param Object $inputObject Description
     *  
     **/
    function persistFlush($inputObject)
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
     * Get a user object using paramters
     * 
     * @param List $paramList paramter list
     * 
     * @return User\Entity\User
     */
    public function getEntityByParameterList($paramList, $entity)
    {
        try {
            if (!empty($paramList)) {
                $paramList['deleteFlag'] = 0;
                $returnObject = $this->getEntityManager()
                    ->getRepository($entity)
                    ->findOneBy($paramList);
                return $returnObject;
            }
        } catch (\Exception $exc) {
           //@TODO: Need log this error
            throw new \Exception($exc);
        }
    }
    
    
     /**
     * Get a user object using paramters
     * 
     * @param List $paramList paramter list
     * 
     * @return User\Entity\User
     */
    public function getEntityListByParameterList($paramList, $entity)
    {
        try {
            if (!empty($paramList)) {               
                $returnObject = $this->getEntityManager()
                    ->getRepository($entity)
                    ->findBy($paramList);
                return $returnObject;
            }
        } catch (\Exception $exc) {
           //@TODO: Need log this error
            throw new \Exception($exc);
        }
    }
    
    
    
    /*
     * Search extracts 
     */
     public function isDefaultRuleBookAttributeExists($entityName, $userId, $field, $value, $defaultFieldName)
    {
         $query = "select a from {$entityName} a
            WHERE a.{$field}='".$value."' AND (a.userId = :userId OR a.{$defaultFieldName}) AND a.deleteFlag = 0";                  
        $queryResult = $this->getEntityManager()->createQuery($query);                      
        $queryResult->setParameter("userId", $userId);        
        $sql =  $queryResult->getSQL();        
        $analysisRequest = $queryResult->getArrayResult();                    
        return $analysisRequest;
        
    }
    
}
