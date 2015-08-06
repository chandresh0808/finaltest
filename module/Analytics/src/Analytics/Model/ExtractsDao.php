<?php

/**
 * DAO class
 * 
 * PHP version 5
 * 
 * @category   Module
 * @package    Api
 * @subpackage Model
 * @author     Costrategix Team <team@costrategix.com>
 * @copyright  2015 CoS
 * @license    http://www.costrategix.com Proprietary 
 * @version    GIT: 1.7
 * @link       http://www.costrategix.com 
 * 
 */

namespace Analytics\Model;
use Doctrine\Common\Collections\Criteria as Criteria;
use Application\Model\Constant as Constant;
/**
 * Define a interface between CMSApiController and other modules
 * 
 * PHP version 5
 * 
 * @category   Module
 * @package    Api
 * @subpackage Model
 * @author     Costrategix Team <team@costrategix.com>
 * @copyright  2015 CoS
 * @license    http://www.costrategix.com Proprietary 
 * @version    GIT: 1.7
 * @link       http://www.costrategix.com 
 * 
 */

class ExtractsDao extends \Application\Model\AbstractDao
{
  
    /**
     * Populate a UserSession Object 
     * Exchange/populate UserSession Array
     * 
     * @param List $paramList ParameterList
     * 
     * @return object userSession
     */
    public function exchangeArray($postDataArray, $extractsObject = null)
    {

        if ($extractsObject) {
            $extractsObject = \Application\Model\Utility::setDateTimeForUpdation($extractsObject);
        } else {
            $extractsObject = new \Application\Entity\Extracts();
            $extractsObject = \Application\Model\Utility::setDateTimeForCreation($extractsObject);
        }
        
        $extractsObject->setUserId($postDataArray['user_id']);
        $extractsObject->setParentUserId($postDataArray['parent_user_id']);
        $extractsObject->setExtractName($postDataArray['extract_name']);
        $extractsObject->setExtractFileName($postDataArray['extract_file_name']);
        $extractsObject->setStatus($postDataArray['status']);
        $extractsObject->setSystemSaltId($postDataArray['system_salt']);  
        $extractsObject->setJobId($postDataArray['job_id']);           
        
        return $extractsObject;
    }           
    
    public function read($id) {
        $queryParamArray['id'] = $id;
        $entity = Constant::ENTITY_EXTRACTS;
        $extractsObject = $this->getEntityByParameterList($queryParamArray, $entity);    
        return $extractsObject;
    }
    
    /*
     * Search extracts 
     */
     public function searchExtracts($inputParamArray)
    {
         $query = "select e,u.firstName,u.lastName from Application\Entity\Extracts e       
            INNER JOIN Application\Entity\User u WITH u.id = e.userId           
            WHERE (e.userId = :userId OR e.userId IN (select se.userId from Application\Entity\Extracts se WHERE se.parentUserId = :userId AND se.deleteFlag = 0))
            AND e.deleteFlag = 0";       
        
        if ($inputParamArray['sort_order'] && $inputParamArray['sort_column']) {
            $query .= " ORDER BY {$inputParamArray['sort_column']} {$inputParamArray['sort_order']} ";
        }
                
        $queryResult = $this->getEntityManager()
                ->createQuery($query)
                ->setMaxResults($inputParamArray['limit'])
                ->setFirstResult($inputParamArray['offset']);        
        
        $queryResult->setParameter("userId", $inputParamArray['user_id']);
        
        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($queryResult, $fetchJoinCollection = true);
        $totalCount = count($paginator);
        $analysisRequest = $queryResult->getResult();                    
        $analysisRequestDetailsArray['total_count'] = $totalCount;
        $analysisRequestDetailsArray['extract_object_list'] = $analysisRequest;

        return $analysisRequestDetailsArray;
        
    }
    
    
    /*
     * get extracts using userid and its child  
     */
    
    /*
     * Search extracts 
     */
     public function getExtractsUsingUserId($userId)
    {
         $query = "select e.id, e.extractName from Application\Entity\Extracts e
            WHERE (e.userId = :userId OR e.userId IN (select se.userId from Application\Entity\Extracts se WHERE se.parentUserId = :userId AND se.deleteFlag = 0))
            AND e.deleteFlag = 0";                  
        $queryResult = $this->getEntityManager()->createQuery($query);                      
        $queryResult->setParameter("userId", $userId);        
        $analysisRequest = $queryResult->getArrayResult();                    
        return $analysisRequest;
        
    }
}
