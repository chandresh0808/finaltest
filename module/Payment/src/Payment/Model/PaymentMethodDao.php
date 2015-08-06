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

class PaymentMethodDao extends \Application\Model\AbstractDao
{
                     

    /**
     * Populate a UserSession Object 
     * Exchange/populate UserSession Array
     * 
     * @param List $paramList ParameterList
     * 
     * @return object userSession
     */
    public function exchangeArray($postDataArray, $paymentMethodObject = null)
    {

        if ($paymentMethodObject) {
            $paymentMethodObject = \Application\Model\Utility::setDateTimeForUpdation($paymentMethodObject);
        } else {
            $paymentMethodObject = new \Application\Entity\PaymentMethod();
            $paymentMethodObject = \Application\Model\Utility::setDateTimeForCreation($paymentMethodObject);
        }
        
        $paymentMethodObject->setOrderId($postDataArray['order_id']);
        $paymentMethodObject->setUserId($postDataArray['user_id']);
        $paymentMethodObject->setLastFourDigits($postDataArray['last_four_digits']);
        $paymentMethodObject->setCreditCardType($postDataArray['credit_card_type']);
        $paymentMethodObject->setCreditHolderName($postDataArray['card_holder_name']);       
        $paymentMethodObject->setExpiryYearMonth($postDataArray['expiry_year_month']);    
        
        return $paymentMethodObject;
    }
    
}
