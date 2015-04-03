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

class UserManager extends \Application\Model\AbstractCommonServiceMutator
{
   public function createUserSessionEntry($inputDataArray) {
       $userSessionDaoService = $this->getUserSessionDaoService();
       $userSessionObject = $userSessionDaoService->createUpdateEntity($inputDataArray);
       return $userSessionObject;
   }
   
   /*
    * Create a entry in user_has_salt table
    * @param array $inputDataArray
    * 
    * @return object $userHasSalt
    * 
    */
   
    public function createUserHasSaltEntry($inputDataArray) {
       $userHasSaltDaoService = $this->getUserHasSaltDaoService();
       $userHasSaltObject = $userHasSaltDaoService->createUpdateEntity($inputDataArray);
       return $userHasSaltObject;
   }
   
   /*
    * Check for user has session
    * @param string $sessionGuid
    * 
    * @return bool $result
    * 
    */
   
   public function isUserHasSession($sessionGuid) {
       $userSessionDaoService = $this->getUserSessionDaoService();
       $queryParamArray['sessionGuid'] = $sessionGuid;
       $userSessionObject = $userSessionDaoService->getUserSessionByParameterList($queryParamArray);
       $result = false;
       if(is_object($userSessionObject)) {
           $result = true;
       }
       return $result;
   }
}
