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

namespace Application\Model;

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
class SystemActivityDao extends \Application\Model\AbstractDao
{

    public function read($id)
    {
        $queryParamArray['id'] = $id;
        $entity = Constant::ENTITY_SYSTEM_ACTIVITY;
        $activityObject = $this->getEntityByParameterList($queryParamArray, $entity);
        return $activityObject;
    }

    /**
     * Populate a UserSession Object 
     * Exchange/populate UserSession Array
     * 
     * @param List $paramList ParameterList
     * 
     * @return object userSession
     */
    public function exchangeArray($postDataArray, $systemActivityObject = null)
    {

        if ($systemActivityObject) {
            $systemActivityObject = \Application\Model\Utility::setDateTimeForUpdation($systemActivityObject);
        } else {
            $systemActivityObject = new \Application\Entity\SystemActivity();
            $systemActivityObject = \Application\Model\Utility::setDateTimeForCreation($systemActivityObject);
        }

        $systemActivityObject->setActivityId($postDataArray['activity_id']);
        $systemActivityObject->setUserId($postDataArray['user_id']);
        $systemActivityObject->setComment($postDataArray['comment']);


        return $systemActivityObject;
    }

    /*
     * Insert into system activity log
     */

    public function createSystemActivityLog($code, $userId, $comment)
    {

        $activityDaoService = $this->getActivityDaoService();
        $entity = Constant::ENTITY_ACTIVITY;
        $queryParamArray['code'] = $code;
        $activityObject = $activityDaoService->getEntityByParameterList($queryParamArray, $entity);
        $activityId = $activityObject->getId();

        $systemActivityLogInputArray['activity_id'] = $activityId;
        $systemActivityLogInputArray['user_id'] = $userId;
        $systemActivityLogInputArray['comment'] = $comment;
        $systemActivityObject = $this->createUpdateEntity($systemActivityLogInputArray);
   
    }
    
    /*
     * Search extracts 
     */
     public function searchSystemActivity($inputParamArray)
    {
         $query = "select a.type,u.firstName,u.lastName,sa.createdDtTm,sa.comment
            FROM Application\Entity\SystemActivity sa      
            INNER JOIN Application\Entity\Activity a WITH a.id = sa.activityId
            LEFT JOIN Application\Entity\User u WITH u.id = sa.userId
            WHERE sa.deleteFlag = 0";       
        
        if ($inputParamArray['search_query']) {
            $query .= " AND (u.firstName LIKE '%".$inputParamArray['search_query']."%' OR
                u.lastName LIKE '%".$inputParamArray['search_query']."%' OR
                a.type LIKE '%".$inputParamArray['search_query']."%' OR
                sa.comment LIKE '%".$inputParamArray['search_query']."%')  ";

        }
         
        if ($inputParamArray['sort_order'] && $inputParamArray['sort_column']) {
            $query .= " ORDER BY {$inputParamArray['sort_column']} {$inputParamArray['sort_order']} ";
        }
                
        $queryResult = $this->getEntityManager()
                ->createQuery($query)
                ->setMaxResults($inputParamArray['limit'])
                ->setFirstResult($inputParamArray['offset']);        
        
        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($queryResult, $fetchJoinCollection = true);
        $paginator->setUseOutputWalkers(false);
        $totalCount = count($paginator);        
        $systemActivityObjectList = $queryResult->getResult();                    
        $systemActivityDetailsArray['total_count'] = $totalCount;
        $systemActivityDetailsArray['system_activity_object_list'] = $systemActivityObjectList;

        return $systemActivityDetailsArray;
        
    }

}
