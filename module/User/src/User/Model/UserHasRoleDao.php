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

class UserHasRoleDao extends \Application\Model\AbstractDao
{
    
           
    /**
     * Populate a UserSession Object 
     * Exchange/populate UserSession Array
     * 
     * @param List $paramList ParameterList
     * 
     * @return object userSession
     */
    public function exchangeArray($inputDataArray, $userHasRoleObject = null)
    {

        if ($userHasRoleObject) {
            $userHasRoleObject = \Application\Model\Utility::setDateTimeForUpdation($userHasRoleObject);
        } else {
            $userHasRoleObject = new \Application\Entity\UserHasRole();
            $userHasRoleObject = \Application\Model\Utility::setDateTimeForCreation($userHasRoleObject);
        }

        $userHasRoleObject->setUserId($inputDataArray['user_id']);
        $userHasRoleObject->setRoleId($inputDataArray['role_id']);

        return $userHasRoleObject;
    }

}
