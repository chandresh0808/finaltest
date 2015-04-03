<?php

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

namespace Auth\Model;
 
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

class AuthManager extends \Application\Model\AbstractCommonServiceMutator
{
    public function authenticateUser($userName, $userEmailId)
    {        
        $authDaoService = $this->getAuthDaoService();        
        $queryParameterArray['username'] = $userName;
        $queryParameterArray['password']  = $userEmailId;        
        $user = $authDaoService->getUserByParameterList($queryParameterArray);                
        if(is_object($user)) {
            return $user; 
        }        
        return false;        
    }
}
