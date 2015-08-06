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

class RiskHasJobFunctionDao extends \Application\Model\AbstractDao
{
    /*
     * Get user rule book has risk list 
     * 
     * @return array  $userRuleBookHasRiskList
     * 
     */
    
    public function exchangeArray($inputDataArray, $riskHasJobFunctionObject = null){
        if ($riskHasJobFunctionObject) {
            $riskHasJobFunctionObject = \Application\Model\Utility::setDateTimeForUpdation($riskHasJobFunctionObject);
        } else {
            $riskHasJobFunctionObject = new \Application\Entity\RiskHasJobFunction();
            $riskHasJobFunctionObject = \Application\Model\Utility::setDateTimeForCreation($riskHasJobFunctionObject);
        }
        
        $riskHasJobFunctionObject->setriskId($inputDataArray['risk_id']);
        $riskHasJobFunctionObject->setjobFunctionId($inputDataArray['job_function_id']);    
        $riskHasJobFunctionObject->setRulebookId($inputDataArray['rulebook_id']);        
        
        return $riskHasJobFunctionObject;
    }
    
    /*
     * returns row elements
     */
    public function read($id) {        
        $queryParamArray['id'] = $id;
        $entity = Constant::ENTITY_RISK_HAS_JOB_FUNCTION;
        $packageObject = $this->getEntityByParameterList($queryParamArray, $entity);    
        return $packageObject;
    }
}

?>
