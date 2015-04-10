<?php
/**
 * Cron Controller
 * 
 * PHP version 5
 * 
 * @category   Auth
 * @package    Controller
 * @subpackage 
 * @author     Costrategix Team <team@costrategix.com>
 * @copyright  2015 Cos
 * @license    http://www.costrategix.com Proprietary 
 * @version    GIT: 1.7
 * @link       http://www.costrategix.co
 * 
 */
namespace Cron\Controller;
use Zend\View\Model\ViewModel;
use Zend\Session\Container as SessionContainer;
use Zend\View\Model\JsonModel;


class CronController extends \Application\Controller\AbstractCoreController
{
        
    /*
     * Delete all user session inactive for 1 hour
     */
    public function deleteInactiveUserSessionAction () {                  
          $cronManagerService = $this->getCronManagerService();
          $result = $cronManagerService->deleteInactiveUserSession();
          return $result;
    }
    
    
}