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

class RuleBookController extends AbstractCoreController
{
    /**
     * check for user authentication
     *  
     * @param type $postData
     * 
     * @return json Description
     */
    public function create()
    {
        $sessionGuid = $this->getValueFromHeader(Constant::USER_SESSION_GUID);        
        $ruleBookList = $this->getApiManagerService()->getUserRuleBookList($sessionGuid);   
        return new JsonModel($ruleBookList);
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
        $sessionGuid = '22A436C5-C945-B35D-F983-8D12B545915F';
        
        $ruleBookList = $this->getApiManagerService()->getUserRuleBookList($sessionGuid);        
        return new JsonModel($result);
    }
       
}