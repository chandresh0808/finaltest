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

class SystemSaltDao extends \Application\Model\AbstractDao
{
    
           
    /**
     * Populate a UserSession Object 
     * Exchange/populate UserSession Array
     * 
     * @param List $paramList ParameterList
     * 
     * @return object userSession
     */
    public function exchangeArray($inputDataArray, $systemSaltObject = null)
    {

        if ($systemSaltObject) {
            $systemSaltObject = \Application\Model\Utility::setDateTimeForUpdation($systemSaltObject);
        } else {
            $systemSaltObject = new \Application\Entity\SystemSalt();
            $systemSaltObject = \Application\Model\Utility::setDateTimeForCreation($systemSaltObject);
        }
        
        $systemSaltObject->setType($inputDataArray['type']);   
        $systemSaltObject->setSalt($inputDataArray['salt']);        

        return $systemSaltObject;
    }
        
    

    
}
