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

namespace RuleBook\Model;

use Application\Model\Constant as Constant;
use Doctrine\Common\Collections\Criteria;

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
class RuleBookManager extends \Application\Model\AbstractCommonServiceMutator
{
    /*
     * Get user rule book list
     * @param string session_guid
     * 
     * @return array  $userRuleBookList
     * 
     */

    public $rulebookDaoObj;
    public $rulebookHasRiskDaoObj;
    public $riskHasJobFunctionObj;
    public $jobFunctionHasTransactionObj;

    public function init()
    {
        $this->rulebookDaoObj = $this->getRuleBookDaoService();
        $this->rulebookHasRiskDaoObj = $this->getRulebookHasRiskDaoService();
        $this->riskHasJobFunctionObj = $this->getRiskHasJobFunctionDaoService();
        $this->jobFunctionHasTransactionObj = $this->getJobFunctionHasTransactionsDaoService();
    }

    public function getUserRuleBookList($userObject)
    {
        $userId = $userObject->getId();
        $ruleBookDaoService = $this->getRuleBookDaoService();
        $queryParamArray['userId'] = array(0, $userId);
        $entity = Constant::ENTITY_RULEBOOK;
        $userRuleBookList = $ruleBookDaoService->getEntityListByParameterList($queryParamArray, $entity);
        return $userRuleBookList;
    }

    /*
     * Data for analysis report
     */

    public function dataForRuleBookList($paramArray)
    {

        $ruleBookDaoService = $this->getRuleBookDaoService();


        //Sorting column mapping
        $sortColumnNameMap = array(0 => "rb.name", 1 => "rb.updatedDtTm");


        $inputParamArray['user_id'] = $paramArray['user_id'];
        $inputParamArray['status'] = 'Completed';
        $inputParamArray['sort_column'] = $sortColumnNameMap[$paramArray['iSortCol_0']];
        $inputParamArray['sort_order'] = $paramArray['sSortDir_0'];
        $inputParamArray['limit'] = $paramArray['iDisplayLength'];
        $inputParamArray['offset'] = $paramArray['iDisplayStart'];
        $inputParamArray['search_query'] = $paramArray['sSearch'];

        $completedRuleBookRequestResponse = $ruleBookDaoService->searchRuleBookRequest($inputParamArray);

        $completedRuleBookRequestObject = $completedRuleBookRequestResponse['rulebook_request_object'];
        $count = $completedRuleBookRequestResponse['total_count'];

        $inputArrayForDataTables = \Application\Model\Utility::customizeResultForDataTable($completedRuleBookRequestObject, $count);

        return $inputArrayForDataTables;
    }

    /*
     * sets delete flag on rule book deletion
     */

    public function setRuleBookDeleteFlag($postData, $userObject)
    {
        $userId = $userObject->getId();
        $ruleBookId = $postData['rb_id'];
        
        $getRulebookHasRiskArray = $this->getRuleBookHasRiskArray($ruleBookId);
        
        if (!empty($getRulebookHasRiskArray)) {
            foreach($getRulebookHasRiskArray as $getRulebookHasRisk){
                $rulebookHasRiskId[] = $getRulebookHasRisk['id'];
            }
            
            if (!empty($rulebookHasRiskId)) {
                $deleteRulebook = $this->deleteRulebook($ruleBookId, $userId);
                $deleteRulebookHasRisk = $this->deleteRulebookHasRisk($ruleBookId);
                
                if ($deleteRulebookHasRisk) {                        
                    $getRiskHasJobFunctionArray = $this->getRiskHasJobFunction($rulebookHasRiskId);
                    
                    if (!empty($getRiskHasJobFunctionArray)) {                        
                        foreach($getRiskHasJobFunctionArray as $getRiskHasJobFunction){
                            $riskHasJobFunctionId[] = $getRiskHasJobFunction['id'];
                        }
                        
                        if (!empty($riskHasJobFunctionId)) {
                            $deleteRiskHasJobFunction = $this->deleteRiskHasJobFunction($rulebookHasRiskId);
                            
                            if ($deleteRiskHasJobFunction) {
                                $deleteJobFunctionHasTransaction = $this->deleteJobFunctionHasTransaction($riskHasJobFunctionId);
                                
                                if ($deleteJobFunctionHasTransaction) {
                                    $response = \Application\Model\Utility::getResponseArray('success', 'Deleted successfully');
                                } else {
                                    $response = \Application\Model\Utility::getResponseArray('fail', 'Delete was unsuccessful');
                                }
                                
                            } else {
                                $response = \Application\Model\Utility::getResponseArray('fail', 'Delete was unsuccessful');
                            }
                            
                        }
                        
                    }
                    
                } else {
                    $response = \Application\Model\Utility::getResponseArray('fail', 'Delete was unsuccessful');
                }                
            }            
            
        } else {
            $response = \Application\Model\Utility::getResponseArray('fail', 'Delete was unsuccessful');
        }
        
        if(empty($response)){
            $response = \Application\Model\Utility::getResponseArray('success', 'Deleted successfully');
        }

        return $response;
    }
    
    
    public function deleteJobFunctionHasTransaction($riskHasJobFunctionId){
        $riskHasJobFunctionId = implode(',', $riskHasJobFunctionId);
        $jobFunctionHasTransactionObj = $this->getJobFunctionHasTransactionsDaoService();
        $query = 'UPDATE job_function_has_transaction SET delete_flag = 1 WHERE job_function_id IN ('.$riskHasJobFunctionId.')';
        $statement = $jobFunctionHasTransactionObj->getEntityManager()->getConnection()->prepare($query);
        
        if ($statement->execute()) {
            return true;
        } else {
            return false;
        }
        
    }
    
    public function deleteRiskHasJobFunction($rulebookHasRiskId){
        $rulebookHasRiskId = implode(',', $rulebookHasRiskId);
        $riskHasJobFunctionObj = $this->getRiskHasJobFunctionDaoService();
        $query = 'UPDATE risk_has_job_function SET delete_flag = 1 WHERE risk_id IN ('.$rulebookHasRiskId.')';
        $statement = $riskHasJobFunctionObj->getEntityManager()->getConnection()->prepare($query);
        
        if ($statement->execute()) {
            return true;
        }else{
            return false;
        }
    }
    
    
    public function deleteRulebookHasRisk($ruleBookId, $rulebookHasRiskId = ''){        
        $rulebookHasRiskDaoObj = $this->getRulebookHasRiskDaoService();
        $query = 'UPDATE rulebook_has_risk SET delete_flag = 1 WHERE rulebook_id = '.$ruleBookId;
        if (!empty($rulebookHasRiskId)) {
            $query .= ' AND id = '.$rulebookHasRiskId;
        }
        
        $statement = $rulebookHasRiskDaoObj->getEntityManager()->getConnection()->prepare($query);
        
        if ($statement->execute()) {
            return true;
        } else {
            return false;
        }
        
    }
    
    public function deleteRulebook($ruleBookId, $userId){
        $rulebookDaoObj = $this->getRuleBookDaoService();
        $query = 'UPDATE rulebook SET delete_flag = 1 WHERE id = '.$ruleBookId.' AND user_id = '.$userId;
        $statement = $rulebookDaoObj->getEntityManager()->getConnection()->prepare($query);
        
        if ($statement->execute()) {
            return true;
        } else {
            return false;
        }
        
    }
    
    

    /*
     * get default rulebook
     */

    public function getRuleBookObj($ruleBookId)
    {
        $this->init();

        //$ruleBookDaoService = $this->rulebookDaoObj;        
        //$ruleBookDaoService = $this->getRuleBookDaoService();
        // \Doctrine\Common\Util\Debug::dump($ruleBookDaoService);exit;
        $ruleBookObject = $this->rulebookDaoObj->read($ruleBookId);


        return $ruleBookObject;
    }

    /*
     * get risk has job function
     */

    public function getRiskHasJobFunctionObj($rulebookHasRiskId)
    {
        $impode = implode(",", $rulebookHasRiskId);
        $riskHasJobFunctionDaoService = $this->riskHasJobFunctionObj;
        //$riskHasJobFunctionDaoService = $this->getRiskHasJobFunctionDaoService();
        $query = "select rhjf.* from risk_has_job_function rhjf                
                  WHERE rhjf.risk_id in ($impode) AND rhjf.delete_flag = 0";
        $statement = $riskHasJobFunctionDaoService->getEntityManager()->getConnection()->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll();

        return $data;
    }

    public function getRuleBookHasRiskObj($ruleBookId)
    {
        $params = array();
        $rulebookHasRiskDaoService = $this->rulebookHasRiskDaoObj;
        //$rulebookHasRiskDaoService = $this->getRulebookHasRiskDaoService();
        $query = "select rhr.* from rulebook_has_risk rhr                
                  WHERE rhr.rulebook_id = '$ruleBookId' AND rhr.delete_flag = 0";

        $statement = $rulebookHasRiskDaoService->getEntityManager()->getConnection()->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll();
        return $data;        
        //return $rulebookHasRiskObject;
    }

    public function getJobFunctionHasTransactionObj($jobFunctionId)
    {
        $impode = implode(",", $jobFunctionId);
        $jobFunctionHasTransactionObj = $this->jobFunctionHasTransactionObj;
        //$jobFunctionHasTransactionObj = $this->getJobFunctionHasTransactionsDaoService();
        $query = "select jfht.* from job_function_has_transaction jfht                
                  WHERE jfht.job_function_id in($impode) AND jfht.delete_flag = 0";
        $statement = $jobFunctionHasTransactionObj->getEntityManager()->getConnection()->prepare($query);
        $statement->execute();
        $data = $statement->fetchAll();

        return $data;
    }

    public function newRulebookRow($ruleBookObject, $postData, $userId)
    {
        $rulebookArray = array();
        $rulebookDaoObj = $this->getRuleBookDaoService();
        
        //$rulebookDaoObj = $this->rulebookDaoObj;
        $rulebookArray['copied_from_rulebook_id'] = $ruleBookObject->getId();
        $rulebookArray['name'] = $postData['rb_name'];        
        if (empty($postData['rb_description'])) {
            $rulebookArray['description'] = $ruleBookObject->getDescription();
        } else {
            $rulebookArray['description'] = $postData['rb_description'];
        }
        $rulebookArray['user_id'] = $userId;
        $rulebookArray['created_dt_tm'] = date('Y-m-d H:i:s');
        $rulebookArray['updated_dt_tm'] = date('Y-m-d H:i:s');
        $rulebookArray['delete_flag'] = 0;
        $insertedRulebook = $rulebookDaoObj->createUpdateEntity($rulebookArray);
        return $insertedRulebook;
    }

    public function copyRulebookHasRisk($values, $inputArray)
    {
        $rulebookHasRiskArray = array();
        $rulebookHasRiskDaoObj = $this->rulebookHasRiskDaoObj;
        $query = "insert into rulebook_has_risk(risk_id, rulebook_id, created_dt_tm, updated_dt_tm, delete_flag)VALUES$values";
        $statement = $rulebookHasRiskDaoObj->getEntityManager()->getConnection()->prepare($query);
        $statement->execute();
        $resultArray = array();
        foreach ($inputArray as $array) {

            $explode = explode(",", $array);
            $query1 = "SELECT id FROM rulebook_has_risk  where risk_id = '$explode[1]' and rulebook_id = '$explode[2]'";
            $statement1 = $rulebookHasRiskDaoObj->getEntityManager()->getConnection()->prepare($query1);
            $statement1->execute();
            $data = $statement1->fetchAll();

            $resultArray[$explode[0]] = $data[0]['id'];
        }
        return $resultArray;
    }

    public function copyRiskHasJobFunction($values, $inputArray)
    {
        $riskHasJobFunctionObj = $this->riskHasJobFunctionObj;

        $query = "insert into risk_has_job_function
                     (risk_id, job_function_id, original_risk_id, original_function_id, created_dt_tm, updated_dt_tm, delete_flag)
                 VALUES$values";
        $statement = $riskHasJobFunctionObj->getEntityManager()->getConnection()->prepare($query);
        $statement->execute();
        $resultArray = array();

        foreach ($inputArray as $array) {
            $explode = explode(",", $array);
            $query1 = "SELECT id FROM risk_has_job_function  where risk_id = '$explode[1]' and job_function_id = '$explode[2]'";
            $statement1 = $riskHasJobFunctionObj->getEntityManager()->getConnection()->prepare($query1);
            $statement1->execute($params);
            $data = $statement1->fetchAll();
            $resultArray[$explode[0]] = $data[0]['id'];
        }

        return $resultArray;
    }

    public function copyJobFunctionHasTransaction($values)
    {
        $jobFunctionHasTransactionObj = $this->jobFunctionHasTransactionObj;

        $query = "insert into job_function_has_transaction
                     (job_function_id, transaction_id, original_function_id, original_transaction_id, created_dt_tm, updated_dt_tm, delete_flag)
                  VALUES$values";

        $statement = $jobFunctionHasTransactionObj->getEntityManager()->getConnection()->prepare($query);
        $statement->execute();
        return $jobFunctionHasTransactionObj->getEntityManager()->getConnection()->lastInsertId();
    }

    public function rulebookNameValidation($rulebookName, $userId)
    {
        if (empty($rulebookName)) {
            $response = \Application\Model\Utility::getResponseArray('fail', 'Please enter rulebook name');
            return $response;
        } else {
            //$rulebookDaoObj = $this->rulebookDaoObj;
            $rulebookDaoObj = $this->getRuleBookDaoService();
            $query = "select rb from Application\Entity\Rulebook rb                
            WHERE LOWER(rb.name) = :rulebookname and rb.userId IN (:userId) AND rb.deleteFlag = 0";
            $queryResult = $rulebookDaoObj->getEntityManager()
                    ->createQuery($query);
            $queryResult->setParameter("rulebookname", strtolower($rulebookName));
            $queryResult->setParameter("userId", array(0, $userId));

            $ruleBookResult = $queryResult->getResult();
            if (count($ruleBookResult)) {
                $response = \Application\Model\Utility::getResponseArray('fail', 'Rulebook name aready exists');
                return $response;
            }
            return 'success';
        }
    }

    public function createRulebookCopy($postData, $userId)
    {
        $rulebookHasRiskArray = array();
        $ruleBookObject = $this->getRuleBookObj($postData['rb_id']);
        $copyError = 0;
        $validationResponse = $this->rulebookNameValidation($postData['rb_name'], $userId);
        if ($validationResponse == 'success') {
            if (is_object($ruleBookObject)) {
                $insertedRulebook = $this->newRulebookRow($ruleBookObject, $postData, $userId);
            } else {
                $response = \Application\Model\Utility::getResponseArray('fail', 'Copy was unsuccessful');
                $copyError = 1;
            }

            if (is_object($insertedRulebook)) {

                $rulebookHasRiskArray = $this->getRuleBookHasRiskObj($postData['rb_id']);

                $ruleHasRiskIDString = '';
                $ruleBookHasRiskRiskIdRulebookIdArray = array();

                foreach ($rulebookHasRiskArray as $rulebookHasRiskData) {

                    $riskId = $rulebookHasRiskData['risk_id'];
                    $ruleBookId = $insertedRulebook->getId();
                    $date = date('Y-m-d H:i:s');

                    if ($ruleHasRiskIDString != "") {
                        $ruleHasRiskIDString.= ",";
                    }
                    $ruleBookHasRiskRiskIdRulebookIdArray[] = $rulebookHasRiskData['id'] . "," . $riskId . "," . $ruleBookId;
                    $ruleHasRiskIDString.= "('$riskId', '$ruleBookId', '$date', '$date', 0)";
                }

                $newRulebookRiskIdArray = $this->copyRulebookHasRisk($ruleHasRiskIDString, $ruleBookHasRiskRiskIdRulebookIdArray);
                $flipNewRulebookRiskIdArray = array_flip($newRulebookRiskIdArray);

                $riskHasJobFunctionArray = $this->getRiskHasJobFunctionObj($flipNewRulebookRiskIdArray);

                $secondTempArray = array();
                $riskHasJobFunctionIdString = '';
                $riskHasJobFunctionIdRiskIdJobFunctionIdArray = array();

                foreach ($riskHasJobFunctionArray as $loopArray) {

                    $riskId = $newRulebookRiskIdArray[$loopArray['risk_id']];
                    $jobFunctionId = $loopArray['job_function_id'];
                    $originalRiskId = $loopArray['original_risk_id'];
                    $originalFunctionId = $loopArray['original_function_id'];
                    $date = date('Y-m-d H:i:s');

                    if ($riskHasJobFunctionIdString != "") {
                        $riskHasJobFunctionIdString.= ",";
                    }
                    $riskHasJobFunctionIdRiskIdJobFunctionIdArray[] = $loopArray['id'] . "," . $riskId . "," . $jobFunctionId;
                    $riskHasJobFunctionIdString.= "('$riskId', '$jobFunctionId','$originalRiskId', '$originalFunctionId', '$date', '$date', 0)";
                }
                $riskHasJobFunctionIdArray = $this->copyRiskHasJobFunction($riskHasJobFunctionIdString, $riskHasJobFunctionIdRiskIdJobFunctionIdArray);

                $flipRiskHasJobFunctionIdArray = array_flip($riskHasJobFunctionIdArray);
                $jobFunctionHasTransactionArray = $this->getJobFunctionHasTransactionObj($flipRiskHasJobFunctionIdArray);

                $jobFunctionHasTransactionIdString = '';

                foreach ($jobFunctionHasTransactionArray as $iterateArray) {

                    $jobFunctionId = $riskHasJobFunctionIdArray[$iterateArray['job_function_id']];
                    $transactionId = $iterateArray['transaction_id'];
                    $originalJobFunctionId = $iterateArray['original_function_id'];
                    $originalTransactionId = $iterateArray['original_transaction_id'];
                    $date = date('Y-m-d H:i:s');

                    if ($jobFunctionHasTransactionIdString != "") {
                        $jobFunctionHasTransactionIdString.= ",";
                    }
                    $jobFunctionHasTransactionIdString.= "('$jobFunctionId', '$transactionId', '$originalJobFunctionId', '$originalTransactionId', '$date', '$date', 0)";
                }
                $functionTransactionId = $this->copyJobFunctionHasTransaction($jobFunctionHasTransactionIdString);
                $response = \Application\Model\Utility::getResponseArray('success', 'Copied successfully');
            } else {
                $response = \Application\Model\Utility::getResponseArray('fail', 'Copy was unsuccessful');
                $copyError = 1;
            }
            if ($copyError == 0) {
                $response = \Application\Model\Utility::getResponseArray('success', 'Copied successfully');
            }
            return $response;
        } else {
            return $validationResponse;
        }
    }

    /*
     * Upload excel data into db
     */

    public function uploadRuleBookExcelInToDb($userObject, $inputFileArray)
    {
        /* Check: is file name is empty */
        if (empty($inputFileArray["name"])) {
            $response['status'] = 'fail';
            $response['errorMessage'] = array("Please upload the file");
            return $response;
        }

        $extensionArray = explode('.', $inputFileArray["name"]);
        $extension = $extensionArray[count($extensionArray) - 1];
        $allowedExtensionArray = array('xlsx', 'xls');
        if (!in_array($extension, $allowedExtensionArray)) {
            $response['status'] = 'fail';
            $response['errorMessage'] = array("Invalid file, Only .xlsx or .xls allowed");
            return $response;
        }
        
        
        ini_set('max_execution_time', 0);
        $maxSize = Constant::MAX_UPLOAD_RULE_BOOK_SIZE;

        if (isset($inputFileArray)) {
            if ($inputFileArray["error"] > 0) {
                $responseArray['status'] = 'fail';
                $responseArray['errorMessage'] = array('Not able to upload excel file');
                return $responseArray;
            } else {
                /* check: file size limit: 5mb */
                if ($inputFileArray['size'] >= $maxSize) {
                    $response['status'] = 'fail';
                    $response['errorMessage'] = array("Can't upload more then 5mb file");
                    return $response;
                }

                move_uploaded_file($inputFileArray["tmp_name"], STORAGE_PATH . "/" . $inputFileArray["name"]);
            }
        }

        $excelService = $this->getExcelService();
        $ruleBookDaoService = $this->getRuleBookDaoService();
        $logService = $this->getLogService();


        //$inputFileName = STORAGE_PATH . "/default_rule_book.xlsx";
        $inputFileName = STORAGE_PATH . "/" . $inputFileArray["name"];
        /* Check: is file uploaded */
        if (!file_exists($inputFileName)) {
            $response['status'] = 'fail';
            $response['errorMessage'] = array("Excel file is not uploaded successfully");
            return $response;
        }

        $spreadsheetInfo = $excelService->getWorksheetInfo($inputFileName);
        

        $ruleBookTabOrder = array(
            Constant::RULEBOOK_SHEET_NAME, 
            Constant::RISK_SHEET_NAME, 
            Constant::RULEBOOK_HAS_RISK_SHEET_NAME, 
            Constant::FUNCTION_SHEET_NAME, 
            Constant::RISK_HAS_FUNCTION_SHEET_NAME, 
            Constant::TRANSACTION_SHEET_NAME, 
            Constant::FUNCTION_HAS_TRANSACTION_SHEET_NAME, 
        );
        /* sorting excel sheets as per given above order */
        usort($spreadsheetInfo, function ($a, $b) use ($ruleBookTabOrder) {
            $pos_a = array_search($a['worksheetName'], $ruleBookTabOrder);
            $pos_b = array_search($b['worksheetName'], $ruleBookTabOrder);            
            return $pos_a - $pos_b;
        });
        
        /* Server side validation as per wiki  */
        $validationResponse = $this->validateRuleBookUpload($inputFileName, $spreadsheetInfo, $excelService);

        if ($validationResponse['valid'] == false) {
            $response['status'] = 'fail';
            $response['errorMessage'] = $validationResponse['error'];
            return $response;
        }

        $options = $this->uploadOption();
        $entityManager = $this->getEntityManager();

        try {
            //Transaction begins
            $entityManager->getConnection()->beginTransaction();

            /*
             * @Todo: Need to optimize this code, Once functionality is working fine, Will optimize it
             */
            $userId = $userObject->getId();
            $i = 0;
            foreach ($spreadsheetInfo as $workSheet) {
                $rows = array();
                $rows = $excelService->readFileChunks($inputFileName, $workSheet, $options);

                switch ($i) {
                    case 0:
                        //rule book entry
                        // Removing headers 
                        unset($rows[0]);

                        /* Check: Duplicate rulebook name */
                        $ruleBookDaoService = $this->getRuleBookDaoService();
                        $ruleBookName = $rows[1][0];
                        $queryParamArray['userId'] = $userObject->getId();
                        $queryParamArray['name'] = $ruleBookName;
                        $entity = Constant::ENTITY_RULEBOOK;
                        $ruleBookObject = $ruleBookDaoService->getEntityByParameterList($queryParamArray, $entity);
                        if (is_object($ruleBookObject)) {
                            $response['status'] = 'fail';
                            $response['errorMessage'] = array('Uploaded file contains rulebook name which already exists for your account.');
                            return $response;
                        }
                        $queryParamArray['userId'] = 0;
                        $ruleBookObject = $ruleBookDaoService->getEntityByParameterList($queryParamArray, $entity);
                        if (is_object($ruleBookObject)) {
                            $response['status'] = 'fail';
                            $response['errorMessage'] = array('Uploaded file contains rulebook name which already exists for your account.');
                            return $response;
                        }
                        /* Entry into rule_book table */
                        $ruleBookId = $this->createRuleBookEntry($rows, $userObject->getId());
                        break;
                    case 1:
                        $this->createRiskEntry($rows, $ruleBookId, $userId);
                        break;
                    case 2:
                        $this->createRuleBookHasRiskEntry($rows, $ruleBookId, $userId);
                        break;
                    case 3:
                        $this->createJobFunctionEntry($rows, $ruleBookId, $userId);
                        break;
                    case 4:
                        $this->createRiskHasJobFunctionEntry($rows, $ruleBookId, $userId);
                        break;
                    case 5:
                        $this->createTransactionEntry($rows, $ruleBookId, $userId);
                        break;
                    case 6:
                        $this->createJobFunctionHasTransactionEntry($rows, $ruleBookId, $userId);
                        break;
                }

                $i++;
            }
            //Transaction commits
            $entityManager->getConnection()->commit();

            $responseArray['status'] = 'success';
            $responseArray['successMessage'] = "Excel file uploaded successfully";
        } catch (\Exception $exc) {
            //Transaction rollbacks
            $entityManager->getConnection()->rollBack();
            $errorMessage = $exc->getMessage();
            $responseArray['status'] = 'fail';
            $responseArray['errorMessage'] = array($errorMessage);
            $errorMessage = "With in catch block: - " . $errorMessage;
            $logService->debug($errorMessage);
        }
        return $responseArray;
    }

    /*
     * Entry into risk table
     */

    public function createRiskEntry($rows, $ruleBookId, $userId)
    {
        $logService = $this->getLogService();
        try {
            // Removing headers 
            unset($rows[0]);
            $riskDaoService = $this->getRiskDaoService();
            $i = 1;
            foreach ($rows as $row) {
                
                
                $queryParamArray['userId'] = $userId;
                $queryParamArray['sapRiskId'] = $row[0];
                $entity = Constant::ENTITY_RISK;
                $riskObject = $riskDaoService->getEntityByParameterList($queryParamArray, $entity);
                
                if (is_object($riskObject)) {
                    continue;
                }
                
                $inputArray['rule_book_id'] = $ruleBookId;
                $inputArray['sap_risk_id'] = $row[0];
                $inputArray['single_function_risk'] = $row[1];
                $inputArray['risk_category'] = $row[2];
                $inputArray['risk_level'] = $row[3];
                $inputArray['description'] = $row[4];
                $inputArray['is_default_risk'] = 0;
                $inputArray['user_id'] = $userId;
                $riskObject = $riskDaoService->exchangeArray($inputArray);
                $this->getEntityManager()->persist($riskObject);

                if ($i == Constant::MAX_PERSIST_VALUE) {
                    $i = 1;
                    $this->getEntityManager()->flush();
                }
                $i++;
            }
            $this->getEntityManager()->flush();
        } catch (\Exception $exc) {
            $errorMessage = $exc->getMessage();
            $errorMessage = "With in catch block: - " . $errorMessage;
            $logService->debug($errorMessage);
            throw new \Exception('Whoops! something went wrong in Risk tab');
        }
    }

    /*
     * Entry  into rulebook_has_risk table
     */

    public function createRuleBookHasRiskEntry($rows, $ruleBookId, $userId)
    {
        $logService = $this->getLogService();
        try {
            // Removing headers 
            unset($rows[0]);
            $riskDaoService = $this->getRiskDaoService();
            $ruleBookHasRiskDaoService = $this->getRulebookHasRiskDaoService();
            $i = 1;
            foreach ($rows as $row) {
                $queryParamArray['sapRiskId'] = $row[1];
                $queryParamArray['userId'] = $userId;
                $entity = Constant::ENTITY_RISK;
                $riskObject = $riskDaoService->getEntityByParameterList($queryParamArray, $entity);
                
                if(!is_object($riskObject)) {
                    $logService->debug("Issue while reading risk object in RuleBookRisk tab: Sap Risk Id - {$row[1]}");
                    throw new \Exception('Whoops something went wrong in Rulebook Risk tab');
                }
                
                $inputArray['rulebook_id'] = $ruleBookId;
                $inputArray['risk_id'] = $riskObject->getId();
                $ruleBookHasRiskObject = $ruleBookHasRiskDaoService->exchangeArray($inputArray);
                
                if(!is_object($ruleBookHasRiskObject)) {
                    $logService->debug("Issue while reading Rule book has Risk object in RuleBookRisk tab: Sap Risk Id - {$row[1]}");
                    throw new \Exception('Whoops something went wrong in Rulebook Risk tab');
                }
                
                $this->getEntityManager()->persist($ruleBookHasRiskObject);

                if ($i == Constant::MAX_PERSIST_VALUE) {
                    $i = 1;
                    $this->getEntityManager()->flush();
                }
                $i++;
            }
            $this->getEntityManager()->flush();
        } catch (\Exception $exc) {
            $errorMessage = $exc->getMessage();
            $errorMessage = "With in catch block: - " . $errorMessage;
            $logService->debug($errorMessage);
            throw new \Exception('Whoops something went wrong in Rulebook Risk tab');
        }
    }

    /*
     * Entry into job function table
     */

    public function createJobFunctionEntry($rows, $ruleBookId, $userId)
    {
        $logService = $this->getLogService();
        try {
            unset($rows[0]);
            $jobFunctionDaoService = $this->getJobFunctionDaoService();
            $i = 1;
            foreach ($rows as $row) {
                                               
                //$queryParamArray['sapJobFunctionId'] = $row[0];
                //$queryParamArray['userId'] = array("{$userId} OR is_default_job_function=1");               
                //isDefaultRuleBookAttributeExists($entityName, $userId, $field, $value,$defaultFieldName);
                //$jobFunctionObject = $jobFunctionDaoService->getEntityByParameterList($queryParamArray, $entity);
                
                $entity = Constant::ENTITY_JOB_FUNCTION;
                $field = "sapJobFunctionId";
                $value = $row[0];
                $defaultFieldName = 'isDefaultJobFunction = 1';
                $jobFunctionArray = $jobFunctionDaoService->isDefaultRuleBookAttributeExists($entity,
                        $userId, $field, $value, $defaultFieldName);
                
                if (count($jobFunctionArray)) {
                    continue;
                }
                
                $inputArray['rule_book_id'] = $ruleBookId;
                $inputArray['sap_job_function_id'] = $row[0];
                $inputArray['user_id'] = $userId;
                $inputArray['description'] = $row[1];
                $inputArray['is_default_job_function'] = 0;
                $jobFunctionObject = $jobFunctionDaoService->exchangeArray($inputArray);
                $this->getEntityManager()->persist($jobFunctionObject);

                if ($i == Constant::MAX_PERSIST_VALUE) {
                    $i = 1;
                    $this->getEntityManager()->flush();
                }
                $i++;
            }
            $this->getEntityManager()->flush();
        } catch (\Exception $exc) {
            $errorMessage = $exc->getMessage();
            $errorMessage = "With in catch block: - " . $errorMessage;
            $logService->debug($errorMessage);
            throw new \Exception('Whoops! something went wrong in Job function tab');
        }
    }

    /*
     * Creates risk has job function entry
     */

    public function createRiskHasJobFunctionEntry($rows, $ruleBookId, $userId)
    {
        $logService = $this->getLogService();
        try {
            unset($rows[0]);
            $jobFunctionDaoService = $this->getJobFunctionDaoService();
            $riskHasJobFunctionDaoService = $this->getRiskHasJobFunctionDaoService();
            $i = 1;
            foreach ($rows as $row) {
                unset($queryParamArray);
                $queryParamArray['sapRiskId'] = $row[0];
                $queryParamArray['userId'] = $userId;
                $entity = Constant::ENTITY_RISK;
                $riskObject = $jobFunctionDaoService->getEntityByParameterList($queryParamArray, $entity);
                
                if(!is_object($riskObject)) {
                    $logService->debug("Issue while reading risk object in RiskFunction tab: Sap Risk Id - {$row[0]}");
                    throw new \Exception('Whoops something went wrong in RiskFunction tab');
                }
                
                unset($queryParamArray);
                $queryParamArray['riskId'] = $riskObject->getId();
                $queryParamArray['rulebookId'] = $ruleBookId; //extra check
                $entity = Constant::ENTITY_RULE_BOOK_HAS_RISK;
                $ruleBookHasRiskObject = $jobFunctionDaoService->getEntityByParameterList($queryParamArray, $entity);
                
                if(!is_object($ruleBookHasRiskObject)) {
                    $logService->debug("Issue while reading Rulebook has risk object in RiskFunction tab: Sap Risk Id - {$row[0]}");
                    throw new \Exception('Whoops something went wrong in RiskFunction tab');
                }
                
                $ruleBookHasRiskId = $ruleBookHasRiskObject->getId();
                unset($queryParamArray);
//                $queryParamArray['sapJobFunctionId'] = $row[1];
//                $queryParamArray['userId'] = "{$userId} OR is_default_job_function=1";
//                $entity = Constant::ENTITY_JOB_FUNCTION;
//                $jobFunctionObject = $jobFunctionDaoService->getEntityByParameterList($queryParamArray, $entity);
                
                $entity = Constant::ENTITY_JOB_FUNCTION;
                $field = "sapJobFunctionId";
                $value = $row[1];
                $defaultFieldName = 'isDefaultJobFunction = 1';
                $jobFunctionArray = $jobFunctionDaoService->isDefaultRuleBookAttributeExists($entity,
                        $userId, $field, $value, $defaultFieldName);
                
                if (!count($jobFunctionArray)) {
                   $logService->debug("Issue while reading job_function object in RiskFunction tab: Sap jobfunct Id - {$row[1]}");
                    throw new \Exception('Whoops something went wrong in RiskFunction tab');
               
                }
                
//                if(!is_object($jobFunctionObject)) {
//                    $logService->debug("Issue while reading job_function object in RiskFunction tab: Sap jobfunct Id - {$row[1]}");
//                    throw new \Exception('Whoops something went wrong in RiskFunction tab');
//                }

                $inputArray['risk_id'] = $ruleBookHasRiskId;
                //$inputArray['job_function_id'] = $jobFunctionObject->getId();
                $inputArray['job_function_id'] = $jobFunctionArray[0]['id'];
                $inputArray['rulebook_id'] = $ruleBookId; //extra check
                $riskHasJobFunctionObject = $riskHasJobFunctionDaoService->exchangeArray($inputArray);
                $this->getEntityManager()->persist($riskHasJobFunctionObject);
                if ($i == Constant::MAX_PERSIST_VALUE) {
                    $i = 1;
                    $this->getEntityManager()->flush();
                }
                $i++;
            }
            $this->getEntityManager()->flush();
        } catch (\Exception $exc) {
            $errorMessage = $exc->getMessage();
            $errorMessage = "With in catch block: - " . $errorMessage;
            $logService->debug($errorMessage);
            throw new \Exception('Whoops! something went wrong in RiskFunction tab');
        }
    }

    /*
     * Entry into job function table
     */

    public function createTransactionEntry($rows, $ruleBookId, $userId)
    {

        $logService = $this->getLogService();
        try {
            // Removing headers 
            unset($rows[0]);
            $transactionDaoService = $this->getTransactionsDaoService();
            $i = 1;
            foreach ($rows as $row) {
                
                
                //$queryParamArray['sapTransactionId'] = $row[0];
                //$queryParamArray['userId'] = "{$userId} OR is_default_transaction=1";
                $entity = Constant::ENTITY_TRANSACTIONS;
                //$transactionObject = $transactionDaoService->getEntityByParameterList($queryParamArray, $entity);
                
                $field = "sapTransactionId";
                $value = $row[0];
                $defaultFieldName = 'isDefaultTransaction = 1';
                $transactionArray = $transactionDaoService->isDefaultRuleBookAttributeExists($entity,
                        $userId, $field, $value, $defaultFieldName);
                                
                if (count($transactionArray)) {
                    continue;
                }
                
                
                $inputArray['rule_book_id'] = $ruleBookId;
                $inputArray['sap_transaction_id'] = $row[0];
                $inputArray['description'] = $row[1];
                $inputArray['is_default_transaction'] = 0;
                $inputArray['user_id'] = $userId;
                $transactionObject = $transactionDaoService->exchangeArray($inputArray);
                $this->getEntityManager()->persist($transactionObject);
                if ($i == Constant::MAX_PERSIST_VALUE) {
                    $i = 1;
                    $this->getEntityManager()->flush();
                }
                $i++;
            }
            $this->getEntityManager()->flush();
        } catch (\Exception $exc) {
            $errorMessage = $exc->getMessage();
            $errorMessage = "With in catch block: - " . $errorMessage;
            $logService->debug($errorMessage);
            throw new \Exception('whoops something went wrong in Transaction tab');
        }
    }

    /*
     * Creates risk has job function entry
     */

    public function createJobFunctionHasTransactionEntry_old($rows, $ruleBookId, $userId)
    {

        $logService = $this->getLogService();
        try {
            // Removing headers 
            unset($rows[0]);
            $jobFunctionDaoService = $this->getJobFunctionDaoService();
            $transactionDaoService = $this->getTransactionsDaoService();
            $jobFunctionHasTransactionDaoService = $this->getJobFunctionHasTransactionsDaoService();
            $i = 1;
            foreach ($rows as $row) {
                unset($queryParamArray);
//                $queryParamArray['sapJobFunctionId'] = $row[0];
//                $queryParamArray['userId'] = "{$userId} OR is_default_job_function=1";
//                $entity = Constant::ENTITY_JOB_FUNCTION;
//                $jobFunctionObject = $jobFunctionDaoService->getEntityByParameterList($queryParamArray, $entity);
                
                
                $entity = Constant::ENTITY_JOB_FUNCTION;
                $field = "sapJobFunctionId";
                $value = $row[0];
                $defaultFieldName = 'isDefaultJobFunction = 1';
                $jobFunctionArray = $jobFunctionDaoService->isDefaultRuleBookAttributeExists($entity,
                        $userId, $field, $value, $defaultFieldName);
                
                if (!count($jobFunctionArray)) {
                   $logService->debug("Issue while reading job_function object in FunctionTransactions tab: Sap jobfunc Id - {$row[0]}");
                    throw new \Exception('Whoops something went wrong in FunctionTransactions tab');
                }
                
//                
//                if(!is_object($jobFunctionObject)) {
//                    $logService->debug("Issue while reading job_function object in FunctionTransactions tab: Sap jobfunc Id - {$row[0]}");
//                    throw new \Exception('Whoops something went wrong in FunctionTransactions tab');
//                }
                
                //$jobFunctionId = $jobFunctionObject->getId();
                $jobFunctionId = $jobFunctionArray[0]['id'];

                unset($queryParamArray);
                $queryParamArray['jobFunctionId'] = $jobFunctionId;
                $queryParamArray['rulebookId'] = $ruleBookId; //extra check
                $entity = Constant::ENTITY_RISK_HAS_JOB_FUNCTION;
                $riskHasJobFunctionObjectList = $jobFunctionDaoService->getEntityListByParameterList($queryParamArray, $entity);
                
                if(!is_object($riskHasJobFunctionObjectList)) {
                    $logService->debug("Issue while reading riskHasJobFunc object in FunctionTransactions tab: Sap transa Id - {$row[1]}");
                    //throw new \Exception('Whoops something went wrong in FunctionTransactions tab');
                }
                
                
                foreach($riskHasJobFunctionObjectList as $riskHasJobFunctionObject) {
                     $riskHasJobFunctionId = $riskHasJobFunctionObject->getId();
                }
                
                $riskHasJobFunctionId = $riskHasJobFunctionObject->getId();

                unset($queryParamArray);
//                $queryParamArray['sapTransactionId'] = $row[1];
//                $queryParamArray['userId'] = "{$userId} OR is_default_job_function=1";
//                $entity = Constant::ENTITY_TRANSACTIONS;
//                $transactionObject = $transactionDaoService->getEntityByParameterList($queryParamArray, $entity);
//
//                if(!is_object($transactionObject)) {
//                    $logService->debug("Issue while reading transaction object in FunctionTransactions tab: Sap transa Id - {$row[1]}");
//                    throw new \Exception('Whoops something went wrong in FunctionTransactions tab');
//                }
                
                $entity = Constant::ENTITY_TRANSACTIONS;
                $field = "sapTransactionId";
                $value = $row[1];
                $defaultFieldName = 'isDefaultTransaction = 1';
                $transactionArray = $jobFunctionDaoService->isDefaultRuleBookAttributeExists($entity,
                        $userId, $field, $value, $defaultFieldName);
                
                
                $inputArray['job_function_id'] = $riskHasJobFunctionId;
                //$inputArray['transaction_id'] = $transactionObject->getId();
                $inputArray['transaction_id'] = $transactionArray[0]['id'];;

                $jobFunctionHasTransactionObject = $jobFunctionHasTransactionDaoService->exchangeArray($inputArray);
                $this->getEntityManager()->persist($jobFunctionHasTransactionObject);
                if ($i == Constant::MAX_PERSIST_VALUE) {
                    $i = 1;
                    $this->getEntityManager()->flush();
                }
                $i++;
            }
            $this->getEntityManager()->flush();
        } catch (\Exception $exc) {
            $errorMessage = $exc->getMessage();
            $errorMessage = "With in catch block: - " . $errorMessage;
            $logService->debug($errorMessage);
            throw new \Exception('Whoops something went wrong in FunctionTransaction tab');
        }
    }
    
    
    
    public function createJobFunctionHasTransactionEntry($rows, $ruleBookId, $userId)
    {
         
        $jobFunctionHasTransactionDaoService = $this->getJobFunctionHasTransactionsDaoService();
        $jobFunctionDaoService = $this->getJobFunctionDaoService();
        $transactionDaoService = $this->getTransactionsDaoService();
        unset($rows[0]);
        $functionIdArray = array();
         foreach ($rows as $row) {
             $functionIdArray[$row[0]][] = $row[1];
         }
         $i = 1;
         foreach($functionIdArray as $functionIdKey => $functionIdValueArray) {
                                                    
            unset($queryParamArray);
            
             $entity = Constant::ENTITY_JOB_FUNCTION;
                $field = "sapJobFunctionId";
                $value = $functionIdKey;
                $defaultFieldName = 'isDefaultJobFunction = 1';
                $jobFunctionArray = $jobFunctionDaoService->isDefaultRuleBookAttributeExists($entity,
                        $userId, $field, $value, $defaultFieldName);
                
                if (!count($jobFunctionArray)) {
                   //$logService->debug("Issue while reading job_function object in FunctionTransactions tab: Sap jobfunc Id - {$row[0]}");
                    throw new \Exception('Whoops something went wrong in FunctionTransactions tab');
                }
            
            $queryParamArray['jobFunctionId'] =  $jobFunctionArray[0]['id'];
            $queryParamArray['rulebookId'] = $ruleBookId; //extra check
            $entity = Constant::ENTITY_RISK_HAS_JOB_FUNCTION;
            $riskHasJobFunctionObjectList = $jobFunctionDaoService->getEntityListByParameterList($queryParamArray, $entity);
            
            foreach($riskHasJobFunctionObjectList as $riskHasJobFunctionObject) {
                
                foreach ($functionIdValueArray as $functionIdValueKey) {
                                
                    $entity = Constant::ENTITY_TRANSACTIONS;
                    $field = "sapTransactionId";
                    $value = $functionIdValueKey;
                    $defaultFieldName = 'isDefaultTransaction = 1';
                    $transactionArray = $jobFunctionDaoService->isDefaultRuleBookAttributeExists($entity,
                            $userId, $field, $value, $defaultFieldName);    

                    $inputArray['job_function_id'] = $riskHasJobFunctionObject->getId();
                    $inputArray['transaction_id'] = $transactionArray[0]['id'];

                    $jobFunctionHasTransactionObject = $jobFunctionHasTransactionDaoService->exchangeArray($inputArray);
                    $this->getEntityManager()->persist($jobFunctionHasTransactionObject);
                    if ($i == Constant::MAX_PERSIST_VALUE) {
                        $i = 1;
                        $this->getEntityManager()->flush();
                    }
                    $i++;
                    
                }
                
            }                        
         }
         $this->getEntityManager()->flush();
    }


    /*
     * Upload rulebook validation
     */

    public function validateRuleBookUpload($inputFileName, $spreadsheetInfo, $excelService)
    {

        $options = $this->uploadOption();
        $result = array('valid' => true);
        $errorMessage = array();

        if (count($spreadsheetInfo) != Constant::NUMBER_OF_EXCEL_SHEET_FOR_UPLOAD_RULEBOOK) {
            $result['valid'] = false;
            $errorMessage['excel_sheet_count'] = "Number of excel tabs does not match";
        }
        $i = 0;
        foreach ($spreadsheetInfo as $workSheet) {
            $rows = array();
            $rows = $excelService->readFileChunks($inputFileName, $workSheet, $options);
            $sheetName = $workSheet['worksheetName'];
            $ruleBookSheetHeader = $rows[0];
            switch ($i) {
                case 0:
                    $errorMessage = $this->ruleBookValidation($sheetName, $ruleBookSheetHeader, $errorMessage);
                    break;
                case 1:
                    $errorMessage = $this->riskValidation($sheetName, $ruleBookSheetHeader, $errorMessage);
                    break;
                case 2:
                    $errorMessage = $this->ruleBookHasRiskValidation($sheetName, $ruleBookSheetHeader, $errorMessage);
                    break;
                case 3:
                    $errorMessage = $this->functionValidation($sheetName, $ruleBookSheetHeader, $errorMessage);
                    break;
                case 4:
                    $errorMessage = $this->riskHasFunctionValidation($sheetName, $ruleBookSheetHeader, $errorMessage);
                    break;
                case 5:
                    $errorMessage = $this->transactionValidation($sheetName, $ruleBookSheetHeader, $errorMessage);
                    break;
                case 6:
                    $errorMessage = $this->functionHasTransactionValidation($sheetName, $ruleBookSheetHeader, $errorMessage);
                    break;
            }

            $i++;
        }

        if (count($errorMessage) >= 1) {
            $result['valid'] = false;
        }

        $result['error'] = $errorMessage;
        return $result;
    }

    public function getRuleBookArray($userId, $rulebookId)
    {
        $rulebookDaoObj = $this->getRuleBookDaoService();
        $query = "SELECT name, description FROM rulebook WHERE user_id = " . $userId . " AND id = " . $rulebookId . " AND delete_flag = 0";
        $statement = $rulebookDaoObj->getEntityManager()->getConnection()->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function getRuleBookHasRiskArray($rulebookId)
    {
        $rulebookHasRiskDaoObj = $this->getRulebookHasRiskDaoService();
        $query = "SELECT rb.name, r.sap_risk_id,r.single_function_risk,r.risk_category,r.risk_level, r.description, rhr.id, r.is_default_risk FROM rulebook_has_risk rhr                  
                  LEFT JOIN risk r ON (rhr.risk_id = r.id)
                  LEFT JOIN rulebook rb ON (rhr.rulebook_id = rb.id)
                  WHERE rhr.rulebook_id = " . $rulebookId . " AND rhr.delete_flag = 0";        
        $query .= " ORDER BY rhr.id DESC";
        $statement = $rulebookHasRiskDaoObj->getEntityManager()->getConnection()->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function getRiskHasJobFunction($rulebookHasRiskId)
    {
        $riskHasJobFunctionObj = $this->getRiskHasJobFunctionDaoService();
        $rulebookHasRiskId = implode(',', $rulebookHasRiskId);
        $query = "SELECT r.sap_risk_id, jf.sap_job_function_id, jf.description, rhjf.id, jf.is_default_job_function FROM risk_has_job_function rhjf
                  LEFT JOIN rulebook_has_risk rhr ON (rhjf.risk_id = rhr.id)                  
                  LEFT JOIN risk r ON (rhr.risk_id = r.id)
                  LEFT JOIN job_function jf ON (rhjf.job_function_id = jf.id)
                  WHERE rhjf.risk_id IN (" . $rulebookHasRiskId . ") AND rhjf.delete_flag = 0";
        //$query .= " ORDER BY rhjf.updated_dt_tm";
        $statement = $riskHasJobFunctionObj->getEntityManager()->getConnection()->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function getjobFunctionHasTransaction($riskHasJobFunctionId)
    {
        $jobFunctionHasTransactionObj = $this->getJobFunctionHasTransactionsDaoService();
        $riskHasJobFunctionId = implode(',', $riskHasJobFunctionId);
        $query = "SELECT jf.sap_job_function_id, t.sap_transaction_id, t.description, jfht.id, t.is_default_transaction FROM job_function_has_transaction jfht                  
                  LEFT JOIN risk_has_job_function rhjf ON (rhjf.id = jfht.job_function_id)                  
                  LEFT JOIN job_function jf ON (jf.id = rhjf.job_function_id)                  
                  LEFT JOIN transactions t ON (t.id = jfht.transaction_id)
                  WHERE jfht.job_function_id IN (" . $riskHasJobFunctionId . ") AND jfht.delete_flag = 0";
        //$query .= " ORDER BY jfht.updated_dt_tm";
        $statement = $jobFunctionHasTransactionObj->getEntityManager()->getConnection()->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function downloadRuleBookExcelFromToDb($ruleBookId, $userObject)
    {
        $this->init();
        $excelService = $this->getExcelService();
        $currentExcel = $excelService->getPHPExcel();
        $userId = $userObject->getId();
        if ($ruleBookId == 1) {
            $userId = 0;
        }
        $rulebookArray = $this->getRuleBookArray($userId, $ruleBookId);
        if (!empty($rulebookArray)) {
            $rulebookName = $rulebookArray[0]['name'];
            $sheet1 = $currentExcel->getActiveSheet();
            $sheet1->setTitle(Constant::RULEBOOK_SHEET_NAME);
            $sheet1->setCellValue('a1', Constant::FIRST_COLUMN_NAME_IN_RULEBOOK_SHEET);
            $sheet1->setCellValue('b1', Constant::SECOND_COLUMN_NAME_IN_RULEBOOK_SHEET);
            $sheet1->fromArray($rulebookArray, ' ', 'A2');


            $rulebookHasRiskArray = $this->getRuleBookHasRiskArray($ruleBookId);
            $rulebookHasRiskArray = array_reverse($rulebookHasRiskArray);
            
            foreach ($rulebookHasRiskArray as $rulebookHasRisk) {

                if (!empty($rulebookHasRisk['name']) AND !empty($rulebookHasRisk['sap_risk_id'])) {
                    $rulebookHasRiskData[] = array($rulebookHasRisk['name'], $rulebookHasRisk['sap_risk_id']);
                }

                if (!empty($rulebookHasRisk['sap_risk_id']) AND !empty($rulebookHasRisk['description'])) {
                    $riskData[] = array(
                        $rulebookHasRisk['sap_risk_id'], 
                        $rulebookHasRisk['single_function_risk'], 
                        $rulebookHasRisk['risk_category'], 
                        $rulebookHasRisk['risk_level'], 
                        $rulebookHasRisk['description']
                        );
                }
                if (!empty($rulebookHasRisk['id'])) {
                    $rulebookHasRiskId[] = $rulebookHasRisk['id'];
                }
            }

            if (!empty($rulebookHasRiskData)) {
                $sheet2 = $currentExcel->createSheet();
                $sheet2->setTitle(Constant::RULEBOOK_HAS_RISK_SHEET_NAME);
                $sheet2->setCellValue('a1', Constant::FIRST_COLUMN_NAME_IN_RULEBOOK_HAS_RISK_SHEET);
                $sheet2->setCellValue('b1', Constant::SECOND_COLUMN_NAME_IN_RULEBOOK_HAS_RISK_SHEET);
                $sheet2->fromArray($rulebookHasRiskData, ' ', 'A2');
            }

            if (!empty($riskData)) {
                $sheet3 = $currentExcel->createSheet();
                $sheet3->setTitle(Constant::RISK_SHEET_NAME);
                $sheet3->setCellValue('a1', Constant::FIRST_COLUMN_NAME_IN_RISK_SHEET);
                $sheet3->setCellValue('b1', Constant::SECOND_COLUMN_NAME_IN_RISK_SHEET);
                $sheet3->setCellValue('c1', Constant::THIRD_COLUMN_NAME_IN_RISK_SHEET);
                $sheet3->setCellValue('d1', Constant::FOUR_COLUMN_NAME_IN_RISK_SHEET);
                $sheet3->setCellValue('e1', Constant::FIVE_COLUMN_NAME_IN_RISK_SHEET);
                
                $sheet3->fromArray($riskData, ' ', 'A2');
            }

            $riskHasJobFunctionArray = $this->getRiskHasJobFunction($rulebookHasRiskId);            
            foreach ($riskHasJobFunctionArray as $riskHasJobFunction) {
                if (array_filter($riskHasJobFunction)) {

                    if (!empty($riskHasJobFunction['sap_risk_id']) AND !empty($riskHasJobFunction['sap_job_function_id'])) {
                        $riskHasJobFunctionData[] = array($riskHasJobFunction['sap_risk_id'], $riskHasJobFunction['sap_job_function_id']);
                    }

                    if (!empty($riskHasJobFunction['sap_job_function_id']) AND !empty($riskHasJobFunction['description'])) {
                        $jobFunction[] = array($riskHasJobFunction['sap_job_function_id'], $riskHasJobFunction['description']);
                    }

                    if (!empty($riskHasJobFunction['id'])) {
                        $riskHasJobFunctionId[] = $riskHasJobFunction['id'];
                    }
                }
            }

            if (!empty($riskHasJobFunctionData)) {
                $sheet4 = $currentExcel->createSheet();
                $sheet4->setTitle(Constant::RISK_HAS_FUNCTION_SHEET_NAME);
                $sheet4->setCellValue('a1', Constant::FIRST_COLUMN_NAME_IN_RISK_HAS_FUNCTION_SHEET);
                $sheet4->setCellValue('b1', Constant::SECOND_COLUMN_NAME_IN_RISK_HAS_FUNCTION_SHEET);
                $sheet4->fromArray($riskHasJobFunctionData, ' ', 'A2');
            }

            if (!empty($jobFunction)) {
                $sheet5 = $currentExcel->createSheet();
                $sheet5->setTitle(Constant::FUNCTION_SHEET_NAME);
                $sheet5->setCellValue('a1', Constant::FIRST_COLUMN_NAME_IN_FUNCTION_SHEET);
                $sheet5->setCellValue('b1', Constant::SECOND_COLUMN_NAME_IN_FUNCTION_SHEET);
                $sheet5->fromArray($jobFunction, ' ', 'A2');
            }

            $jobFunctionHasTransactionArray = $this->getjobFunctionHasTransaction($riskHasJobFunctionId);
            foreach ($jobFunctionHasTransactionArray as $jobFunctionHasTransaction) {

                if (!empty($jobFunctionHasTransaction['sap_job_function_id']) AND !empty($jobFunctionHasTransaction['sap_transaction_id'])) {
                    $jobFunctionHasTransactionData[] = array($jobFunctionHasTransaction['sap_job_function_id'], $jobFunctionHasTransaction['sap_transaction_id']);
                }

                if (!empty($jobFunctionHasTransaction['sap_transaction_id']) AND !empty($jobFunctionHasTransaction['description'])) {
                    $Transactions[] = array($jobFunctionHasTransaction['sap_transaction_id'], $jobFunctionHasTransaction['description']);
                }
            }

            if (!empty($jobFunctionHasTransactionData)) {
                $sheet6 = $currentExcel->createSheet();
                $sheet6->setTitle(Constant::FUNCTION_HAS_TRANSACTION_SHEET_NAME);
                $sheet6->setCellValue('a1', Constant::FIRST_COLUMN_NAME_IN_FUNCTION_HAS_TRANSACTION_SHEET);
                $sheet6->setCellValue('b1', Constant::SECOND_COLUMN_NAME_IN_FUNCTION_HAS_TRANSACTION_SHEET);
                $sheet6->fromArray($jobFunctionHasTransactionData, ' ', 'A2');
            }

            if (!empty($Transactions)) {
                $sheet7 = $currentExcel->createSheet();
                $sheet7->setTitle(Constant::TRANSACTION_SHEET_NAME);
                $sheet7->setCellValue('a1', Constant::FIRST_COLUMN_NAME_IN_TRANSACTION_SHEET);
                $sheet7->setCellValue('b1', Constant::SECOND_COLUMN_NAME_IN_TRANSACTION_SHEET);
                $sheet7->fromArray($Transactions, ' ', 'A2');
            }

            $writer = $excelService->writeFile($currentExcel, 'Excel2007');
            $writer->save(STORAGE_PATH . '/' . $rulebookName . '.xlsx');
            $path = STORAGE_PATH . '/' . $rulebookName . '.xlsx';
            $command = chmod($path, 0777);

            header('Content-Description: File Transfer');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');           
            header('Content-Disposition: attachment; filename="'.basename($path).'"');
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($path));
            readfile($path);

            ob_clean();
            flush();
            
            //Delete the file
            unlink($path);
            
            exit();
        } else {
            $response = \Application\Model\Utility::getResponseArray('fail', 'Rulebook does not belong to this rulebook');
        }
        return $response;
    }

    public function compareString($firstString, $secondString)
    {
        $compareResponse = \Application\Model\Utility::caseInsensitiveStringCompare(
                        $firstString, $secondString);
        return $compareResponse;
    }

    /*
     * Rule book sheet validation
     */

    public function ruleBookValidation($sheetName, $ruleBookSheetHeader, $errorMessage)
    {

        $compareResponse = $this->compareString($sheetName, Constant::RULEBOOK_SHEET_NAME);

        if (!$compareResponse) {
            $result['valid'] = false;
            $errorMessage['rule_book_name'] = "Tab name for rule book tab does not match";
        }

        if (count($ruleBookSheetHeader) != Constant::NUM_OF_COLUMN_IN_RULEBOOK_SHEET) {
            $result['valid'] = false;
            $errorMessage['rule_book_count'] = "Number of column in rulebook tab does not match";
        }
        $compareResponse = $this->compareString($ruleBookSheetHeader[0], Constant::FIRST_COLUMN_NAME_IN_RULEBOOK_SHEET);

        if (!$compareResponse) {
            $result['valid'] = false;
            $errorMessage['rule_book_sheet_first_column'] = "First Column name in rule book tab does not match";
        }
        $compareResponse = $this->compareString($ruleBookSheetHeader[1], Constant::SECOND_COLUMN_NAME_IN_RULEBOOK_SHEET);

        if (!$compareResponse) {
            $result['valid'] = false;
            $errorMessage['rule_book_sheet_second_column'] = "Second Column name in rule book tab does not match";
        }

        return $errorMessage;
    }

    /*
     * Risk sheet validation
     */

    public function riskValidation($sheetName, $ruleBookSheetHeader, $errorMessage)
    {

        $compareResponse = $this->compareString($sheetName, Constant::RISK_SHEET_NAME);

        if (!$compareResponse) {
            $result['valid'] = false;
            $errorMessage['risk_book_name'] = "Tab name for risk tab does not match";
        }

        if (count($ruleBookSheetHeader) != Constant::NUM_OF_COLUMN_IN_RISK_SHEET) {
            $result['valid'] = false;
            $errorMessage['risk_count'] = "Number of column in risk tab does not match";
        }
        $compareResponse = $this->compareString($ruleBookSheetHeader[0], Constant::FIRST_COLUMN_NAME_IN_RISK_SHEET);
        if (!$compareResponse) {
            $result['valid'] = false;
            $errorMessage['risk_sheet_first_column'] = "First Column name in risk tab does not match";
        }

        $compareResponse = $this->compareString($ruleBookSheetHeader[1], Constant::SECOND_COLUMN_NAME_IN_RISK_SHEET);
        if (!$compareResponse) {
            $result['valid'] = false;
            $errorMessage['risk_sheet_second_column'] = "Second Column name in risk tab does not match";
        }

        $compareResponse = $this->compareString($ruleBookSheetHeader[2], Constant::THIRD_COLUMN_NAME_IN_RISK_SHEET);
        if (!$compareResponse) {
            $result['valid'] = false;
            $errorMessage['risk_sheet_third_column'] = "Third Column name in risk tab does not match";
        }

        $compareResponse = $this->compareString($ruleBookSheetHeader[3], Constant::FOUR_COLUMN_NAME_IN_RISK_SHEET);
        if (!$compareResponse) {
            $result['valid'] = false;
            $errorMessage['risk_sheet_four_column'] = "Fourth Column name in risk tab does not match";
        }

        $compareResponse = $this->compareString($ruleBookSheetHeader[4], Constant::FIVE_COLUMN_NAME_IN_RISK_SHEET);
        if (!$compareResponse) {
            $result['valid'] = false;
            $errorMessage['risk_sheet_five_column'] = "Fiveth Column name in risk tab does not match";
        }

        return $errorMessage;
    }

    /*
     * Rule book has risk sheet validation
     */

    public function ruleBookHasRiskValidation($sheetName, $ruleBookSheetHeader, $errorMessage)
    {

        $compareResponse = $this->compareString($sheetName, Constant::RULEBOOK_HAS_RISK_SHEET_NAME);

        if (!$compareResponse) {
            $result['valid'] = false;
            $errorMessage['rule_book_has_risk_name'] = "Tab name for rule book has risk tab does not match";
        }

        if (count($ruleBookSheetHeader) != Constant::NUM_OF_COLUMN_IN_RULEBOOK_HAS_RISK_SHEET) {
            $result['valid'] = false;
            $errorMessage['rule_book_has_risk_count'] = "Number of column in rule book has risk tab does not match";
        }
        $compareResponse = $this->compareString($ruleBookSheetHeader[0], Constant::FIRST_COLUMN_NAME_IN_RULEBOOK_HAS_RISK_SHEET);

        if (!$compareResponse) {
            $result['valid'] = false;
            $errorMessage['rule_book_has_risk_sheet_first_column'] = "First Column name in rule book has risk tab does not match";
        }
        $compareResponse = $this->compareString($ruleBookSheetHeader[1], Constant::SECOND_COLUMN_NAME_IN_RULEBOOK_HAS_RISK_SHEET);

        if (!$compareResponse) {
            $result['valid'] = false;
            $errorMessage['rule_book_has_risk_sheet_second_column'] = "Second Column name in rule book has risk tab does not match";
        }

        return $errorMessage;
    }

    /*
     * Function sheet validation
     */

    public function functionValidation($sheetName, $ruleBookSheetHeader, $errorMessage)
    {

        $compareResponse = $this->compareString($sheetName, Constant::FUNCTION_SHEET_NAME);

        if (!$compareResponse) {
            $result['valid'] = false;
            $errorMessage['function_name'] = "Tab name for function tab does not match";
        }

        if (count($ruleBookSheetHeader) != Constant::NUM_OF_COLUMN_IN_FUNCTION_SHEET) {
            $result['valid'] = false;
            $errorMessage['function_count'] = "Number of column in function tab does not match";
        }
        $compareResponse = $this->compareString($ruleBookSheetHeader[0], Constant::FIRST_COLUMN_NAME_IN_FUNCTION_SHEET);

        if (!$compareResponse) {
            $result['valid'] = false;
            $errorMessage['function_first_column'] = "First Column name in function tab does not match";
        }
        $compareResponse = $this->compareString($ruleBookSheetHeader[1], Constant::SECOND_COLUMN_NAME_IN_FUNCTION_SHEET);

        if (!$compareResponse) {
            $result['valid'] = false;
            $errorMessage['function_second_column'] = "Second Column name in function tab does not match";
        }

        return $errorMessage;
    }

    /*
     * Function sheet validation
     */

    public function riskHasFunctionValidation($sheetName, $ruleBookSheetHeader, $errorMessage)
    {

        $compareResponse = $this->compareString($sheetName, Constant::RISK_HAS_FUNCTION_SHEET_NAME);

        if (!$compareResponse) {
            $result['valid'] = false;
            $errorMessage['risk_has_function_name'] = "Tab name for risk has function tab does not match";
        }

        if (count($ruleBookSheetHeader) != Constant::NUM_OF_COLUMN_IN_RISK_HAS_FUNCTION_SHEET) {
            $result['valid'] = false;
            $errorMessage['risk_has_function_count'] = "Number of column in risk has function tab does not match";
        }
        $compareResponse = $this->compareString($ruleBookSheetHeader[0], Constant::FIRST_COLUMN_NAME_IN_RISK_HAS_FUNCTION_SHEET);

        if (!$compareResponse) {
            $result['valid'] = false;
            $errorMessage['risk_has_function_first_column'] = "First Column name in risk has function tab does not match";
        }
        $compareResponse = $this->compareString($ruleBookSheetHeader[1], Constant::SECOND_COLUMN_NAME_IN_RISK_HAS_FUNCTION_SHEET);

        if (!$compareResponse) {
            $result['valid'] = false;
            $errorMessage['risk_has_function_second_column'] = "Second Column name in risk has function tab does not match";
        }

        return $errorMessage;
    }

    /*
     * Transaction sheet validation
     */

    public function transactionValidation($sheetName, $ruleBookSheetHeader, $errorMessage)
    {

        $compareResponse = $this->compareString($sheetName, Constant::TRANSACTION_SHEET_NAME);

        if (!$compareResponse) {
            $result['valid'] = false;
            $errorMessage['transactions_name'] = "Tab name for transactions tab does not match";
        }

        if (count($ruleBookSheetHeader) != Constant::NUM_OF_COLUMN_IN_TRANSACTION_SHEET) {
            $result['valid'] = false;
            $errorMessage['transactions_count'] = "Number of column in transactions tab does not match";
        }
        $compareResponse = $this->compareString($ruleBookSheetHeader[0], Constant::FIRST_COLUMN_NAME_IN_TRANSACTION_SHEET);

        if (!$compareResponse) {
            $result['valid'] = false;
            $errorMessage['transactions_first_column'] = "First Column name in transactions tab does not match";
        }
        $compareResponse = $this->compareString($ruleBookSheetHeader[1], Constant::SECOND_COLUMN_NAME_IN_TRANSACTION_SHEET);

        if (!$compareResponse) {
            $result['valid'] = false;
            $errorMessage['transactions_second_column'] = "Second Column name in transactions tab does not match";
        }

        return $errorMessage;
    }

    /*
     * Function has transaction sheet validation
     */

    public function functionHasTransactionValidation($sheetName, $ruleBookSheetHeader, $errorMessage)
    {

        $compareResponse = $this->compareString($sheetName, Constant::FUNCTION_HAS_TRANSACTION_SHEET_NAME);

        if (!$compareResponse) {
            $result['valid'] = false;
            $errorMessage['function_has_transaction_name'] = "Tab name for function has transaction tab does not match";
        }

        if (count($ruleBookSheetHeader) != Constant::NUM_OF_COLUMN_IN_FUNCTION_HAS_TRANSACTION_SHEET) {
            $result['valid'] = false;
            $errorMessage['function_has_transaction_count'] = "Number of column in function has transaction tab does not match";
        }
        $compareResponse = $this->compareString($ruleBookSheetHeader[0], Constant::FIRST_COLUMN_NAME_IN_FUNCTION_HAS_TRANSACTION_SHEET);

        if (!$compareResponse) {
            $result['valid'] = false;
            $errorMessage['function_has_transaction_first_column'] = "First Column name in function has transaction tab does not match";
        }
        $compareResponse = $this->compareString($ruleBookSheetHeader[1], Constant::SECOND_COLUMN_NAME_IN_FUNCTION_HAS_TRANSACTION_SHEET);

        if (!$compareResponse) {
            $result['valid'] = false;
            $errorMessage['function_has_transaction_second_column'] = "Second Column name in function has transaction tab does not match";
        }

        return $errorMessage;
    }

    /*
     * upload excel file option
     */

    public function uploadOption()
    {
        $options = array('chunkSize' => 5000, 'readDataOnly' => true);
        return $options;
    }

    /*
     * create rule book entry
     */

    public function createRuleBookEntry($rows, $userId)
    {
        $logService = $this->getLogService();
        try {
            $ruleBookDaoService = $this->getRuleBookDaoService();
            $inputArray['name'] = $rows[1][0];
            $inputArray['user_id'] = $userId;
            $inputArray['description'] = $rows[1][1];
            $ruleBookObject = $ruleBookDaoService->createUpdateEntity($inputArray);
            $ruleBookId = $ruleBookObject->getId();
            return $ruleBookId;
        } catch (\Exception $exc) {
            $errorMessage = $exc->getMessage();
            $errorMessage = "With in catch block: - " . $errorMessage;
            $logService->debug($errorMessage);
            throw new \Exception('Whoops! something went wrong in Rulebook tab');
        }
    }   
    
    public function validateRisk($postData) {
        $riskDaoService = $this->getRiskDaoService();
        $duplicateRiskName = $riskDaoService->riskNameMatch($postData);
        if (empty($postData['risk_id'])) {            
            $response['status'] = 'fail';
            $response['riskName'] = 'Please enter RiskID';
        } elseif (empty($postData['risk_description'])) {            
            $response['status'] = 'fail';
            $response['riskDescription'] = 'Please enter Description';
        } elseif (!empty($duplicateRiskName)) {            
            $response['status'] = 'fail';
            $response['riskName'] = 'Risk already exists';
        } else {
            $response['status'] = 'success';            
        }
        return $response;
    }
    
    
    public function createRisk($postData){        
        $riskValidationResponse = $this->validateRisk($postData);
        
        if ($riskValidationResponse['status'] == 'fail') {
            return $riskValidationResponse;
        }
        
        $newRiskId = $this->addRisk($postData);
        if (!empty($newRiskId)) {
            $newRulebookHasRiskId = $this->addRulebookHasRisk($newRiskId, $postData['rulebook_id']);
            
            if (!empty($newRulebookHasRiskId)) { 
                $response = \Application\Model\Utility::getResponseArray('success', 'Add risk was successful');
            } else {
                $response = \Application\Model\Utility::getResponseArray('fail', 'Add risk was unsuccessful'); 
            }
        }
        return $response;
    }
    
    
    public function addRisk($postData){        
        $logService = $this->getLogService();
        try {
            $riskDaoService = $this->getRiskDaoService();            
            $inputArray['sap_risk_id'] = $postData['risk_id'];
            $inputArray['rule_book_id'] = $postData['rulebook_id'];
            $inputArray['single_function_risk'] = 'N';            
            $inputArray['description'] = $postData['risk_description'];            
            $inputArray['is_default_risk'] = 0;           
            $newRiskObject = $riskDaoService->createUpdateEntity($inputArray);            
            if (is_object($newRiskObject)) {
                return $newRiskObject->getId();
            } else {
                return false;
            }
        } catch (\Exception $exc) {
            $errorMessage = $exc->getMessage();
            $errorMessage = "With in catch block: - " . $errorMessage;
            $logService->debug($errorMessage);
            throw new \Exception('Whoops! something went wrong in Risk tab');
        }        
    } 
       
    
    public function addRulebookHasRisk($riskId, $rulebookId){        
        $logService = $this->getLogService();
        try {
            $ruleBookHasRiskDaoService = $this->getRulebookHasRiskDaoService();
            $inputArray['rulebook_id'] = $rulebookId;
            $inputArray['risk_id'] = $riskId;            
            $newRulebookHasRiskObject = $ruleBookHasRiskDaoService->createUpdateEntity($inputArray);            
            if (is_object($newRulebookHasRiskObject)) {
                return $newRulebookHasRiskObject->getId();
            } else {
                return false;
            }
        } catch (\Exception $exc) {
            $errorMessage = $exc->getMessage();
            $errorMessage = "With in catch block: - " . $errorMessage;
            $logService->debug($errorMessage);
            throw new \Exception('Whoops! something went wrong in Risk tab');
        }
    }
    
    public function validateJobFunction($postData) {                
        $errorString = '';
        $jobFunctionDaoService = $this->getJobFunctionDaoService();
        if (isset($postData['selected_job_function_list'])){
            foreach ($postData['selected_job_function_list'] as $key=>$value) {
                if (empty($key)){
                    $response['status'] = 'fail';
                    $response['jobFunctionName'] = 'One of the selected JobFunctionID is empty';
                    break;
                }

                $checkDuplicate['job_function_id'] = $key;
                $checkDuplicate['risk_id'] = $postData['risk_id'];
                $duplicateJobFunctionName = $jobFunctionDaoService->jobFunctionNameMatch($checkDuplicate);
                if (!empty($duplicateJobFunctionName)) {
                    $errorString .= $key.',';
                }            
            }

            if (!empty($errorString)){
                $errorString = rtrim($errorString, ',');
                $response['status'] = 'fail';
                $response['jobFunctionName'] = $errorString.' job function already exists';
            } else {
                $response['status'] = 'success';
            }
            
        } else {            
            
            $duplicateJobFunctionName = $jobFunctionDaoService->jobFunctionNameMatch($postData);

            if (empty($postData['job_function_id'])) {            
                $response['status'] = 'fail';
                $response['jobFunctionName'] = 'Please enter JobFunctionID';
            } elseif (empty($postData['job_function_description'])) {            
                $response['status'] = 'fail';
                $response['jobFunctionDescription'] = 'Please enter Description';
            } elseif (!empty($duplicateJobFunctionName)) {
                if ($duplicateJobFunctionName[0]['riskHasJobFunctionId'] != $postData['riskHasJobFunctionId']) {
                    $response['status'] = 'fail';
                    $response['jobFunctionName'] = 'Job Function already exists';
                }
            } else {
                $response['status'] = 'success';            
            }
        }
        
        return $response;
    }
    
    public function createJobFunction($postData, $userId){
        
        $errorString = '';
        $error = '';
        $jobFunctionValidationResponse = $this->validateJobFunction($postData);
        
        
        if ($jobFunctionValidationResponse['status'] == 'fail') {
            return $jobFunctionValidationResponse;
        }
        if (isset($postData['selected_job_function_list'])){
            foreach ($postData['selected_job_function_list'] as $key=>$value) {
                $inputArray['risk_id'] = $postData['risk_id'];                
                $inputArray['job_function_id'] = $key;
                $inputArray['job_function_description'] = $value;
                $inputArray['rulebook_id'] = $postData['rulebook_id'];
                
                $response = $this->jobFunctionAdditionUpdation($inputArray, $userId);
                if ($response['status'] == 'fail') {
                    $errorString .= $inputArray['job_function_id'].',';
                }
            }
            
            if (!empty($errorString)) {
                $errorString = rtrim($errorString, ',');
                $response = \Application\Model\Utility::getResponseArray('fail', 'Add of '.$errorString.' job Function was unsuccessful');
            } else {
                $response = \Application\Model\Utility::getResponseArray('success', 'Add job Function was successful');
            }
            
        } else {                               
            $response = $this->jobFunctionAdditionUpdation($postData, $userId);
                       
        }        
        return $response;
    }
    
    public function jobFunctionAdditionUpdation($postData, $userId) {        
        $transactionIdArray = array();
        $jobFunctionDaoService = $this->getJobFunctionDaoService();
        $defaultJobFunction = $jobFunctionDaoService->checkDefaultJobFunction($postData);
        $existingJobFunctionForUser = $jobFunctionDaoService->getExistingJobFunctionIdForUser($postData, $userId);
        
        if (!empty($defaultJobFunction)) {            
            $newRiskHasJobFunctionId = $this->addRiskHasJobFunction($defaultJobFunction[0]['job_function_id'], $postData['risk_id']);            
            if (!empty($newRiskHasJobFunctionId)) {
                foreach ($defaultJobFunction as $jobFunction) {
                    $newJobFunctionHasTransactionId = $this->addJobFunctionHasTransaction($jobFunction['transaction_id'], $newRiskHasJobFunctionId);
                    if (empty($newJobFunctionHasTransactionId)) {
                        $error = 'fail';
                    }
                }
            }
            
            if (isset($postData['edit_job_function'])) {
                /*$inputArray['job_function_id'] = $postData['edit_job_function'];
                $inputArray['riskHasJobFunction_id'] = $postData['riskHasJobFunctionId'];
                $inputArray['rulebook_id'] = $postData['rulebook_id'];                */
                $this->deleteJobFunction($postData['riskHasJobFunctionId']);                
            }
        } elseif ($postData['job_function_id'] == $postData['edit_job_function']) {
            $inputArray['job_function_id'] = $postData['edit_job_function'];
            $existingJobFunctionForUser = $jobFunctionDaoService->getExistingJobFunctionIdForUser($inputArray, $userId);                                        
            if (!empty($existingJobFunctionForUser)) {
                $jobFunctionDaoService = $this->getJobFunctionDaoService();   
                $queryParamArray['id'] = $existingJobFunctionForUser[0]['id'];
                $entity = Constant::ENTITY_JOB_FUNCTION;
                $jobFunctionObject = $jobFunctionDaoService->getEntityByParameterList($queryParamArray, $entity);                        
                if (is_object($jobFunctionObject)){
                    $postData['job_function_obj'] = $jobFunctionObject;
                }
            }
            $newJobFunctionId = $this->addUpdateJobFunction($postData, $userId);
            if (empty($newJobFunctionId)) {
                $error = 'fail';
            }
            
        } elseif (!empty($existingJobFunctionForUser)) {
            
            $jobId = $existingJobFunctionForUser[0]['id'];
            $newRiskHasJobFunctionId = $this->addRiskHasJobFunction($jobId, $postData['risk_id']);

            /*$transactionForExistingJobFunction = $jobFunctionDaoService->getTransactionForExistingJobFunctionId($jobId, $userId);                

            if (!empty($transactionForExistingJobFunction)) {                
                if (!empty($newRiskHasJobFunctionId)) {                        
                    foreach ($transactionForExistingJobFunction as $transaction) {                                                        
                        if (!in_array($transaction['transaction_id'], $transactionIdArray)) {                                
                            $newJobFunctionHasTransactionId = $this->addJobFunctionHasTransaction($transaction['transaction_id'], $newRiskHasJobFunctionId);
                            if (empty($newJobFunctionHasTransactionId)) {
                                $error = 'fail';
                            }
                        }
                        $transactionIdArray[] = $transaction['transaction_id'];
                    }                        
                }
            }*/

            if (isset($postData['edit_job_function'])) {
                /*$inputArray['job_function_id'] = $postData['edit_job_function'];
                $inputArray['riskHasJobFunction_id'] = $postData['riskHasJobFunctionId'];
                $inputArray['rulebook_id'] = $postData['rulebook_id'];*/                
                $this->deleteJobFunction($postData['riskHasJobFunctionId']);                
            }

        } else {                
            if (isset($postData['edit_job_function'])) {
                $inputArray['job_function_id'] = $postData['edit_job_function'];
                $existingJobFunctionForUser = $jobFunctionDaoService->getExistingJobFunctionIdForUser($inputArray, $userId);                                        
                if (!empty($existingJobFunctionForUser)) {
                    $jobFunctionDaoService = $this->getJobFunctionDaoService();   
                    $queryParamArray['id'] = $existingJobFunctionForUser[0]['id'];
                    $entity = Constant::ENTITY_JOB_FUNCTION;
                    $jobFunctionObject = $jobFunctionDaoService->getEntityByParameterList($queryParamArray, $entity);                        
                    if (is_object($jobFunctionObject)){
                        $postData['job_function_obj'] = $jobFunctionObject;
                    }
                }
                $newJobFunctionId = $this->addUpdateJobFunction($postData, $userId);
                if (empty($newJobFunctionId)) {
                   $error = 'fail'; 
                }
                
            } else {

                $newJobFunctionId = $this->addUpdateJobFunction($postData, $userId);
                if (!empty($newJobFunctionId)) {
                    $newRiskHasJobFunctionId = $this->addRiskHasJobFunction($newJobFunctionId, $postData['risk_id']);

                    if (empty($newRiskHasJobFunctionId)) {
                        $error = 'fail';
                    }
                }                    
            }            
        }
        if (empty($error)) {
            $response = \Application\Model\Utility::getResponseArray('success', 'Add job Function was successful');
        } else {
            $response = \Application\Model\Utility::getResponseArray('fail', 'Add job Function was unsuccessful'); 
        } 
        
        return $response;
    }
    
    
    public function addUpdateJobFunction($postData, $userId){        
        $logService = $this->getLogService();
        try {            
            $jobFunctionDaoService = $this->getJobFunctionDaoService();                        
            $inputArray['rule_book_id'] = $postData['rulebook_id'];
            $inputArray['sap_job_function_id'] = $postData['job_function_id'];
            $inputArray['description'] = $postData['job_function_description'];
            $inputArray['is_default_job_function'] = 0;
            $inputArray['user_id'] = $userId;
            
            if (isset($postData['job_function_obj'])) {
                $newJobFunctionObject = $jobFunctionDaoService->createUpdateEntity($inputArray, $postData['job_function_obj']);            
            } else {
                $newJobFunctionObject = $jobFunctionDaoService->createUpdateEntity($inputArray);            
            }
            
            if (is_object($newJobFunctionObject)) {
                return $newJobFunctionObject->getId();
            } else {
                return false;
            }
        } catch (\Exception $exc) {
            $errorMessage = $exc->getMessage();
            $errorMessage = "With in catch block: - " . $errorMessage;
            $logService->debug($errorMessage);
            throw new \Exception($errorMessage);
        }
    }
    
    
    public function addRiskHasJobFunction($jobFunctionId, $riskId){        
        $logService = $this->getLogService();
        try {
            $riskHasJobFunctionObj = $this->getRiskHasJobFunctionDaoService();
            $inputArray['job_function_id'] = $jobFunctionId;
            $inputArray['risk_id'] = $riskId;
            $newRiskHasJobFunctionObject = $riskHasJobFunctionObj->createUpdateEntity($inputArray);            
            if (is_object($newRiskHasJobFunctionObject)) {
                return $newRiskHasJobFunctionObject->getId();
            } else {
                return false;
            }
        } catch (\Exception $exc) {
            $errorMessage = $exc->getMessage();
            $errorMessage = "With in catch block: - " . $errorMessage;
            $logService->debug($errorMessage);
            throw new \Exception($errorMessage);
        }
    }    
    
    public function validateTransaction($postData) {        
        $errorString = '';
        $transactionsDaoService = $this->getTransactionsDaoService();
        if (isset($postData['selected_transaction_list'])){
            foreach ($postData['selected_transaction_list'] as $key=>$value) {
                if (empty($key)){
                    $response['status'] = 'fail';
                    $response['jobFunctionName'] = 'One of the selected JobFunctionID is empty';
                    break;
                }
                
                $checkDuplicate['transaction_id'] = $key;
                $checkDuplicate['job_function_id'] = $postData['job_function_id'];
                $duplicateTransactionName = $transactionsDaoService->transactionNameMatch($checkDuplicate);
                if (!empty($duplicateTransactionName)) {
                    $errorString .= $key.',';
                }            
            }
            
            if (!empty($errorString)){
                $errorString = rtrim($errorString, ',');
                $response['status'] = 'fail';
                $response['transactionName'] = $errorString.' transaction already exists';
            } else {
                $response['status'] = 'success';
            }
            
        } else {   
            $duplicateTransactionName = $transactionsDaoService->transactionNameMatch($postData);            
            if (empty($postData['transaction_id'])) {            
                $response['status'] = 'fail';
                $response['transactionName'] = 'Please enter TransactionID';
            } elseif (empty($postData['transaction_description'])) {            
                $response['status'] = 'fail';
                $response['transactionDescription'] = 'Please enter Description';
            } elseif (!empty($duplicateTransactionName)) {            
                if ($duplicateTransactionName[0]['jobFunctionHasTransactionId'] != $postData['jobFunctionHasTransactionId']) {
                    $response['status'] = 'fail';
                    $response['transactionName'] = 'Transaction already exists';
                }
            } else {
                $response['status'] = 'success';            
            }            
        }
        
        return $response;
    }
    
    
    public function createTransaction($postData, $userId){        
        $transactionValidationResponse = $this->validateTransaction($postData);
        
        if ($transactionValidationResponse['status'] == 'fail') {
            return $transactionValidationResponse;
        }
        
        if (isset($postData['selected_transaction_list'])){
            foreach ($postData['selected_transaction_list'] as $key=>$value) {
                $inputArray['job_function_id'] = $postData['job_function_id'];
                $inputArray['transaction_id'] = $key;
                $inputArray['transaction_description'] = $value;
                $inputArray['rulebook_id'] = $postData['rulebook_id'];                
                
                $response = $this->transactionAdditionUpdation($inputArray, $userId);
                if ($response['status'] == 'fail') {
                    $errorString .= $inputArray['transaction_id'].',';
                }
            }
            
            if (!empty($errorString)) {
                $errorString = rtrim($errorString, ',');
                $response = \Application\Model\Utility::getResponseArray('fail', 'Add of '.$errorString.' transaction was unsuccessful');
            } else {
                $response = \Application\Model\Utility::getResponseArray('success', 'Add transaction was successful');
            }
            
        } else {        
            $response = $this->transactionAdditionUpdation($postData, $userId);        
        }
        return $response;
    }
    
    public function transactionAdditionUpdation($postData, $userId) {                
        $error = '';
        $transactionDaoService = $this->getTransactionsDaoService();
        $defaultTransaction = $transactionDaoService->checkDefaultTransaction($postData);
        $existingTransactionForUser = $transactionDaoService->getExistingTransactionIdForUser($postData, $userId);            
        
        if (!empty($defaultTransaction)) {
            
            $newJobFunctionHasTransactionId = $this->addJobFunctionHasTransaction($defaultTransaction[0]['id'], $postData['job_function_id']);                        
            if (empty($newJobFunctionHasTransactionId)) {
                $error = 'fail';
            }
            if (isset($postData['edit_transaction'])) {
                /*$inputArray['transaction_id'] = $postData['edit_transaction'];
                $inputArray['jobFunctionHasTransaction_id'] = $postData['jobFunctionHasTransactionId'];
                $inputArray['rulebook_id'] = $postData['rulebook_id'];*/
                $this->deleteTransaction($postData['jobFunctionHasTransactionId']);                
            }
            
        } elseif ($postData['transaction_id'] == $postData['edit_transaction']) {
            
            $inputArray['transaction_id'] = $postData['edit_transaction'];
            $existingTransactionForUser = $transactionDaoService->getExistingTransactionIdForUser($inputArray, $userId);                    
            if (!empty($existingTransactionForUser)) {                        
                $queryParamArray['id'] = $existingTransactionForUser[0]['id'];
                $entity = Constant::ENTITY_TRANSACTIONS;
                $transactionObject = $transactionDaoService->getEntityByParameterList($queryParamArray, $entity);                        
                if (is_object($transactionObject)){
                    $postData['transaction_obj'] = $transactionObject;
                }
            }
            $newTransactionId = $this->addUpdateTransaction($postData, $userId);
            if (empty($newTransactionId)) {
                $error = 'fail';
            }
            
        } elseif (!empty($existingTransactionForUser)) {
            
            $newJobFunctionHasTransactionId = $this->addJobFunctionHasTransaction($existingTransactionForUser[0]['id'], $postData['job_function_id']);                        
            if (empty($newJobFunctionHasTransactionId)) {
                $error = 'fail';
            }

            if (isset($postData['edit_transaction'])) {
                /*$inputArray['transaction_id'] = $postData['edit_transaction'];
                $inputArray['jobFunctionHasTransaction_id'] = $postData['jobFunctionHasTransactionId'];
                $inputArray['rulebook_id'] = $postData['rulebook_id'];*/                    
                $this->deleteTransaction($postData['jobFunctionHasTransactionId']);                
            }
            
        } else {
            
            if (isset($postData['edit_transaction'])) {
                $inputArray['transaction_id'] = $postData['edit_transaction'];
                $existingTransactionForUser = $transactionDaoService->getExistingTransactionIdForUser($inputArray, $userId);                    
                if (!empty($existingTransactionForUser)) {                        
                    $queryParamArray['id'] = $existingTransactionForUser[0]['id'];
                    $entity = Constant::ENTITY_TRANSACTIONS;
                    $transactionObject = $transactionDaoService->getEntityByParameterList($queryParamArray, $entity);                        
                    if (is_object($transactionObject)){
                        $postData['transaction_obj'] = $transactionObject;
                    }
                }
                $newTransactionId = $this->addUpdateTransaction($postData, $userId);
                if (empty($newTransactionId)) {
                    $error = 'fail';
                }
                
            } else {
                $newTransactionId = $this->addUpdateTransaction($postData, $userId);
                if (!empty($newTransactionId)) {
                    $newJobFunctionHasTransactionId = $this->addJobFunctionHasTransaction($newTransactionId, $postData['job_function_id']);                                            
                    if (empty($newJobFunctionHasTransactionId)) {
                        $error = 'fail';
                    }                    
                }
            }
            
        }
        
        if (empty($error)) {
            $response = \Application\Model\Utility::getResponseArray('success', 'Add transaction was successful');                       
        } else {
            $response = \Application\Model\Utility::getResponseArray('fail', 'Add transaction was successful');                      
        }
        
        return $response;
    }
    
    
    public function addUpdateTransaction($postData, $userId){        
        $logService = $this->getLogService();
        try {
            
            $transactionDaoService = $this->getTransactionsDaoService();
            $inputArray['rule_book_id'] = $postData['rulebook_id'];
            $inputArray['sap_transaction_id'] = $postData['transaction_id'];
            $inputArray['description'] = $postData['transaction_description'];
            $inputArray['is_default_transaction'] = 0;
            $inputArray['user_id'] = $userId;
            
            if (isset($postData['transaction_obj'])) {
                $newTransactionObject = $transactionDaoService->createUpdateEntity($inputArray, $postData['transaction_obj']);
            } else {
                $newTransactionObject = $transactionDaoService->createUpdateEntity($inputArray);
            }
            
            
            if (is_object($newTransactionObject)) {
                return $newTransactionObject->getId();
            } else {
                return false;
            }
        } catch (\Exception $exc) {
            $errorMessage = $exc->getMessage();
            $errorMessage = "With in catch block: - " . $errorMessage;
            $logService->debug($errorMessage);
            throw new \Exception($errorMessage);
        }
    }
    
    public function addJobFunctionHasTransaction($transactionId, $jobFunctionId){        
        $logService = $this->getLogService();
        try {
            $jobFunctionHasTransactionDaoService = $this->getJobFunctionHasTransactionsDaoService();
            $inputArray['job_function_id'] = $jobFunctionId;
            $inputArray['transaction_id'] = $transactionId;
            $newJobFunctionHasTransactionObject = $jobFunctionHasTransactionDaoService->createUpdateEntity($inputArray);            
            if (is_object($newJobFunctionHasTransactionObject)) {
                return $newJobFunctionHasTransactionObject->getId();
            } else {
                return false;
            }
        } catch (\Exception $exc) {
            $errorMessage = $exc->getMessage();
            $errorMessage = "With in catch block: - " . $errorMessage;
            $logService->debug($errorMessage);
            throw new \Exception($errorMessage);
        }
    }
    
    public function getDefaultJobFunction($userId){
        $count = 0;
        $jobFunctionDaoService = $this->getJobFunctionDaoService();
        $defaultJobFunctionArray = $jobFunctionDaoService->defaultJobFunction($userId);
        foreach ($defaultJobFunctionArray as $defaultJobFunction) {            
            if ($defaultJobFunction['is_default_job_function'] == 0) {
                $defaultJobFunctionArray[$count]['is_default'] = '- custom';
            } else {
                $defaultJobFunctionArray[$count]['is_default'] = '';
            }
            $count++;
        }        
        return $defaultJobFunctionArray;
    }
    
    public function getDefaultTransaction($userId){
        $count = 0;
        $transactionDaoService = $this->getTransactionsDaoService();
        $defaultTransactionArray = $transactionDaoService->defaultTransaction($userId);
        foreach ($defaultTransactionArray as $defaultTransaction) {
            if ($defaultTransaction['is_default_transaction'] == 0) {
                $defaultTransactionArray[$count]['is_default'] = '- custom';
            } else {
                $defaultTransactionArray[$count]['is_default'] = '';
            }
            $count++;
        }        
        return $defaultTransactionArray;
    }
    

    public function deleteRulebookHasRiskById($rulebookHasRiskId){
        $rulebookHasRiskDaoService = $this->getRulebookHasRiskDaoService();
        $rulebookHasRiskObj = $rulebookHasRiskDaoService->read($rulebookHasRiskId);
        $rulebookHasRiskObj->setDeleteFlag(1);
        $rulebookHasRiskObj = $rulebookHasRiskDaoService->persistFlush($rulebookHasRiskObj);
        if(is_object($rulebookHasRiskObj)){
            return true;
        }else{
            return false;
        }
        
    }
    
    
    public function deleteRisk($postData){        
        $deleteRulebookHasRisk = $this->deleteRulebookHasRiskById($postData['rulebookHasRisk_id']);

        if ($deleteRulebookHasRisk) {                        
            $riskHasJobFunctionArray = $this->getRiskHasJobFunction(array($postData['rulebookHasRisk_id']));            
            if (!empty($riskHasJobFunctionArray)) {                        
                foreach($riskHasJobFunctionArray as $riskHasJobFunction){
                    $riskHasJobFunctionId[] = $riskHasJobFunction['id'];
                }                
                if (!empty($riskHasJobFunctionId)) {
                    $deleteRiskHasJobFunction = $this->deleteRiskHasJobFunction(array($postData['rulebookHasRisk_id']));

                    if ($deleteRiskHasJobFunction) {
                        $deleteJobFunctionHasTransaction = $this->deleteJobFunctionHasTransaction($riskHasJobFunctionId);

                        if ($deleteJobFunctionHasTransaction) {
                            $response = \Application\Model\Utility::getResponseArray('success', 'Deleted successfully');
                        } else {
                            $response = \Application\Model\Utility::getResponseArray('fail', 'Delete was unsuccessful');
                        }

                    } else {
                        $response = \Application\Model\Utility::getResponseArray('fail', 'Delete was unsuccessful');
                    }

                }

            }

        } else {
            $response = \Application\Model\Utility::getResponseArray('fail', 'Delete was unsuccessful');
        }               
            
        
        if(empty($response)){
            $response = \Application\Model\Utility::getResponseArray('success', 'Deleted successfully');
        }
        
        return $response;
        
    }
    
    public function deleteRiskHasJobFunctionById($riskHasJobFunctionId){
        $riskHasJobFunctionDaoService = $this->getRiskHasJobFunctionDaoService();
        $riskHasJobFunctionObj = $riskHasJobFunctionDaoService->read($riskHasJobFunctionId);
        $riskHasJobFunctionObj->setDeleteFlag(1);
        $riskHasJobFunctionObj = $riskHasJobFunctionDaoService->persistFlush($riskHasJobFunctionObj);
        if(is_object($riskHasJobFunctionObj)){
            return true;
        }else{
            return false;
        }
        
    }
    
    
    /*public function deleteJobFunctionById($jobFunctionId){
        $jobFunctionDaoService = $this->getJobFunctionDaoService();
        $jobFunctionObj = $jobFunctionDaoService->read($jobFunctionId);
        $jobFunctionObj->setDeleteFlag(1);
        $jobFunctionObj = $jobFunctionDaoService->persistFlush($jobFunctionObj);
        if(is_object($jobFunctionObj)){
            return true;
        }else{
            return false;
        }
        
    }*/
    
    
    /*public function deleteTransactionById($transactionId){
        $transactionDaoService = $this->getTransactionsDaoService();
        $transactionObj = $transactionDaoService->read($transactionId);
        $transactionObj->setDeleteFlag(1);
        $transactionObj = $transactionDaoService->persistFlush($transactionObj);
        if(is_object($transactionObj)){
            return true;
        }else{
            return false;
        }
        
    }*/
    
    
    public function deleteJobFunction($riskHasJobFunctionId){        
        $jobFunctionDaoService = $this->getJobFunctionDaoService();       
        
        /*$existingJobFunctionForUser = $jobFunctionDaoService->getExistingJobFunctionIdForUser($postData, $userId);                        
        if (!empty($existingJobFunctionForUser[0]['id'])) {
            $deleteJobFunction = $this->deleteJobFunctionById($existingJobFunctionForUser[0]['id']);
        }*/
        
        $deleteRiskHasJobFunction = $this->deleteRiskHasJobFunctionById($riskHasJobFunctionId);
        if ($deleteRiskHasJobFunction) {
            $deleteJobFunctionHasTransaction = $this->deleteJobFunctionHasTransaction(array($riskHasJobFunctionId));
            
            if ($deleteJobFunctionHasTransaction) {
                $response = \Application\Model\Utility::getResponseArray('success', 'Deleted successfully');
            } else {
                $response = \Application\Model\Utility::getResponseArray('fail', 'Delete was unsuccessful');
            }

        } else {
            $response = \Application\Model\Utility::getResponseArray('fail', 'Delete was unsuccessful');
        }
        return $response;
    }
    
    public function deleteTransaction($jobFunctionHasTransactionId) {
        $transactionDaoService = $this->getTransactionsDaoService();       
        
        /*$existingTransactionForUser = $transactionDaoService->getExistingTransactionIdForUser($postData, $userId);                        
        if (!empty($existingTransactionForUser[0]['id'])) {
            $deleteTransaction = $this->deleteTransactionById($existingTransactionForUser[0]['id']);
        }*/
        
        $deleteRiskHasJobFunction = $this->deleteJobFunctionHasTransactionById($jobFunctionHasTransactionId);
        if ($deleteRiskHasJobFunction) {
            $response = \Application\Model\Utility::getResponseArray('success', 'Deleted successfully');
        } else {
            $response = \Application\Model\Utility::getResponseArray('fail', 'Delete was unsuccessful');
        }
        return $response;
    }
    
    
    
    public function deleteJobFunctionHasTransactionById($jobFunctionHasTransactionId){                
        $jobFunctionHasTransactionDaoService = $this->getJobFunctionHasTransactionsDaoService();
        $jobFunctionHasTransactionObj = $jobFunctionHasTransactionDaoService->read($jobFunctionHasTransactionId);        
        $jobFunctionHasTransactionObj->setDeleteFlag(1);
        $jobFunctionHasTransactionObj = $jobFunctionHasTransactionDaoService->persistFlush($jobFunctionHasTransactionObj);
        if(is_object($jobFunctionHasTransactionObj)){
            $response = true;
        }else{
            $response = false;
        }
        return $response;
    }
    
    /*
     * Edit risk
     */
    public function editRisk($postData) {
        
        $riskDaoService = $this->getRiskDaoService();   
        
        if (empty($postData['sap_id']) || empty($postData['sap_desc'])) {
            return false;
        }
        
        /* New entry in risk table */
        $inputArray['risk_id'] = $postData['sap_id'];
        $inputArray['rulebook_id'] = $postData['rule_book_id'];   
        $inputArray['risk_description'] = $postData['sap_desc'];   
        $newRiskId = $this->addRisk($inputArray);
        
        /* update mapping table with  new risk id */
        $queryParamArray['id'] = $postData['db_id'];             
        $entity = Constant::ENTITY_RULEBOOK_HAS_RISK;
        $ruleBookHasRiskObject = $riskDaoService->getEntityByParameterList($queryParamArray, $entity);
        $ruleBookHasRiskObject->setRiskId($newRiskId);
        $ruleBookHasRiskObject = $riskDaoService->persistFlush($ruleBookHasRiskObject);
        
        if (is_object($ruleBookHasRiskObject)) {
            return true;
        } 
        return false;
    }
    
    
    /*
     * Edit risk
     */
    public function editJobFunction($postData, $userId) {        
        
        $jobFunctionDaoService = $this->getJobFunctionDaoService();   
        
        if (empty($postData['sap_id']) || empty($postData['sap_desc'])) {
            return false;
        }                       
        
        /* New entry in risk table */
        $inputArray['risk_id'] = $postData['risk_id'];                
        $inputArray['job_function_id'] = $postData['sap_id'];
        $inputArray['job_function_description'] = $postData['sap_desc'];
        $inputArray['rulebook_id'] = $postData['rule_book_id'];        
        $inputArray['edit_job_function'] = $postData['edit_val'];        
        
        $tempArray = explode('_', $postData['db_id']);
        $id = $tempArray[count($tempArray) - 1];
                
        $inputArray['riskHasJobFunctionId'] = $id;        
        
        $jobFunctionValidationResponse = $this->validateJobFunction($inputArray);
        
        
        if ($jobFunctionValidationResponse['status'] == 'fail') {
            return $jobFunctionValidationResponse;
        }
        
        $response = $this->jobFunctionAdditionUpdation($inputArray, $userId);
        
        return $response;
    }
    
    
    /*
     * Edit transaction
     */
    
    public function editTransaction($postData, $userId) {
        
        $transactionDaoService = $this->getTransactionsDaoService(); 
        
        if (empty($postData['sap_id']) || empty($postData['sap_desc'])) {
            return false;
        }
        
        /* New entry in risk table */
        $inputArray['job_function_id'] = $postData['job_function_id'];
        $inputArray['transaction_id'] = $postData['sap_id'];        
        $inputArray['rulebook_id'] = $postData['rule_book_id'];   
        $inputArray['transaction_description'] = $postData['sap_desc'];   
        $inputArray['edit_transaction'] = $postData['edit_val'];                   
        
        $tempArray = explode('_', $postData['db_id']);
        $id= $tempArray[count($tempArray) - 1];
                
        $inputArray['jobFunctionHasTransactionId'] = $id;        
        $transactionValidationResponse = $this->validateTransaction($inputArray);
        
        
        if ($transactionValidationResponse['status'] == 'fail') {
            return $transactionValidationResponse;
        }
        
        $response = $this->transactionAdditionUpdation($inputArray, $userId);
        
        return $response;
    }
    
    
    public function addRulebook($postData, $userId, $rulebookObject = null){        
        $logService = $this->getLogService();
        try {
            $rulebookDaoService = $this->getRuleBookDaoService(); 
            $inputArray['name'] = $postData['sap_id'];            
            $inputArray['description'] = $postData['sap_desc'];            
            $inputArray['user_id'] = $userId;
            
            if (!empty($rulebookObject)){
                $rulebookObject = $rulebookDaoService->createUpdateEntity($inputArray, $rulebookObject);
            } else {
                $rulebookObject = $rulebookDaoService->createUpdateEntity($inputArray);
            }
                    
            if (is_object($rulebookObject)) {
                return $rulebookObject->getId();
            } else {
                return false;
            }
        } catch (\Exception $exc) {
            $errorMessage = $exc->getMessage();
            $errorMessage = "With in catch block: - " . $errorMessage;
            $logService->debug($errorMessage);
            throw new \Exception($errorMessage);
        }
    }    
    
    
    public function editRulebook($postData, $userObject) {
        
        $rulebookDaoService = $this->getRuleBookDaoService(); 
        $userId = $userObject->getId();
        
        if (empty($postData['sap_id']) || empty($postData['sap_desc'])) {
            return false;
        }
        
        /*Check for dupicate rulebook name */
        $validationResponse = $this->rulebookNameValidation($postData['sap_id'], $userId);        
        if ($validationResponse != 'success') {
            return $validationResponse;
        }
        
        /*get the row to be updated */
        $queryParamArray['id'] = $postData['rule_book_id'];
        $entity = Constant::ENTITY_RULEBOOK;
        $rulebookObject = $rulebookDaoService->getEntityByParameterList($queryParamArray, $entity);        
        if (is_object($rulebookObject)) {
            $updatedRulebookId = $this->addRulebook($postData, $userId, $rulebookObject);
        }        
        if(!empty($updatedRulebookId)) {
            $response = \Application\Model\Utility::getResponseArray('success', 'Rulebook has been updated successfully');
        }
        
        return $response;
    }
    
}
