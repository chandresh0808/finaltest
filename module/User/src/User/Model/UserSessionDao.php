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

class UserSessionDao extends \Application\Model\AbstractDao
{
    
           
    /**
     * Populate a UserSession Object 
     * Exchange/populate UserSession Array
     * 
     * @param List $paramList ParameterList
     * 
     * @return object userSession
     */
    public function exchangeArray($postDataArray, $userSessionObject = null)
    {

        if ($userSessionObject) {
            $userSessionObject = \Application\Model\Utility::setDateTimeForUpdation($userSessionObject);
        } else {
            $userSessionObject = new \Application\Entity\UserSession();
            $userSessionObject = \Application\Model\Utility::setDateTimeForCreation($userSessionObject);
        }
        
        $epochTime = \Application\Model\Utility::getCurrentEpochTime();
        $userSessionObject->setUserId($postDataArray['user_id']);
        $userSessionObject->setSessionGuid($postDataArray['session_guid']);
        $userSessionObject->setLastRequestDtTm($epochTime);

        return $userSessionObject;
    }
        
    
}
