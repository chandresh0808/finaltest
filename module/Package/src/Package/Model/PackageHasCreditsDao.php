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

namespace Package\Model;
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

class PackageHasCreditsDao extends \Application\Model\AbstractDao
{    
    public function read($id) {
        $queryParamArray['id'] = $id;
        $entity = Constant::ENTITY_PACKAGE_HAS_CREDITS;
        $packageHasCreditsObject = $this->getEntityByParameterList($queryParamArray, $entity);    
        return $packageHasCreditsObject;
    }
            
     /**
     * Populate a UserSession Object 
     * Exchange/populate UserSession Array
     * 
     * @param List $paramList ParameterList
     * 
     * @return object userSession
     */
    public function exchangeArray($postDataArray, $packageHasCreditsObject = null)
    {

        if ($packageHasCreditsObject) {
            $packageHasCreditsObject = \Application\Model\Utility::setDateTimeForUpdation($packageHasCreditsObject);
        } else {
            $packageHasCreditsObject = new \Application\Entity\PackageHasCredits();
            $packageHasCreditsObject = \Application\Model\Utility::setDateTimeForCreation($packageHasCreditsObject);
        }
        
        $packageHasCreditsObject->setPackageId($postDataArray['package_id']);
        $packageHasCreditsObject->setPackageDuration($postDataArray['package_duration']);
        $packageHasCreditsObject->setTotalCredits($postDataArray['total_credits']);
        $packageHasCreditsObject->setPackageAmount($postDataArray['package_amount']);
        $packageHasCreditsObject->setMaxReportPerDay($postDataArray['max_report_per_day']);       

        return $packageHasCreditsObject;
    }
    
}
