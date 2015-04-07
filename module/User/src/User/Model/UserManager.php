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
   
    public function createAuthSaltEntry($inputDataArray) {
       $systemSaltDaoService = $this->getSystemSaltDaoService();
       $systemSaltObject = $systemSaltDaoService->createUpdateEntity($inputDataArray);
       return $systemSaltObject;
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
       $entity = Constant::ENTITY_USER_SESSION;
       $userSessionObject = $userSessionDaoService->getEntityByParameterList($queryParamArray, $entity);
       $result = false;
       if(is_object($userSessionObject)) {
           $result = true;
       }
       return $result;
   }
   
   /*
    * Read user salt using ref_id
    * @param int $refId
    * 
    * @return object $systemSaltObject
    */
   public function getAuthSaltUsingId($refId)
    {
        $systemSaltDaoService = $this->getSystemSaltDaoService();
        $queryParamArray['id'] = $refId;
        $entity = Constant::ENTITY_SYSTEM_SALT;
        $systemSaltObject = $systemSaltDaoService->getEntityByParameterList($queryParamArray, $entity);
        return $systemSaltObject;
    }
}
