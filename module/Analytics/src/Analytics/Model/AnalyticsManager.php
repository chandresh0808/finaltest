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
    public function createAnalysisReqeustEntry($inputDataArray) {
        
        $analysisRequestDaoService = $this->getAnalysisRequestDaoService();
        $analysisRequestDaoObject = $analysisRequestDaoService->createUpdateEntity($inputDataArray);
        return $analysisRequestDaoObject;
        
    }
}
