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
        $ruleBookId = $this->getValueFromHeader(Constant::INPUT_PARAM_RULE_BOOK_ID);
        $extractName = $this->getValueFromHeader(Constant::INPUT_PARAM_EXTRACT_NAME);
        $extractFileName = $this->getValueFromHeader(Constant::INPUT_PARAM_EXTRACT_FILE_NAME);
        $refId = $this->getValueFromHeader(Constant::INPUT_PARAM_REF_ID);        
                       
        $result = $this->getApiManagerService()->saveAnalysisRequest(
                $sessionGuid, 
                $ruleBookId, 
                $extractName,
                $extractFileName,
                $refId);       
        
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

        $sessionGuid = '5B701483-652E-B73E-8F02-1F4DF96C5D1C';
        $ruleBookId = '1';
        $extractName = 'Test Extract';
        $extractFileName = 'Test Extract File Name';
        $refId = '37';
        
        
        $result = $this->getApiManagerService()->saveAnalysisRequest(
                $sessionGuid, 
                $ruleBookId, 
                $extractName,
                $extractFileName,
                $refId);       
        
        return new JsonModel($result);
    }
    
}