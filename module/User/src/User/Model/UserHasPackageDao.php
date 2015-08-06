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

class UserHasPackageDao extends \Application\Model\AbstractDao
{
    /**
     * Populate a UserSession Object 
     * Exchange/populate UserSession Array
     * 
     * @param List $paramList ParameterList
     * 
     * @return object userSession
     */
    public function exchangeArray($postDataArray, $userHasPackageObject = null)
    {

        if ($userHasPackageObject) {
            $userHasPackageObject = \Application\Model\Utility::setDateTimeForUpdation($userHasPackageObject);
        } else {
            $userHasPackageObject = new \Application\Entity\UserHasPackage();
            $userHasPackageObject = \Application\Model\Utility::setDateTimeForCreation($userHasPackageObject);
        }
        
        $userHasPackageObject->setUserId($postDataArray['user_id']);
        $userHasPackageObject->setPackageId($postDataArray['package_id']);
        $userHasPackageObject->setPackageHasCreditsId($postDataArray['package_has_credits_id']);
        $userHasPackageObject->setPackageEffectiveDtTm($postDataArray['package_effective_dt_tm']);
        $userHasPackageObject->setPackageExpiryDtTm($postDataArray['package_expiry_dt_tm']);      

        return $userHasPackageObject;
    }
    
    public function expiredPackages($userId){
        $current_timestamp = "'".date('Y-m-d H:i:s')."'";
        $query = "SELECT uach.total_credit_analysis_points, uach.credit_analysis_points_used FROM user_has_package uhp
                  LEFT JOIN user_analysis_credit_history uach ON (uach.user_has_package_id = uhp.id)
                  WHERE uhp.package_expiry_dt_tm < ".$current_timestamp." AND uach.user_id = ".$userId;        
        $statement = $this->getEntityManager()->getConnection()->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }
    
}
