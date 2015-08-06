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

class RiskDao extends \Application\Model\AbstractDao
{
    /*
     * Get user rule book list
     * @param string session_guid
     * 
     * @return array  $userRuleBookList
     * 
     */
    
    public function exchangeArray($inputDataArray, $riskObject = null){
        if ($riskObject) {
            $riskObject = \Application\Model\Utility::setDateTimeForUpdation($riskObject);
        } else {
            $riskObject = new \Application\Entity\Risk();
            $riskObject = \Application\Model\Utility::setDateTimeForCreation($riskObject);
        }
        
        $riskObject->setRulebookId($inputDataArray['rule_book_id']);
        $riskObject->setSapRiskId($inputDataArray['sap_risk_id']);
        $riskObject->setSingleFunctionRisk($inputDataArray['single_function_risk']);        
        $riskObject->setRiskCategory($inputDataArray['risk_category']);
        $riskObject->setRiskLevel($inputDataArray['risk_level']);
        $riskObject->setDescription($inputDataArray['description']);
        $riskObject->setIsDefaultRisk($inputDataArray['is_default_risk']);
        $riskObject->setUserId($inputDataArray['user_id']);
        
        return $riskObject;
    }
    
    
    
    /*
     * returns row elements
     */
    public function read($id) {        
        $queryParamArray['id'] = $id;
        $entity = Constant::ENTITY_RISK;
        $packageObject = $this->getEntityByParameterList($queryParamArray, $entity);    
        return $packageObject;
    }
    
    
    public function riskNameMatch($postData) {
        $riskName = '"'.str_replace('"', '\\"', $postData['risk_id']).'"';
        $query = "SELECT r.id FROM rulebook_has_risk rhs
                  LEFT JOIN risk r ON (rhs.risk_id = r.id)
                  WHERE LOWER(r.sap_risk_id) = LOWER(".$riskName.") AND rhs.rulebook_id = ".$postData['rulebook_id']." AND rhs.delete_flag = 0";        
        $statement = $this->getEntityManager()->getConnection()->prepare($query);
        $statement->execute();
        return $statement->fetchAll();        
    }
    
}
