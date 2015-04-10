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
        $userSessionObject->setUser($postDataArray['user_id']);
        $userSessionObject->setSessionGuid($postDataArray['session_guid']);
        $userSessionObject->setLastRequestDtTm($epochTime);

        return $userSessionObject;
    }
        
    
    /*
     * delete user session
     * @param object $userSession
     * 
     * @return object $userSession
     */
    
    public function update($userSession) {
       $userSession = $this->persistFlush($userSession);   
       return $userSession;
    }
    
    
    /*
     * Update in active user session
     * @param int $testRun
     * 
     * @return int 
     */
    public function deleteInactiveUserSession()
    {
        $query = "UPDATE user_session SET delete_flag=1 WHERE 
            delete_flag='0' AND updated_dt_tm <= DATE_SUB(NOW(), INTERVAL 1 HOUR)";
        $connection = $this->getEntityManager()->getConnection();
        $rowsAffected = $connection->executeUpdate($query);
        return $rowsAffected;
    }

}
