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

namespace User\Model;
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

class UserCreditHistoryDao extends \Application\Model\AbstractDao
{
    /**
     * Populate a UserSession Object 
     * Exchange/populate UserSession Array
     * 
     * @param List $paramList ParameterList
     * 
     * @return object userSession
     */
    public function exchangeArray($postDataArray, $userCreditHistoryObject = null)
    {

        if ($userCreditHistoryObject) {
            $userCreditHistoryObject = \Application\Model\Utility::setDateTimeForUpdation($userCreditHistoryObject);
        } else {
            $userCreditHistoryObject = new \Application\Entity\UserAnalysisCreditHistory();
            $userCreditHistoryObject = \Application\Model\Utility::setDateTimeForCreation($userCreditHistoryObject);
        }
        
        $userCreditHistoryObject->setUserId($postDataArray['user_id']);
        $userCreditHistoryObject->setUserHasPackageId($postDataArray['user_has_package_id']);
        $userCreditHistoryObject->setTotalCreditAnalysisPoints($postDataArray['total_credits']);
        $userCreditHistoryObject->setCreditAnalysisPointsUsed($postDataArray['used_credits']);   

        return $userCreditHistoryObject;
    }    
    
}
