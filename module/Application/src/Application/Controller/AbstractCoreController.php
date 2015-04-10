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

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
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
class AbstractCoreController extends AbstractActionController
{
        
    /**
     *Get the flash Message
     * 
     * @param string $flashMessageIndex FlashMessageIndexName
     * 
     * @return  Description 
     * 
     **/
    public function getFlashMessage($flashMessageIndex)
    {
        try {
            $flashMessage = $this->flashMessenger()->setNamespace($flashMessageIndex)
                    ->getMessages();
            if ($flashMessage[0]) {
                return $flashMessage[0];
            } else {
                return null;
            }
        } catch (\Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
     /**
     * Get the current route name
     *
     * @return string - action route name
     */
    protected function _getRouteName()
    {
        return $this->getServiceLocator()
            ->get('Application')
            ->getMvcEvent()
            ->getRouteMatch()
            ->getMatchedRouteName();
    }

    /**
     * Get cron manager Service instance
     * 
     * @return Cron\Model\CronManager
     */
    public function getCronManagerService()
    {
        return $this->getServiceLocator()->get('cron_manager_service');
    }

}
