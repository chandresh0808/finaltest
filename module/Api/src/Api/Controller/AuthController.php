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

class AuthController extends AbstractCoreController
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
        $request  = $this->getRequest();
        $encryptedString = $request->getHeaders('credentials')->getFieldValue();                
        $result = $this->getApiManagerService()->authenticateUser($encryptedString);       
        return new JsonModel($result);
    }
 
}