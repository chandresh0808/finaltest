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

class UserSessionDao extends \Application\Model\AbstractCommonServiceMutator
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

        $userSessionObject->setUserId($postDataArray['user_id']);
        $userSessionObject->setSessionGuid($postDataArray['session_guid']);
        $userSessionObject->setAnalysisRequestGuid($postDataArray['analysis_request_guid']);

        return $userSessionObject;
    }

    
    /**
     * Get a user object using paramters
     * 
     * @param List $paramList paramter list
     * 
     * @return User\Entity\User
     */
    public function getUserSessionByParameterList($paramList)
    {
        try {
            if (!empty($paramList)) {
                $paramList['deleteFlag'] = 0;
                $returnObject = $this->getEntityManager()
                    ->getRepository('Application\Entity\UserSession')
                    ->findOneBy($paramList);
                return $returnObject;
            }
        } catch (\Exception $exc) {
           //@TODO: Need log this error
            throw new \Exception($exc);
        }
    }
    
}
