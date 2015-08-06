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

class AnalysisRequestDao extends \Application\Model\AbstractDao
{
  
    /**
     * Populate a UserSession Object 
     * Exchange/populate UserSession Array
     * 
     * @param List $paramList ParameterList
     * 
     * @return object userSession
     */
    public function exchangeArray($postDataArray, $analysisRequestObject = null)
    {

        if ($analysisRequestObject) {
            $analysisRequestObject = \Application\Model\Utility::setDateTimeForUpdation($analysisRequestObject);
        } else {
            $analysisRequestObject = new \Application\Entity\AnalysisRequest();
            $analysisRequestObject = \Application\Model\Utility::setDateTimeForCreation($analysisRequestObject);
        }
        
        $analysisRequestObject->setUserId($postDataArray['user_id']);
        $analysisRequestObject->setRulebookId($postDataArray['rulebook_id']);
        $analysisRequestObject->setExtractId($postDataArray['extract_id']);
        $analysisRequestObject->setParentUserId($postDataArray['parent_user_id']);
        $analysisRequestObject->setStatus($postDataArray['status']);
        $analysisRequestObject->setAnalysisRequestDescription($postDataArray['analysis_request_description']);  
        $analysisRequestObject->setAnalysisRequestName($postDataArray['analysis_request_name']);     
        $analysisRequestObject->setIsFreeTrialRequest($postDataArray['is_free_trial_request']);   
        
        return $analysisRequestObject;
    }
    
    
    /**
     * Add criteari and get completed analysis request
     * 
     *@param Object $analysisRequestObjectList  Object
     *@param int $status  
     *  
     *@return Object $analysisRequest 
     */    
    public function getAnalysisRequestListUsingStatus($analysisRequestObjectList, $status)
    {
        $criteria = Criteria::create()
                ->where(Criteria::expr()->eq("status", $status));
        $analysisRequest = $analysisRequestObjectList->matching($criteria);
        return $analysisRequest;
    }
    
    
    public function searchAnalysisRequest($inputParamArray)
    {
         $query = "select ar,r.name,e.extractName,e.jobId from Application\Entity\AnalysisRequest ar  
            INNER JOIN Application\Entity\Extracts e WITH e.id = ar.extractId
            INNER JOIN Application\Entity\Rulebook r WITH r.id = ar.rulebookId
            WHERE ar.userId = :userId AND ar.deleteFlag = 0";
            
         
        if (isset($inputParamArray['status'])) {
            $query .= " AND ar.status = :statusId AND
            ar.fileExpireDtTm >= :currentDtTm";
        } else {
            $query .= " AND ar.status != 'completed'";
        }
        
        if ($inputParamArray['sort_order'] && $inputParamArray['sort_column']) {
            $query .= " ORDER BY {$inputParamArray['sort_column']} {$inputParamArray['sort_order']} ";
        }
                
        $queryResult = $this->getEntityManager()
                ->createQuery($query)
                ->setMaxResults($inputParamArray['limit'])
                ->setFirstResult($inputParamArray['offset']);
        
        $currentDateTime = date("Y-m-d h:i:s");
        
        $queryResult->setParameter("userId", $inputParamArray['user_id']);
        if (isset($inputParamArray['status'])) {
            $queryResult->setParameter("statusId", $inputParamArray['status']);
            $queryResult->setParameter("currentDtTm", $currentDateTime);
        }
        
        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($queryResult, $fetchJoinCollection = true);
        $totalCount = count($paginator);
        $analysisRequest = $queryResult->getResult();                    
        $analysisRequestDetailsArray['total_count'] = $totalCount;
        $analysisRequestDetailsArray['analysis_request_object'] = $analysisRequest;

        return $analysisRequestDetailsArray;
        
    }
    
    public function read($id) {
        $queryParamArray['id'] = $id;
        $entity = Constant::ENTITY_ANALYSIS_REQUEST;
        $analysisRequestObject = $this->getEntityByParameterList($queryParamArray, $entity);    
        return $analysisRequestObject;
    }
    
    
        
    /*
     * Update in active user session
     * @param int $testRun
     * 
     * @return int 
     */
    public function isExtractAssociatedWithMoreThenOneAnalysisRequest($analysisReqId, $extractId)
    {        
        $query = "SELECT extract_id from analysis_request where id <> {$analysisReqId} AND extract_id={$extractId} 
            AND file_expire_dt_tm >= Now() AND delete_flag=0";
        $connection = $this->getEntityManager()->getConnection();
        $nowOfRows = $connection->executeQuery($query)->rowCount();
        return $nowOfRows;
    }
    
}
