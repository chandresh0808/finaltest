<?php

/**
 * DAO class
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
use Doctrine\Common\Collections\Criteria as Criteria;
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

class NotificationLogDao extends \Application\Model\AbstractDao
{
  
    /**
     * Populate a UserSession Object 
     * Exchange/populate UserSession Array
     * 
     * @param List $paramList ParameterList
     * 
     * @return object userSession
     */
    public function exchangeArray($postDataArray, $notificationLogObject = null)
    {

        if ($notificationLogObject) {
            $notificationLogObject = \Application\Model\Utility::setDateTimeForUpdation($notificationLogObject);
        } else {
            $notificationLogObject = new \Application\Entity\NotificationLog();
            $notificationLogObject = \Application\Model\Utility::setDateTimeForCreation($notificationLogObject);
        }
        
        $notificationLogObject->setAnalysisRequestId($postDataArray['analysis_request_id']);
        $notificationLogObject->setSystemParamKey($postDataArray['system_param_key']);
        $notificationLogObject->setStatus($postDataArray['status']);
        
        return $notificationLogObject;
    }
    
    
    public function read($id) {
        $queryParamArray['id'] = $id;
        $entity = Constant::ENTITY_NOTIFICATION_LOG;
        $notificationLogObject = $this->getEntityByParameterList($queryParamArray, $entity);    
        return $notificationLogObject;
    }
    
}
