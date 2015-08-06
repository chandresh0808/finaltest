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

class UserDao extends \Application\Model\AbstractDao
{
    /**
     * Populate a UserSession Object 
     * Exchange/populate UserSession Array
     * 
     * @param List $paramList ParameterList
     * 
     * @return object userSession
     */
    public function exchangeArray($postDataArray, $userObject = null)
    {

        if ($userObject) {
            $userObject = \Application\Model\Utility::setDateTimeForUpdation($userObject);
        } else {
            $userObject = new \Application\Entity\User();
            $userObject = \Application\Model\Utility::setDateTimeForCreation($userObject);
        }
        
        $userObject->setUsername($postDataArray['email_id']);
        $userObject->setPassword($postDataArray['password']);
        $userObject->setFirstName($postDataArray['first_name']);
        $userObject->setLastName($postDataArray['last_name']);
        $userObject->setPhoneNumber($postDataArray['phone_number']);
        $userObject->setFreeTrialFlag($postDataArray['free_trial_flag']);
        $userObject->setActivePackageFlag($postDataArray['active_package_flag']);
        $userObject->setActivationCode($postDataArray['activation_code']);
        $userObject->setActiveFlag($postDataArray['active_flag']);
        $userObject->setParentUserId($postDataArray['parent_user_id']);
        $userObject->setIsBlocked($postDataArray['is_blocked']);

        return $userObject;
    }
    
    
    public function read($id) {
        $queryParamArray['id'] = $id;
        $entity = Constant::ENTITY_USER;
        $analysisRequestObject = $this->getEntityByParameterList($queryParamArray, $entity);    
        return $analysisRequestObject;
    }       
    
    
    public function userListRequest($inputParamArray) {
        $query = "SELECT u.id, u.first_name, u.last_name, u.username, u.last_login, u.active_flag, u.is_blocked,uhr.role_id, uach.user_id,(sum(total_credit_analysis_points) - sum(credit_analysis_points_used)) as available 
                  FROM user u 
                  LEFT JOIN user_analysis_credit_history uach on u.id = uach.user_id 
                  LEFT JOIN user_has_role uhr on u.id = uhr.user_id where uhr.role_id != 1 AND u.delete_flag = 0";                  
        
        if (isset($inputParamArray['associate_user_list'])) {
            $query .= " AND u.parent_user_id = ".$inputParamArray['user_id']." AND u.active_flag = 1 AND u.is_blocked = 0";  
        }
        
        if (isset($inputParamArray['associate_user_list_admin'])) {
            $query .= " AND u.parent_user_id = ".$inputParamArray['user_id']." group by u.id";  
        }
        
        if ($inputParamArray['search_query']) {
            if (strtolower($inputParamArray['search_query']) == 'active') {                
                $query .= " AND u.active_flag = 1 ";  
            } elseif (strtolower($inputParamArray['search_query']) == 'inactive') {
                $query .= " AND u.active_flag = 0";  
            } else {
                $query .= " AND (u.first_name LIKE '%".$inputParamArray['search_query']."%' OR  
                            u.last_name LIKE '%".$inputParamArray['search_query']."%' OR
                            u.username LIKE '%".$inputParamArray['search_query']."%')";                        
            }
        }        
        
        if ($inputParamArray['sort_order'] && $inputParamArray['sort_column']) {
            $query .= " group by u.id ORDER BY {$inputParamArray['sort_column']} {$inputParamArray['sort_order']} ";
        }
                
        $statement = $this->getEntityManager()->getConnection()->prepare($query);
        $statement->execute();
        $resultArray = $statement->fetchAll();                
        $totalCount = count($resultArray);        
        
        if ($inputParamArray['limit'] OR $inputParamArray['offset']) {
            $resultArray = array_slice($resultArray, $inputParamArray['offset'], $inputParamArray['limit']);
        }
                
        $userRequestDetailsArray['total_count'] = $totalCount;
        $userRequestDetailsArray["user_request_array"] = $resultArray;        
        return $userRequestDetailsArray;                
    }  
    
}
