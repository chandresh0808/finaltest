<?php
/**
 * AbstractCoreController.php
 * 
 * Common Controller functionality inherited by other controllers
 * 
 * PHP version 5
 * 
 * @category   Module
 * @package    Application
 * @subpackage Controller
 * @author     Costrategix Team <team@costrategix.com>
 * @copyright  2015 CoS
 * @license    http://www.costrategix.com Proprietary 
 * @version    GIT: 1.7
 * @link       http://www.costrategix.com 
 * 
 */

namespace Api\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\ViewModel;


/**
 * Common Controller functionality inherited by other controllers
 * 
 * @category   Module
 * @package    Review_Module
 * @subpackage ReviewController
 * @author     Costrategix Team <team@costrategix.com>
 * @copyright  2014 QGrid
 * @license    http://www.qgrid.io Proprietary 
 * @version    Release: 1.0
 * @link       http://www.qgrid.io
 * 
 */
class AbstractCoreController extends AbstractRestfulController
{
        
    /**
     * Get Api Manager Service instnace
     *
     * @return Api\Model\ApiManager
     */
    public function getApiManagerService()
    {        
        return $this->getServiceLocator()->get('api_manager_service');
    }
    
    /*
     * Return value from header paramter
     * @param string $headerName
     * 
     * @return string $headerValue
     */
    public function getValueFromHeader($headerName) {
        $request  = $this->getRequest();
        if(!empty($headerName)) {
             $headerValue = $request->getHeaders($headerName)->getFieldValue();
        }       
        return $headerValue;
    }
}
