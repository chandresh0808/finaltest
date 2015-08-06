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
    
     
    /*
     * Notify and delete analysis request
     * 
     * @TODO - When user extends AR - need to delete all notification entry for it
     * 
     */
    
    public function notifyAndDeleteAnalysisRequestAction () {  
               
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        
        $config = $this->getApplicationConfig();
        $applicationEnv = getenv('APPLICATION_ENV');      
        $appBaseLink = $config['appBaseUrl'][$applicationEnv];
        
        $cronManagerService = $this->getCronManagerService();
        $aws = $this->getServiceLocator()->get('aws');  
        $cronManagerService->notifyAndDeleteAnalysisRequest($aws, $appBaseLink);
        return $viewModel;
    }
    
}