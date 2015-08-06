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

class TransactionsDao extends \Application\Model\AbstractDao
{
    /*
     * Get user rule book list
     * @param string session_guid
     * 
     * @return array  $userRuleBookList
     * 
     */
    
    public function exchangeArray($inputDataArray, $transactionsObject = null){
        if ($transactionsObject) {
            $transactionsObject = \Application\Model\Utility::setDateTimeForUpdation($transactionsObject);
        } else {
            $transactionsObject = new \Application\Entity\Transactions();
            $transactionsObject = \Application\Model\Utility::setDateTimeForCreation($transactionsObject);
        }
        
        $transactionsObject->setRulebookId($inputDataArray['rule_book_id']);
        $transactionsObject->setSapTransactionId($inputDataArray['sap_transaction_id']);
        $transactionsObject->setDescription($inputDataArray['description']);
        $transactionsObject->setIsDefaultTransaction($inputDataArray['is_default_transaction']);   
        $transactionsObject->setUserId($inputDataArray['user_id']);
        
        return $transactionsObject;
    }           
    
    /*
     * returns row elements
     */
    public function read($id) {        
        $queryParamArray['id'] = $id;
        $entity = Constant::ENTITY_TRANSACTIONS;
        $packageObject = $this->getEntityByParameterList($queryParamArray, $entity);    
        return $packageObject;
    }
    
    public function defaultTransaction($userId){
        $query = "SELECT * FROM transactions WHERE delete_flag = 0 AND (is_default_transaction = 1 OR user_id = ".$userId.")";
        $statement = $this->getEntityManager()->getConnection()->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }
    
    public function transactionNameMatch($postData) {
        $transactionName = '"'.str_replace('"', '\\"', $postData['transaction_id']).'"';
        $query = "SELECT t.id, jfht.id as jobFunctionHasTransactionId FROM job_function_has_transaction jfht
                  LEFT JOIN transactions t ON (jfht.transaction_id = t.id)
                  WHERE LOWER(t.sap_transaction_id) = LOWER(".$transactionName.") AND jfht.job_function_id = ".$postData['job_function_id']." AND jfht.delete_flag = 0";        
        $statement = $this->getEntityManager()->getConnection()->prepare($query);
        $statement->execute();
        return $statement->fetchAll();        
    }
    
    public function checkDefaultTransaction($postData) {
        $transactionName = '"'.str_replace('"', '\\"', $postData['transaction_id']).'"';
        $query = "SELECT id FROM transactions                  
                  WHERE LOWER(sap_transaction_id) = LOWER(".$transactionName.") AND is_default_transaction = 1 AND delete_flag = 0";        
        $statement = $this->getEntityManager()->getConnection()->prepare($query);
        $statement->execute();
        return $statement->fetchAll();   
    }
    
    public function getExistingTransactionIdForUser($postData, $userId) {
        $transactionName = '"'.str_replace('"', '\\"', $postData['transaction_id']).'"';
        $query = "SELECT id FROM transactions 
                  WHERE LOWER(sap_transaction_id) = LOWER(".$transactionName.") AND user_id = ".$userId." AND delete_flag = 0 AND is_default_transaction = 0";        
        $statement = $this->getEntityManager()->getConnection()->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }    
    
    
}
