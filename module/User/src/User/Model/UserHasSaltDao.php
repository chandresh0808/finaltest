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

class UserHasSaltDao extends \Application\Model\AbstractDao
{
    
           
    /**
     * Populate a UserSession Object 
     * Exchange/populate UserSession Array
     * 
     * @param List $paramList ParameterList
     * 
     * @return object userSession
     */
    public function exchangeArray($inputDataArray, $userHasSaltObject = null)
    {

        if ($userHasSaltObject) {
            $userHasSaltObject = \Application\Model\Utility::setDateTimeForUpdation($userHasSaltObject);
        } else {
            $userHasSaltObject = new \Application\Entity\UserHasSalt();
            $userHasSaltObject = \Application\Model\Utility::setDateTimeForCreation($userHasSaltObject);
        }

        $userHasSaltObject->setSalt($inputDataArray['salt']);        

        return $userHasSaltObject;
    }

}
