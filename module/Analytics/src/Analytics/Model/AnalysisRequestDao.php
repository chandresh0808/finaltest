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

namespace Analytics\Model;
 
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

class AnalysisRequestDao extends \Application\Model\AbstractDao
{
  
    /**
     * Populate a UserSession Object 
     * Exchange/populate UserSession Array
     * 
     * @param List $paramList ParameterList
     * 
     * @return object userSession
     */
    public function exchangeArray($postDataArray, $analysisRequestObject = null)
    {

        if ($analysisRequestObject) {
            $analysisRequestObject = \Application\Model\Utility::setDateTimeForUpdation($analysisRequestObject);
        } else {
            $analysisRequestObject = new \Application\Entity\AnalysisRequest();
            $analysisRequestObject = \Application\Model\Utility::setDateTimeForCreation($analysisRequestObject);
        }
        
        $analysisRequestObject->setUserId($postDataArray['user_id']);
        $analysisRequestObject->setRulebookId($postDataArray['rule_book_id']);
        $analysisRequestObject->setExtractName($postDataArray['extract_name']);
        $analysisRequestObject->setExtractFileName($postDataArray['extract_file_name']);
        $analysisRequestObject->setStatus($postDataArray['status']);
        $analysisRequestObject->setSystemSaltId($postDataArray['system_salt']);     

        return $analysisRequestObject;
    }
    
}
