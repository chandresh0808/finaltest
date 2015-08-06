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

namespace RuleBook\Model;
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

class RuleBookDao extends \Application\Model\AbstractDao
{
    /*
     * Get user rule book list
     * @param string session_guid
     * 
     * @return array  $userRuleBookList
     * 
     */
    
    public function exchangeArray($inputDataArray, $ruleBookObject = null){
        if ($ruleBookObject) {
            $ruleBookObject = \Application\Model\Utility::setDateTimeForUpdation($ruleBookObject);
        } else {
            $ruleBookObject = new \Application\Entity\Rulebook();
            $ruleBookObject = \Application\Model\Utility::setDateTimeForCreation($ruleBookObject);
        }
        
        $ruleBookObject->setName($inputDataArray['name']);
        $ruleBookObject->setUserId($inputDataArray['user_id']);
        $ruleBookObject->setDescription($inputDataArray['description']);        
        $ruleBookObject->setCopiedFromRulebookId($inputDataArray['copied_from_rulebook_id']);        
        
        return $ruleBookObject;
    }
    
    
    public function searchRuleBookRequest($inputParamArray){               
        
        $query = "select rb from Application\Entity\Rulebook rb                
            WHERE rb.userId IN (:userId) AND rb.deleteFlag = 0";       
        
        if ($inputParamArray['search_query']) {
            $query .= " AND rb.name LIKE '%".$inputParamArray['search_query']."%' ";

        }
        
        if ($inputParamArray['sort_order'] && $inputParamArray['sort_column']) {
            $query .= " ORDER BY {$inputParamArray['sort_column']} {$inputParamArray['sort_order']} ";
        }       
        
       
        
        $queryResult = $this->getEntityManager()
                ->createQuery($query)
                ->setMaxResults($inputParamArray['limit'])
                ->setFirstResult($inputParamArray['offset']);
       
        $queryResult->setParameter("userId", $inputParamArray['user_id']);       
        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($queryResult, $fetchJoinCollection = true);        
        $totalCount = count($paginator);
        $ruleBookRequest = $queryResult->getResult();
        $ruleBookRequestDetailsArray['total_count'] = $totalCount;
        $ruleBookRequestDetailsArray['rulebook_request_object'] = $ruleBookRequest;        
        return $ruleBookRequestDetailsArray;
    }
    
    /*
     * returns row elements
     */
    public function read($id) {        
        $queryParamArray['id'] = $id;
        $entity = Constant::ENTITY_RULEBOOK;
        $packageObject = $this->getEntityByParameterList($queryParamArray, $entity);    
        return $packageObject;
    }
    
}
