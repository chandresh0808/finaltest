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

namespace Payment\Model;

 
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

class PaymentHistoryDao extends \Application\Model\AbstractDao
{
                     

    /**
     * Populate a UserSession Object 
     * Exchange/populate UserSession Array
     * 
     * @param List $paramList ParameterList
     * 
     * @return object userSession
     */
    public function exchangeArray($postDataArray, $paymentHistoryObject = null)
    {

        if ($paymentHistoryObject) {
            $paymentHistoryObject = \Application\Model\Utility::setDateTimeForUpdation($paymentHistoryObject);
        } else {
            $paymentHistoryObject = new \Application\Entity\PaymentHistory();
            $paymentHistoryObject = \Application\Model\Utility::setDateTimeForCreation($paymentHistoryObject);
        }
        
        $paymentHistoryObject->setOrderId($postDataArray['order_id']);
        $paymentHistoryObject->setPaymentRequest($postDataArray['payment_request']);
        $paymentHistoryObject->setPaymentResponse($postDataArray['payment_response']);      
        return $paymentHistoryObject;
    }
    
}
