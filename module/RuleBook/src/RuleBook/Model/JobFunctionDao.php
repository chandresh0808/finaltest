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

namespace RuleBook\Model;
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

class JobFunctionDao extends \Application\Model\AbstractDao
{
    /*
     * Get user rule book list
     * @param string session_guid
     * 
     * @return array  $userRuleBookList
     * 
     */
    
    public function exchangeArray($inputDataArray, $JobFunctionObject = null){
        if ($JobFunctionObject) {
            $JobFunctionObject = \Application\Model\Utility::setDateTimeForUpdation($JobFunctionObject);
        } else {
            $JobFunctionObject = new \Application\Entity\JobFunction();
            $JobFunctionObject = \Application\Model\Utility::setDateTimeForCreation($JobFunctionObject);
        }
        
        $JobFunctionObject->setRulebookId($inputDataArray['rule_book_id']);
        $JobFunctionObject->setSapJobFunctionId($inputDataArray['sap_job_function_id']);
        $JobFunctionObject->setDescription($inputDataArray['description']);
        $JobFunctionObject->setIsDefaultJobFunction($inputDataArray['is_default_job_function']); 
        $JobFunctionObject->setUserId($inputDataArray['user_id']);
               
        return $JobFunctionObject;
    }
    
    
    
    /*
     * returns row elements
     */
    public function read($id) {        
        $queryParamArray['id'] = $id;
        $entity = Constant::ENTITY_JOB_FUNCTION;
        $packageObject = $this->getEntityByParameterList($queryParamArray, $entity);    
        return $packageObject;
    }
    
    public function defaultJobFunction($userId){        
        $query = "SELECT * FROM job_function WHERE delete_flag = 0 AND (is_default_job_function = 1 OR user_id = ".$userId.")";
        $statement = $this->getEntityManager()->getConnection()->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }
    
    public function jobFunctionNameMatch($postData) {
        $jobFunctionName = '"'.str_replace('"', '\\"', $postData['job_function_id']).'"';
        $query = "SELECT jf.id, rhjf.id as riskHasJobFunctionId FROM risk_has_job_function rhjf
                  LEFT JOIN job_function jf ON (rhjf.job_function_id = jf.id)
                  WHERE LOWER(jf.sap_job_function_id) = LOWER(".$jobFunctionName.") AND rhjf.risk_id = ".$postData['risk_id']." AND rhjf.delete_flag = 0";        
        $statement = $this->getEntityManager()->getConnection()->prepare($query);
        $statement->execute();
        return $statement->fetchAll();        
    }       
    
    
    public function checkDefaultJobFunction($postData) {
        $jobFunctionName = '"'.str_replace('"', '\\"', $postData['job_function_id']).'"';
        $query = "SELECT job_function_id, transaction_id FROM sap_default_job_function_has_transaction                  
                  WHERE LOWER(original_function_id) = LOWER(".$jobFunctionName.")";        
        $statement = $this->getEntityManager()->getConnection()->prepare($query);
        $statement->execute();
        return $statement->fetchAll();   
    }
    
    public function getExistingJobFunctionIdForUser($postData, $userId) {
        $jobFunctionName = '"'.str_replace('"', '\\"', $postData['job_function_id']).'"';
        $query = "SELECT id FROM job_function 
                  WHERE LOWER(sap_job_function_id) = LOWER(".$jobFunctionName.") AND user_id = ".$userId." AND delete_flag = 0 AND is_default_job_function = 0";                                
        $statement = $this->getEntityManager()->getConnection()->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }    
    
    
    public function getTransactionForExistingJobFunctionId($jobFunctionId, $userId) {        
        $query = "SELECT jfht.transaction_id FROM job_function jf                 
                  LEFT JOIN risk_has_job_function rhjf ON (jf.id = rhjf.job_function_id)
                  LEFT JOIN job_function_has_transaction jfht ON (rhjf.id = jfht.job_function_id)
                  WHERE jf.id = ".$jobFunctionId." AND jf.user_id = ".$userId." AND jfht.delete_flag = 0";        
        $statement = $this->getEntityManager()->getConnection()->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }
    
}
