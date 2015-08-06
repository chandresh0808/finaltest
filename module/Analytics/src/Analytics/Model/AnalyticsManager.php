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

namespace Analytics\Model;

use Application\Model\Constant as Constant;
use Zend\Session\Container;
use PHPExcel;
use PHPExcel_IOFactory;

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
class AnalyticsManager extends \Application\Model\AbstractCommonServiceMutator
{
    /*
     * Create analysis request entry
     * @param array $inputArray
     * 
     * @return object $analysisRequestDaoObject
     */

    public function createAnalysisReqeustEntry($inputDataArray)
    {

        $analysisRequestDaoService = $this->getAnalysisRequestDaoService();
        $analysisRequestDaoObject = $analysisRequestDaoService->createUpdateEntity($inputDataArray);
        return $analysisRequestDaoObject;
    }

    /*
     * Data for analysis report
     */

    public function dataForAnalysisReport($paramArray)
    {

        $analysisRequestDaoService = $this->getAnalysisRequestDaoService();

        //Sorting column mapping
        $sortColumnNameMap = array(0 => "e.extractName", 1 => "ar.fileCreatedDtTm", 2 => "ar.fileExpireDtTm");

        $inputParamArray['user_id'] = $paramArray['user_id'];
        $inputParamArray['status'] = 'Completed';
        $inputParamArray['sort_column'] = $sortColumnNameMap[$paramArray['iSortCol_0']];
        $inputParamArray['sort_order'] = $paramArray['sSortDir_0'];
        $inputParamArray['limit'] = $paramArray['iDisplayLength'];
        $inputParamArray['offset'] = $paramArray['iDisplayStart'];

        $completedAnalysisRequestResponse = $analysisRequestDaoService->searchAnalysisRequest($inputParamArray);

        $completedAnalysisRequestObject = $completedAnalysisRequestResponse['analysis_request_object'];
        $count = $completedAnalysisRequestResponse['total_count'];

        $inputArrayForDataTables = \Application\Model\Utility::customizeResultForDataTable($completedAnalysisRequestObject, $count);
        return $inputArrayForDataTables;
    }
    
    
    /*
     * Data for analysis report which are pending/deleted/failed
     */

    public function dataForAnalysisInQueue($paramArray)
    {

        $analysisRequestDaoService = $this->getAnalysisRequestDaoService();

        //Sorting column mapping
        $sortColumnNameMap = array(0 => "e.extractName", 1 => "ar.createdDtTm", 2 => "ar.status");

        $inputParamArray['user_id'] = $paramArray['user_id'];        
        $inputParamArray['sort_column'] = $sortColumnNameMap[$paramArray['iSortCol_0']];
        $inputParamArray['sort_order'] = $paramArray['sSortDir_0'];
        $inputParamArray['limit'] = $paramArray['iDisplayLength'];
        $inputParamArray['offset'] = $paramArray['iDisplayStart'];

        $completedAnalysisRequestResponse = $analysisRequestDaoService->searchAnalysisRequest($inputParamArray);

        $completedAnalysisRequestObject = array_reverse($completedAnalysisRequestResponse['analysis_request_object']);
        $count = $completedAnalysisRequestResponse['total_count'];

        $inputArrayForDataTables = \Application\Model\Utility::customizeResultForDataTable($completedAnalysisRequestObject, $count);
        return $inputArrayForDataTables;
    }

    /*
     * Gets credits availables
     * @TODO: Need to user this function where inside code is used
     */

    public function getCreditsAvailable($userObject)
    {

        $userHasPackageObjectList = $userObject->getUserHasPackageList();
        $totalPoints = 0;
        $creditPoints = 0;
        foreach ($userHasPackageObjectList as $userHasPackageObject) {
            $userCreditHistoryObject = $userHasPackageObject->getUserCreditHistory()->first();
            $totalPoints += $userCreditHistoryObject->getTotalCreditAnalysisPoints();
            $creditPoints += $userCreditHistoryObject->getCreditAnalysisPointsUsed();
        }

        $availablePoints = ($totalPoints - $creditPoints);
        
        if ($availablePoints < 0) {
            $availablePoints = 0;
        }
        
        return $availablePoints;
    }

    /*
     * Gets expire date value
     */

    public function getSystemParamValueUsingKey($key)
    {
        $systemParamService = $this->getSystemParamService();
        $systemParamValue = $systemParamService->getSystemParamValueByKey($key);
        return $systemParamValue;
    }

    /*
     * Extend analysis request expire date
     */

    public function extendAnalysisRequestExpireDate($postDataArray)
    {
        $analysisRequestId = $postDataArray['ar_id'];
        $enhanceExpireValue = $postDataArray['enhance_ev'];
        
        $analysisRequestDaoService = $this->getAnalysisRequestDaoService();
        $analysisRequestObject = $analysisRequestDaoService->read($analysisRequestId);
        $logService = $this->getLogService();

        try {

            if (is_object($analysisRequestObject)) {
                $expireDateObject = $analysisRequestObject->getFileExpireDtTm();
                $expireDate = date_format($expireDateObject, "Y-m-d H:i:s");
                $extendedExpireDate = date('Y-m-d H:i:s', strtotime("{$expireDate} +{$enhanceExpireValue} day"));
                $extendedExpireObject = date_create($extendedExpireDate);
                $analysisRequestObject->setFileExpireDtTm($extendedExpireObject);
                $analysisRequestObject = $analysisRequestDaoService->persistFlush($analysisRequestObject);
                if (is_object($analysisRequestObject)) {
                    $response = \Application\Model\Utility::getResponseArray('success', 'Expire date extended successfully');

                    /*
                     * Delte entries from notification table
                     */
                    $this->deleteNotificationLogEntryUsingRequestAnalysisId($analysisRequestId);
                } else {
                    $response = \Application\Model\Utility::getResponseArray('fail', 'Expire date does not extended successfully');
                }
            } else {
                $response = \Application\Model\Utility::getResponseArray('fail', 'Expire date does not extended successfully');
            }
        } catch (\Exception $exc) {
            $errorMessage = $exc->getMessage();
            $logService->debug($errorMessage);
            $response = \Application\Model\Utility::getResponseArray('fail', 'Expire date extended successfully');
        }

        return $response;
    }

    /*
     * Download analysis request
     */

    public function downloadAnalysisReport($postDataArray, $aws, $userObject)
    {

        try {
            $systemActivityDaoService = $this->getSystemActivityDaoService();  
            $analysisRequestId = $postDataArray['ar_id'];
            $analysisRequestDaoService = $this->getAnalysisRequestDaoService();
            $extractsDaoService = $this->getExtractsDaoService();
            $analysisRequestObject = $analysisRequestDaoService->read($analysisRequestId);
            $extractId = $analysisRequestObject->getExtractId();
            $extractObject = $extractsDaoService->read($extractId);

            $jobId = $extractObject->getJobId();
            $direcoryName = $jobId."_".$analysisRequestId;
            $extractName = $extractObject->getExtractName();

            $applicationEnv = getenv('APPLICATION_ENV');
            $s3BucketConfiguration = $this->getS3BucketConfiguration();
            $s3configArray = $s3BucketConfiguration[$applicationEnv];
                       
            $this->_downloadFolderFromS3Bucket($jobId, $aws, $s3configArray['bucket_name'], $analysisRequestId);

            $descPath = STORAGE_PATH . "/downloads/{$direcoryName}/Completed/{$direcoryName}";

            if (!is_dir($descPath)) {
                $response = \Application\Model\Utility::getResponseArray('fail', 'Not able to download, Please try again');
                return $response;
            }

            $anslysisRequestName = $analysisRequestObject->getAnalysisRequestName();     
            
            //$this->_makeFolderAsZip($jobId, $extractName, $analysisRequestId);
            $this->_convertCsvToExcel($jobId, $anslysisRequestName, $analysisRequestId);
            
            /* activity log start */                                    
            $code = Constant::ACTIVITY_CODE_DA;
            $userId =   $userObject->getId();
            $fullName = $userObject->getFirstName() . " " . $userObject->getLastName();
            $comment = "{$fullName} has downloaded analysis - {$anslysisRequestName}";                                                                                                        
            $systemActivityObject = $systemActivityDaoService->createSystemActivityLog($code, $userId, $comment);
            /* activity log end */
           
            
            $response = \Application\Model\Utility::getResponseArray('success', 'Downloaded successfully');

            $arReport = new Container('ar_report');
            $arReport->jobId = $jobId;
            $arReport->analysis_request_id = $analysisRequestId;
            $arReport->analysisRequestName = $anslysisRequestName;
        } catch (\Exception $exc) {
            $response = \Application\Model\Utility::getResponseArray('fail', 'Not able to download, Please try again');
        }
 
        return $response;
    }

    /*
     * Download folder from s3 bucket
     * @param int $jobId
     */

    private function _downloadFolderFromS3Bucket($jobId, $aws, $bucketName, $analysisRequestId)
    {

        $client = $aws->get('s3');
        $downloadPath = STORAGE_PATH . '/downloads/' . $jobId."_".$analysisRequestId;
        $bucket = $bucketName;
        exec('mkdir ' . $downloadPath);
        if (is_dir($downloadPath)) {
            chmod($downloadPath, 0777);
        }
        $source = 'Completed/' . $jobId."_".$analysisRequestId;
        $client->downloadBucket($downloadPath, $bucket, $source);
    }

    /*
     * make the folder zip
     */

    private function _makeFolderAsZip($jobId, $extractName, $analysisRequestId)
    { 
       
        $directoryPath = $jobId."_".$analysisRequestId;
        $descPath = STORAGE_PATH . "/downloads/{$directoryPath}/{$extractName}.zip";
        chdir(STORAGE_PATH . "/downloads/{$directoryPath}/Completed");
        exec("zip -r {$descPath}  {$directoryPath}");
    }
    
    /*
     * all the csv files are converted to single excel file
     */
    private function _convertCsvToExcel($jobId, $anslysisRequestName, $analysisRequestId)
    {
        $directoryName = $jobId."_".$analysisRequestId;
        $descPath = STORAGE_PATH . "/downloads/{$directoryName}/Completed/{$directoryName}";
        $files = scandir($descPath);
        $inputFileType = 'Excel2007';
        $count = 0;
        $objPHPExcelReader = PHPExcel_IOFactory::createReader('CSV');        
        
        foreach($files as $file) {
            if (strpos($file,'.csv') !== false) {
                $filePath = STORAGE_PATH . "/downloads/{$directoryName}/Completed/{$directoryName}/$file";
                $fileName = explode('.', $file);
                if ($count == 0) {                    
                    $objPHPExcelInit = $objPHPExcelReader->load($filePath);                    
                    $objPHPExcelInit->getActiveSheet()->setTitle($fileName[0]);
                } else {                    
                    $objPHPExcel = $objPHPExcelReader->load($filePath);
                    $objPHPExcel->getActiveSheet()->setTitle($fileName[0]);
                    $objPHPExcelInit->addExternalSheet($objPHPExcel->getActiveSheet());
                }    
                $count++;
            }
        }
        $objPHPExcelWriter = PHPExcel_IOFactory::createWriter($objPHPExcelInit,$inputFileType);
        $objPHPExcelWriter->save(STORAGE_PATH . "/downloads/" . $directoryName . "/" . $anslysisRequestName . ".xlsx");
        $path = STORAGE_PATH . "/downloads/" . $directoryName . "/" . $anslysisRequestName . ".xlsx";
        $command = chmod($path, 0777);        
    }
    
    
    /*
     * Download the consolidated excel file
     */
    public function downloadArExcel()
    {
        try {
            
            $arReport = new Container('ar_report');
            $jobId = $arReport->jobId;
            $analysisRequestName = $arReport->analysisRequestName.'.xlsx';
            
            $folderName = $jobId."_".$arReport->analysis_request_id;
            
            $filePath = STORAGE_PATH . "/downloads/{$folderName}/{$analysisRequestName}";
            header('Content-Description: File Transfer');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');           
            header('Content-Disposition: attachment; filename="'.basename($filePath).'"');
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);

            ob_clean();
            flush();
            
            //Delete the file
            $directoryPath = $jobId."_".$arReport->analysis_request_id;
            $folderPath = STORAGE_PATH . "/downloads/{$directoryPath}";
            exec("rm -rfv {$folderPath}");
            unset($arReport->jobId);
            unset($arReport->analysisRequestName);
            unset($arReport->analysis_request_id);
            
            exit();
            
        } catch (Exception $ex) {
            $response = \Application\Model\Utility::getResponseArray('fail', 'Not able to download, Please try again');
        }
        
        return $response;
    }    
    
    
    /*
     * Download analysis folder
     */
    public function downloadArZipFolder()
    {

        try {

            $arReport = new Container('ar_report');
            $jobId = $arReport->jobId;
            $extractName = $arReport->extractName.'.zip';
            
            $folderName = $jobId."_".$arReport->analysis_request_id;

            $zipname = STORAGE_PATH . "/downloads/{$folderName}/{$extractName}";
            $response = new \Zend\Http\Response\Stream();
            $response->setStream(fopen($zipname, 'r'));
            $response->setStatusCode(200);
            $headers = new \Zend\Http\Headers();
            $headers->addHeaderLine('Content-Type', 'application/zip')
                    ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $extractName)
                    ->addHeaderLine('Content-Length', filesize($zipname)); //echo $zipname;exit;
            $response->setHeaders($headers);

            //Delete the folder
            $directoryPath = $jobId."_".$arReport->analysis_request_id;
            $folderPath = STORAGE_PATH . "/downloads/{$directoryPath}";
            exec("rm -rfv {$folderPath}");
            unset($arReport->jobId);
            unset($arReport->extractName);
            unset($arReport->analysis_request_id);
        } catch (\Exception $exc) {
            $response = \Application\Model\Utility::getResponseArray('fail', 'Not able to download, Please try again');
        }

        return $response;
    }

    /*
     * Delete analysis request
     */

    public function deleteAnalysisRequest($postDataArray, $aws, $userObject, $isSystem)
    {

        try {
            $analysisRequestId = $postDataArray['ar_id'];
            $analysisRequestDaoService = $this->getAnalysisRequestDaoService();
            $analysisRequestObject = $analysisRequestDaoService->read($analysisRequestId);
            $extractsDaoService = $this->getExtractsDaoService();
            $systemActivityDaoService = $this->getSystemActivityDaoService();  

            if (is_object($analysisRequestObject)) {

                $extractId = $analysisRequestObject->getExtractId();
                $extractObject = $extractsDaoService->read($extractId);

                if (is_object($extractObject)) {

                    $jobId = $extractObject->getJobId();
                    $directoryName = $jobId."_".$analysisRequestId;
                    //$nowOfRows = $analysisRequestDaoService->isExtractAssociatedWithMoreThenOneAnalysisRequest($analysisRequestId, $extractId);
                    $applicationEnv = getenv('APPLICATION_ENV');
                    $s3BucketConfiguration = $this->getS3BucketConfiguration();
                    $s3configArray = $s3BucketConfiguration[$applicationEnv];
                    $completeAnalysisResponseFromS3 = $this->_deleteCompleteAnalysisFolderFromS3($directoryName, $aws, $s3configArray['bucket_name']);

                    if ($completeAnalysisResponseFromS3 >= 1) {

                        $nowOfRows = $analysisRequestDaoService->isExtractAssociatedWithMoreThenOneAnalysisRequest($analysisRequestId, $extractId);

                        if ($nowOfRows <= 0) {
                            $extractsAnalysisResponseFromS3 = $this->_deleteExtractsFolderFromS3($jobId, $aws, $s3configArray['bucket_name']);

                            if ($extractsAnalysisResponseFromS3 <= 0) {
                                $response = \Application\Model\Utility::getResponseArray('fail', 'Not able to delete extracts folder from S3 bucket');
                                return $response;
                            }

                            $extractObject->setDeleteFlag(1);
                            $extractObject = $analysisRequestDaoService->persistFlush($extractObject);

                            if (!is_object($extractObject)) {
                                $response = \Application\Model\Utility::getResponseArray('fail', 'Not able to delete extract from db, Please try again');
                                return $response;
                            } else {
                                /* activity log start */                                                      
                                if ($isSystem) {
                                    $extractName = $extractObject->getExtractName();     
                                    $code = Constant::ACTIVITY_CODE_EDA;
                                    $userId = 0;
                                    $comment = "System has deleted analysis request {$extractName}";
                                    $systemActivityObject = $systemActivityDaoService->createSystemActivityLog($code, $userId, $comment);
                                }                                                                                                         
                                
                                /* activity log end */
                            }
                        }

                        $analysisRequestObject->setDeleteFlag(1);
                        $analysisRequestObject = $analysisRequestDaoService->persistFlush($analysisRequestObject);
                        if (is_object($analysisRequestObject)) {
                            
                            /* activity log start */                        
                            $anslysisRequestName = $analysisRequestObject->getAnalysisRequestName();     
                            $code = Constant::ACTIVITY_CODE_ADU;
                            $userId = $userObject->getId();
                            $fullName = $userObject->getFirstName() . " " . $userObject->getLastName();
                            $comment = "{$fullName} has deleted analysis request {$anslysisRequestName}";
                            if ($isSystem) {
                                $code = Constant::ACTIVITY_CODE_ADA;
                                $userId = 0;
                                $comment = "System has deleted analysis request {$anslysisRequestName}";
                            }                                                                                                         
                            $systemActivityObject = $systemActivityDaoService->createSystemActivityLog($code, $userId, $comment);
                            /* activity log end */
                                                        
                            $response = \Application\Model\Utility::getResponseArray('success', 'Analysis request delete successfully');
                        } else {
                            $response = \Application\Model\Utility::getResponseArray('fail', 'Not able to delete, Please try again');
                        }
                    } else {
                        $response = \Application\Model\Utility::getResponseArray('fail', 'Not able to delete complete analysis folder from S3 bucket');
                    }
                }
            }
        } catch (\Exception $exc) {
            $response = \Application\Model\Utility::getResponseArray('fail', 'Not able to delete, Please try again');
        }

        return $response;
    }

    /*
     * Delete complete analysis folder from s3
     */

    private function _deleteCompleteAnalysisFolderFromS3($jobId, $aws, $bucketName)
    {
        $client = $aws->get('s3');
        $result = 0;
        if ($jobId && !is_null($jobId) && $jobId !="") {
            $source = 'Completed/' . $jobId;
            $result = $client->deleteMatchingObjects($bucketName, $source);
        }

        return $result;
    }

    /*
     * Delete extracts folder from s3
     */

    private function _deleteExtractsFolderFromS3($jobId, $aws, $bucketName)
    {
        $client = $aws->get('s3');
        $result = 0;
        if ($jobId && is_numeric($jobId)) {
            $source = 'requests/' . $jobId;
            $result = $client->deleteMatchingObjects($bucketName, $source);
        }
        return $result;
    }

    public function getAnalysisRequestName($userId)
    {
        $analysisRequestDaoService = $this->getAnalysisRequestDaoService();
        $queryParamArray['userId'] = $userId;
        $entity = Constant::ENTITY_ANALYSIS_REQUEST;
        $analysisRequestObject = $analysisRequestDaoService->getEntityListByParameterList($queryParamArray, $entity);
        if (is_array($analysisRequestObject)) {
            $analysisNames[] = 'Select Analysis Report';
            foreach ($analysisRequestObject as $analysisRequest) {
                $extractName = $analysisRequest->getExtractName();
                $analysisNames[$extractName] = $extractName;
            }
            return array_unique($analysisNames);
        }
        return false;
    }

    /*
     * Notify and delete analysis request
     */

    public function notifyAndDeleteAnalysisRequest($aws, $appBaseLink)
    {

        $analysisRequestDaoService = $this->getAnalysisRequestDaoService();
        $userDaoService = $this->getUserDaoService();
        $logService = $this->getLogService();

        $queryParamArray['status'] = Constant::ANALYSIS_REQUEST_COMPLETE_STATUS;
        $entity = Constant::ENTITY_ANALYSIS_REQUEST;
        $analysisRequestObjectList = $analysisRequestDaoService->getEntityListByParameterList($queryParamArray, $entity);
        unset($queryParamArray);

        $queryParamArray['paramKey'] = Constant::SP_AR_NOTEFY_FIRST;
        $entity = Constant::ENTITY_SYSTEM_PARAM;
        $systemParamObject = $analysisRequestDaoService->getEntityByParameterList($queryParamArray, $entity);
        $firstNotificationTime = $systemParamObject->getParamValue();

        $queryParamArray['paramKey'] = Constant::SP_AR_NOTEFY_SECOND;
        $entity = Constant::ENTITY_SYSTEM_PARAM;
        $systemParamObject = $analysisRequestDaoService->getEntityByParameterList($queryParamArray, $entity);
        $secondNotificationTime = $systemParamObject->getParamValue();

        $queryParamArray['paramKey'] = Constant::SP_AR_NOTIFY_THIRD;
        $entity = Constant::ENTITY_SYSTEM_PARAM;
        $systemParamObject = $analysisRequestDaoService->getEntityByParameterList($queryParamArray, $entity);
        $thirdNotificationTime = $systemParamObject->getParamValue();
        unset($queryParamArray);
        foreach ($analysisRequestObjectList as $analysisRequestObject) {


            if (is_object($analysisRequestObject)) {

                if (!is_object($analysisRequestObject->getFileExpireDtTm())) {
                    continue;
                }
                $expireDateTime = $analysisRequestObject->getFileExpireDtTm()->format('Y-m-d H:i:s');
                $expireDateTime = \Application\Model\Utility::convertUtcToEst($expireDateTime);
                $analysisRequestId = $analysisRequestObject->getId();
                $userId = $analysisRequestObject->getUserId();
                $userObject = $userDaoService->read($userId);

                $expireDateInEpoch = strtotime($expireDateTime);
                $currentEpochTime = \Application\Model\Utility::getCurrentEpochTime();
                $substractedValue = $expireDateInEpoch - $currentEpochTime;

                //send first notification & entry in notification table
                if ($substractedValue <= $firstNotificationTime && $substractedValue > $secondNotificationTime) {
                    $queryParamArray['analysisRequestId'] = $analysisRequestId;
                    $queryParamArray['systemParamKey'] = Constant::SP_AR_NOTEFY_FIRST;
                    $entity = Constant::ENTITY_NOTIFICATION_LOG;
                    $notificationLogObject = $analysisRequestDaoService->getEntityByParameterList($queryParamArray, $entity);

                    if (!is_object($notificationLogObject)) {
                        $expiredDay = "3 days";
                        $mailResponse = $this->sendAnalysisRequestNotificationMail($analysisRequestObject, $expiredDay, $userObject, $appBaseLink);

                        if ($mailResponse) {
                            $systemParamKey = Constant::SP_AR_NOTEFY_FIRST;
                            $this->createNotificationEntry($analysisRequestId, $systemParamKey);
                        }
                    }
                    // send second notification  & entry in notification table                                                                  
                } else if ($substractedValue <= $secondNotificationTime && $substractedValue >= $thirdNotificationTime) {

                    $queryParamArray['analysisRequestId'] = $analysisRequestId;
                    $queryParamArray['systemParamKey'] = Constant::SP_AR_NOTEFY_SECOND;
                    $entity = Constant::ENTITY_NOTIFICATION_LOG;
                    $notificationLogObject = $analysisRequestDaoService->getEntityByParameterList($queryParamArray, $entity);

                    if (!is_object($notificationLogObject)) {
                        $expiredDay = "a day";
                        $mailResponse = $this->sendAnalysisRequestNotificationMail($analysisRequestObject, $expiredDay, $userObject, $appBaseLink);

                        if ($mailResponse) {
                            $systemParamKey = Constant::SP_AR_NOTEFY_SECOND;
                            $this->createNotificationEntry($analysisRequestId, $systemParamKey);
                        }
                    }

                    // send third notification notification  & entry in notification table     
                } else if ($substractedValue <= $thirdNotificationTime && $substractedValue >= 0) {

                    $queryParamArray['analysisRequestId'] = $analysisRequestId;
                    $queryParamArray['systemParamKey'] = Constant::SP_AR_NOTIFY_THIRD;
                    $entity = Constant::ENTITY_NOTIFICATION_LOG;
                    $notificationLogObject = $analysisRequestDaoService->getEntityByParameterList($queryParamArray, $entity);

                    if (!is_object($notificationLogObject)) {
                        $expiredDay = "10 minutes";
                        $mailResponse = $this->sendAnalysisRequestNotificationMail($analysisRequestObject, $expiredDay, $userObject, $appBaseLink);

                        if ($mailResponse) {
                            $systemParamKey = Constant::SP_AR_NOTIFY_THIRD;
                            $this->createNotificationEntry($analysisRequestId, $systemParamKey);
                        }
                    }
                }

                if ($substractedValue <= 0) {
                    // hard delete from s3 and soft delete in db
                    $info = "Request analysis - id - {$analysisRequestId} deleting";
                    $logService->debug($info);
                    $inputArray['ar_id'] = $analysisRequestId;
                    $isSystem = true;
                    $this->deleteAnalysisRequest($inputArray, $aws, $userObject, $isSystem);
                    $this->deleteNotificationLogEntryUsingRequestAnalysisId($analysisRequestId);
                }
            }
        }
    }

    public function validateRequestSupport($requestSupportArray)
    {
        if (empty($requestSupportArray['email'])) {
            $response['status'] = 'fail';
            $response['email'] = 'Please enter email';
            return $response;
        } elseif (!filter_var($requestSupportArray['email'], FILTER_VALIDATE_EMAIL)) {
            $response['status'] = 'fail';
            $response['email'] = 'Please enter correct email';
            return $response;
        } elseif ($requestSupportArray['category'] == 0 AND empty($requestSupportArray['category'])) {
            $response['status'] = 'fail';
            $response['category'] = 'Please select category';
        } elseif (empty($requestSupportArray['analysis_request_details'])) {
            $response['status'] = 'fail';
            $response['analysis_request_details'] = 'Please enter the details';
        } else {
            $response['status'] = 'success';
        }
        return $response;
    }

    /*
     * send email
     * @param object $user
     * @param string $password
     */

    public function sendRequestSupportMail($userObject, $requestSupportArray)
    {
        $validationResponse = $this->validateRequestSupport($requestSupportArray);
        if ($validationResponse['status'] == 'fail') {
            return $validationResponse;
        }

        $template = $this->getMailTemplateService()->getRequestSupportTemplate($userObject, $requestSupportArray);

        $systemParamService = $this->getSystemParamService();
        $key = 'Support';        
        $toEmail = $systemParamService->getSystemParamValueByKey($key);

        $mailService = $this->getMailService();
        $mailService->sendMail($template, $toEmail, $toEmail);
        $response['status'] = 'success';
        return $response;
    }

    public function sendAnalysisRequestNotificationMail($analysisRequestObject, $expiredDay, $userObject, $appBaseLink)
    {
        $template = $this->getMailTemplateService()->getAnlysisRequestTemplate($analysisRequestObject, $expiredDay, $userObject, $appBaseLink);
        $systemParamService = $this->getSystemParamService();
        $key = 'notifications';
        $fromEmail = $systemParamService->getSystemParamValueByKey($key);
        $mailService = $this->getMailService();
        $response = $mailService->sendMail($template, $userObject->getUsername(), $fromEmail);
        return $response;
    }

    /*
     * Insert into notification table
     */

    public function createNotificationEntry($analysisRequestId, $systemParamKey)
    {

        $notificationLogDaoService = $this->getNotificationLogDaoService();
        $inputArray['analysis_request_id'] = $analysisRequestId;
        $inputArray['system_param_key'] = $systemParamKey;
        $inputArray['status'] = Constant::AR_MAIL_STATUS;
        $notificationLogOjbect = $notificationLogDaoService->createUpdateEntity($inputArray);
        return $notificationLogOjbect;
    }

    /*
     * Delete notification log table using analysis request id
     */

    public function deleteNotificationLogEntryUsingRequestAnalysisId($analysisRequestId)
    {

        $analysisRequestDaoService = $this->getAnalysisRequestDaoService();
        $queryParamArray['analysisRequestId'] = $analysisRequestId;
        $entity = Constant::ENTITY_NOTIFICATION_LOG;
        $notificationLogObjectList = $analysisRequestDaoService->getEntityListByParameterList($queryParamArray, $entity);
        foreach ($notificationLogObjectList as $notificationLogObject) {
            $notificationLogObject->setDeleteFlag(1);
            $this->getEntityManager()->persist($notificationLogObject);
        }
        $this->getEntityManager()->flush();
    }

    /*
     * Populate request support form
     */

    public function populateRequestSupportForm($requestSupportForm, $postDataArray)
    {

        if ($postDataArray['email']) {
            $requestSupportForm->get('email')->setValue($postDataArray['email']);
        }

        if ($postDataArray['category']) {
            $requestSupportForm->get('category')->setValue($postDataArray['category']);
        }

        if ($postDataArray['analysis_name']) {
            $requestSupportForm->get('analysis_name')->setValue($postDataArray['analysis_name']);
        }
        if ($postDataArray['analysis_request_details']) {
            $requestSupportForm->get('analysis_request_details')->setValue($postDataArray['analysis_request_details']);
        }
        return $requestSupportForm;
    }

    /*
     * Create analysis request entry
     * @param array $inputArray
     * 
     * @return object $analysisRequestDaoObject
     */

    public function createExtractsEntry($inputDataArray)
    {
        $extractsDaoService = $this->getExtractsDaoService();
        $systemActivityDaoService = $this->getSystemActivityDaoService();  
        $extractsObject = $extractsDaoService->createUpdateEntity($inputDataArray);

        if ($extractsObject) {
            $extractName = $inputDataArray['extract_file_name'];
            $code = Constant::ACTIVITY_CODE_USE;
            $userId = $inputDataArray['user_id'];            
            $queryParamArray['id'] = $userId;
            $entity = Constant::ENTITY_USER;
            $userObject = $systemActivityDaoService->getEntityByParameterList($queryParamArray, $entity);            
            $fullName = $userObject->getFirstName() . " " . $userObject->getLastName();
              $extracted = 'No';
            if ($inputDataArray['system_salt'] != 0) {
                $extracted = 'Yes';
            }
            
            $comment = "{$fullName} has uploaded extract {$extractName} & Extract is encrypted - $extracted";
            $systemActivityDaoService->createSystemActivityLog($code, $userId, $comment);
        }

        return $extractsObject;
    }

    /*
     * Data for analysis report
     */

    public function dataForExtracts($paramArray)
    {

        $extractsDaoService = $this->getExtractsDaoService();

        //Sorting column mapping
        $sortColumnNameMap = array(0 => "e.extractName", 1 => "e.userId", 2 => "e.createdDtTm");

        $inputParamArray['user_id'] = $paramArray['user_id'];
        $inputParamArray['sort_column'] = $sortColumnNameMap[$paramArray['iSortCol_0']];
        $inputParamArray['sort_order'] = $paramArray['sSortDir_0'];
        $inputParamArray['limit'] = $paramArray['iDisplayLength'];
        $inputParamArray['offset'] = $paramArray['iDisplayStart'];

        $extractsResponse = $extractsDaoService->searchExtracts($inputParamArray);

        $extractsObjectList = $extractsResponse['extract_object_list'];
        $count = $extractsResponse['total_count'];

        $inputArrayForDataTables = \Application\Model\Utility::customizeResultForDataTable($extractsObjectList, $count);
        return $inputArrayForDataTables;
    }

    /*
     * Delete analysis request
     */

    public function deleteExtracts($postDataArray, $aws, $userObject)
    {
        try {
            $extractsId = $postDataArray['extract_id'];
            $extractsDaoService = $this->getExtractsDaoService();
            $extractObject = $extractsDaoService->read($extractsId);

            if (is_object($extractObject)) {

                $jobId = $extractObject->getJobId();
                $applicationEnv = getenv('APPLICATION_ENV');
                $s3BucketConfiguration = $this->getS3BucketConfiguration();
                $s3configArray = $s3BucketConfiguration[$applicationEnv];
                $responseFromS3 = $this->_deleteExtractsFolderFromS3($jobId, $aws, $s3configArray['bucket_name']);

                if ($responseFromS3 >= 1) {
                    $extractObject->setDeleteFlag(1);
                    $extractObject = $extractsDaoService->persistFlush($extractObject);
                    if (is_object($extractObject)) {
                        
                         /* activity log start */
                        $systemActivityDaoService = $this->getSystemActivityDaoService();                        
                        $extractName = $extractObject->getExtractName();
                        $code = Constant::ACTIVITY_CODE_EDU;
                        $userId = $userObject->getId();
                        $fullName = $userObject->getFirstName() . " " . $userObject->getLastName();
                        $comment = "{$fullName} has deleted extract {$extractName}";
                        $systemActivityDaoService->createSystemActivityLog($code, $userId, $comment);
                        /* activity log end */
                        
                        
                        $response = \Application\Model\Utility::getResponseArray('success', 'Extract have been deleted successfully');
                    } else {
                        $response = \Application\Model\Utility::getResponseArray('fail', 'Not able to delete, please try again');
                    }
                } else {
                    $response = \Application\Model\Utility::getResponseArray('fail', 'Not able to delete extract folder from S3 bucket');
                }
            }
        } catch (\Exception $exc) {
            $response = \Application\Model\Utility::getResponseArray('fail', 'Not able to delete, please try again');
        }

        return $response;
    }

    /*
     * Analysis request 
     */

    public function analysisRequest($postDataArray, $userObject, $analysisRequestForm)
    {

        $analysisRequestDaoService = $this->getAnalysisRequestDaoService();
        $postDataArray['userId'] = $userObject->getId();
        $validationResponse = $this->validateAnalysisRequestData($postDataArray);

        $systemActivityDaoService = $this->getSystemActivityDaoService();

        if (false == $validationResponse['valid']) {
            $response['status'] = 'fail';
            $response['error_message'] = $validationResponse['error'];
            $response['analysis_request_form'] = $this->populateAnalysisRequestFormWithPostDate($analysisRequestForm, $postDataArray);
        } else {

            $userManagerService = $this->getUserManagerService();
            $userCreditHistoryArray = $userManagerService->getUserCreditHistory($userObject);

            $userId = $userObject->getId();
            $parentUserId = $userObject->getParentUserId();
            $inputArray['user_id'] = $userId;
            $inputArray['parent_user_id'] = $parentUserId;
            $inputArray['rulebook_id'] = $postDataArray['rule_book'];
            $inputArray['analysis_request_name'] = htmlspecialchars($postDataArray['analysis_name']);
            $inputArray['analysis_request_description'] = htmlspecialchars($postDataArray['analysis_details']);
            $inputArray['extract_id'] = $postDataArray['extracts'];
            $inputArray['is_free_trial_request'] = 1;

            if ($userCreditHistoryArray['remainingPoints'] > 0) {
                $inputArray['is_free_trial_request'] = 0;
            }


            /* activity log start */
            $code = Constant::ACTIVITY_CODE_RA;
            $fullName = $userObject->getFirstName() . " " . $userObject->getLastName();
            $comment = "{$fullName} has made analysis request {$inputArray['analysis_request_name']}";
            $systemActivityObject = $systemActivityDaoService->createSystemActivityLog($code, $userId, $comment);
            /* activity log end */


            $inputArray['status'] = Constant::ANALYSIS_REQUEST_PENDING_STATUS;
            $analysisRequestOjbect = $analysisRequestDaoService->createUpdateEntity($inputArray);
            if (is_object($analysisRequestOjbect)) {

                /* updates credits history table */
                if ($userCreditHistoryArray['remainingPoints'] > 0) {
                    $expiringUserHasPackage = $userObject->getUserHasPackageList();
                    $count = 0;
                    foreach ($expiringUserHasPackage as $expiringUserHasPackageObject){
                        if (is_object($expiringUserHasPackageObject) AND $count == 0) {
                            $userCreditHistoryObject = $expiringUserHasPackageObject->getUserCreditHistory()->first();                           
                            $usageAvailable = $userCreditHistoryObject->getTotalCreditAnalysisPoints() - $userCreditHistoryObject->getCreditAnalysisPointsUsed();
                            if ($usageAvailable > 0) {
                                $userCreditHistoryDaoService = $this->getUserCreditHistoryDaoService();
                                //$userCreditHistoryObject = $expiringUserHasPackageObject->getUserCreditHistory()->first();
                                $userCreditHistoryObject->setCreditAnalysisPointsUsed($userCreditHistoryObject->getCreditAnalysisPointsUsed() + 1);
                                $userCreditHistoryDaoService->persistFlush($userCreditHistoryObject);
                                $count++;
                            }
                        }
                    }
                }

                $response['status'] = 'success';
                $response['success_message'] = 'Analysis request created successfully';
            } else {
                $response['status'] = 'fail';
                $response['error_message'] = 'Not able to create analysis request';
                $response['analysis_request_form'] = $this->populateAnalysisRequestFormWithPostDate($analysisRequestForm, $postDataArray);
            }
        }
        return $response;
    }

    /*
     * Populate post data in analysis request form
     */

    public function populateAnalysisRequestFormWithPostDate($analysisRequestForm, $postDataArray)
    {
        if ($postDataArray['analysis_name']) {
            $analysisRequestForm->get('analysis_name')->setValue($postDataArray['analysis_name']);
        }
        if ($postDataArray['analysis_details']) {
            $analysisRequestForm->get('analysis_details')->setValue($postDataArray['analysis_details']);
        }
        if ($postDataArray['rule_book']) {
            $analysisRequestForm->get('rule_book')->setValue($postDataArray['rule_book']);
        }
        if ($postDataArray['extracts']) {
            $analysisRequestForm->get('extracts')->setValue($postDataArray['extracts']);
        }
        return $analysisRequestForm;
    }

    /*
     * Populate analysis request form
     */

    public function populateAnalysisRequestForm($analysisRequestForm, $userObject)
    {
        
        $userId = $userObject->getId();
        $ruleBookObjectList = $this->getRuleBookUsingUserId($userId);       
        $ruleBookObjectList = array_reverse($ruleBookObjectList);
        
        $ruleBookListArray[0] = null;
        foreach ($ruleBookObjectList as $ruleBookObject) {
            if ($ruleBookObject->getDeleteFlag() == 0) {
                $ruleBookListArray[$ruleBookObject->getId()] = $ruleBookObject->getName();
            }
        }
        $ruleBookObjectList = $this->getRuleBookUsingUserId(0);       
        $ruleBookListArray[$ruleBookObjectList[0]->getId()] = $ruleBookObjectList[0]->getName();
        
        $extractListArray = $this->getExtractsUsingUserId($userId);
        $extractArray[0] = null;
        foreach ($extractListArray as $extract) {
            $extractArray[$extract['id']] = $extract['extractName'];
        }

        $analysisRequestForm->get('extracts')->setValueOptions($extractArray);
        $analysisRequestForm->get('rule_book')->setValueOptions($ruleBookListArray);
        return $analysisRequestForm;
    }

    /*
     * Get rulebook using userId
     */

    public function getRuleBookUsingUserId($userId)
    {        
        $ruleBookDaoService = $this->getRuleBookDaoService();
        $queryParamArray['userId'] = $userId;
        $entity = Constant::ENTITY_RULEBOOK;
        $ruleBookObjectList = $ruleBookDaoService->getEntityListByParameterList($queryParamArray, $entity);        
        return $ruleBookObjectList;
    }

    /*
     * Get extracts using user id
     */

    public function getExtractsUsingUserId($userId)
    {
        $extractsDaoService = $this->getExtractsDaoService();
        $extractArray = $extractsDaoService->getExtractsUsingUserId($userId);
        return $extractArray;
    }

    /*
     * Validate user accout details
     */

    public function validateAnalysisRequestData($inputArray)
    {
        $analysisRequestDaoService = $this->getAnalysisRequestDaoService();
        $result = array('valid' => true);
        $errorMessage = array();

        if (empty($inputArray['analysis_name'])) {
            $result['valid'] = false;
            $errorMessage['analysis_name'] = "Please enter analysis name";
        } else {
            $queryParamArray['analysisRequestName'] = $inputArray['analysis_name'];
            $queryParamArray['userId'] = $inputArray['userId'];
            $entity = Constant::ENTITY_ANALYSIS_REQUEST;
            $analysisRequestObject = $analysisRequestDaoService->getEntityByParameterList($queryParamArray, $entity);
            if (is_object($analysisRequestObject)) {
                $result['valid'] = false;
                $errorMessage['analysis_name'] = "Analysis name is already in use";
            }
        }

        if (empty($inputArray['rule_book'])) {            
            $result['valid'] = false;
            $errorMessage['rule_book'] = "Please select a rulebook";
        }

        if (empty($inputArray['extracts'])) {
            $result['valid'] = false;
            $errorMessage['extracts'] = "Please select an extract";
        }
        
        $result['error'] = $errorMessage;
        return $result;
    }

}
