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

namespace Cron\Model;

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
class CronManager extends \Application\Model\AbstractCommonServiceMutator
{

    /*
     * Delete all user session inactive for 1 hour
     */
    public function deleteInactiveUserSession () {
          $userManagerService = $this->getUserManagerService();
          $result = $userManagerService->deleteInactiveUserSession();
          return $result;
    }

   /*
    * Notify and delete analysis request
    */ 
    
    public function notifyAndDeleteAnalysisRequest($aws, $appBaseLink) {
        $analyticsManagerService = $this->getAnalyticsManagerService();        
        $result = $analyticsManagerService->notifyAndDeleteAnalysisRequest($aws, $appBaseLink);
        return $result;
    }
}
