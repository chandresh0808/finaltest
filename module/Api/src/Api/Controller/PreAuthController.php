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

class PreAuthController extends AbstractCoreController
{
    /**
     * check for user authentication
     *  
     * 
     * @return json Description
     */
    public function create()
    {
        $result = $this->getApiManagerService()->generateAndSaveSalt();
        return new JsonModel($result);
    }
 

    
}