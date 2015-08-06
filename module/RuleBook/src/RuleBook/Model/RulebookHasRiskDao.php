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

class RulebookHasRiskDao extends \Application\Model\AbstractDao
{
    /*
     * Get user rule book has risk list 
     * 
     * @return array  $userRuleBookHasRiskList
     * 
     */
    
    public function exchangeArray($inputDataArray, $rulebookHasRiskObject = null){
        if ($rulebookHasRiskObject) {
            $rulebookHasRiskObject = \Application\Model\Utility::setDateTimeForUpdation($rulebookHasRiskObject);
        } else {
            $rulebookHasRiskObject = new \Application\Entity\RulebookHasRisk();
            $rulebookHasRiskObject = \Application\Model\Utility::setDateTimeForCreation($rulebookHasRiskObject);
        }
        
        $rulebookHasRiskObject->setrulebookId($inputDataArray['rulebook_id']);
        $rulebookHasRiskObject->setRiskId($inputDataArray['risk_id']);        
        
        return $rulebookHasRiskObject;
    }
    
    
    /*
     * returns row elements
     */
    public function read($id) {        
        $queryParamArray['id'] = $id;
        $entity = Constant::ENTITY_RULEBOOK_HAS_RISK;
        $packageObject = $this->getEntityByParameterList($queryParamArray, $entity);    
        return $packageObject;
    }
}

?>
