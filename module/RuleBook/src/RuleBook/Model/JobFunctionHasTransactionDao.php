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

class JobFunctionHasTransactionDao extends \Application\Model\AbstractDao
{
    /*
     * Get user rule book has risk list 
     * 
     * @return array  $userRuleBookHasRiskList
     * 
     */
    
    public function exchangeArray($inputDataArray, $jobFunctionHasTransactionObject = null){
        if ($jobFunctionHasTransactionObject) {
            $jobFunctionHasTransactionObject = \Application\Model\Utility::setDateTimeForUpdation($jobFunctionHasTransactionObject);
        } else {
            $jobFunctionHasTransactionObject = new \Application\Entity\JobFunctionHasTransaction();
            $jobFunctionHasTransactionObject = \Application\Model\Utility::setDateTimeForCreation($jobFunctionHasTransactionObject);
        }
        
        $jobFunctionHasTransactionObject->setJobFunctionId($inputDataArray['job_function_id']);
        $jobFunctionHasTransactionObject->setTransactionId($inputDataArray['transaction_id']);        
        
        return $jobFunctionHasTransactionObject;
    }
    
    /*
     * returns row elements
     */
    public function read($id) {        
        $queryParamArray['id'] = $id;
        $entity = Constant::ENTITY_JOB_FUNCTION_HAS_TRANSACTION;
        $packageObject = $this->getEntityByParameterList($queryParamArray, $entity);    
        return $packageObject;
    }
}

?>
