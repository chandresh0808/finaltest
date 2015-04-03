<?php

/**
 * Provides Rest Api for Java utility
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

use Api\Controller\AbstractCoreController as AbstractCoreController;
use Zend\View\Model\ViewModel;

class UtilityController extends AbstractCoreController
{
     
    public function getList() {        
        echo $email = $this->params()->fromQuery('email') . " From Utility";       
        return new JsonModel($result);
    }    
}
