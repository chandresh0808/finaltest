<?php

/**
 * Provides Rest Api for CMS
 * 
 * PHP version 5
 * 
 * @category   Module
 * @package    Api
 * @subpackage Controller
 * @author     Costrategix Team <team@costrategix.com>
 * @copyright  2015 CoS
 * @license    http://www.costrategix.com Proprietary 
 * @version    GIT: 1.7
 * @link       http://www.costrategix.com 
 * 
 */

namespace Api\Controller;

use Zend\View\Model\JsonModel;
use Api\Controller\AbstractCoreController as AbstractCoreController;
use Application\Model\Constant as Constant;

class AnalysisRequestController extends AbstractCoreController
{
    /**
     * Generate Password for extract 
     *  
     * @param type $postData
     * 
     * @return json Description
     */
    public function create()
    {        
        
        $sessionGuid = $this->getValueFromHeader(Constant::USER_SESSION_GUID);
        //$ruleBookId = $this->getValueFromHeader(Constant::INPUT_PARAM_RULE_BOOK_ID);
        $extractName = $this->getValueFromHeader(Constant::INPUT_PARAM_EXTRACT_NAME);
        $extractFileName = $this->getValueFromHeader(Constant::INPUT_PARAM_EXTRACT_FILE_NAME);
        $refId = $this->getValueFromHeader(Constant::INPUT_PARAM_REF_ID);    
        $jobId = $this->getValueFromHeader(Constant::INPUT_PARAM_JOB_ID);
                       
        $result = $this->getApiManagerService()->saveExtracts(
                $sessionGuid, 
                $extractName,
                $extractFileName,
                $refId,
                $jobId);       
        
        return new JsonModel($result);
    }
 
    
    /**
     * @TODO : This is for testing, Need to remove it
     *  
     * @param type $postData
     * 
     * @return json Description
     */
    public function getList()
    {        

        $sessionGuid = '9E02B281-5C78-5DDB-EC6E-D440B0B206F7';      
        $extractName = 'testing';
        $extractFileName = 'test';
        $refId = 1;
        $jobId = 1;
        
        $result = $this->getApiManagerService()->saveExtracts(
                $sessionGuid,               
                $extractName,
                $extractFileName,
                $refId,
                $jobId);       
        
        return new JsonModel($result);
    }
    
}