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

class OrderDetailsDao extends \Application\Model\AbstractDao
{
                     

    
    /**
     * Populate a UserSession Object 
     * Exchange/populate UserSession Array
     * 
     * @param List $paramList ParameterList
     * 
     * @return object userSession
     */
    public function exchangeArray($postDataArray, $orderDetailsObject = null)
    {

        if ($orderDetailsObject) {
            $orderDetailsObject = \Application\Model\Utility::setDateTimeForUpdation($orderDetailsObject);
        } else {
            $orderDetailsObject = new \Application\Entity\OrderDetails();
            $orderDetailsObject = \Application\Model\Utility::setDateTimeForCreation($orderDetailsObject);
        }
        
        $orderDetailsObject->setOrderId($postDataArray['order_id']);
        $orderDetailsObject->setOrderFirstName($postDataArray['full_name']);
        $orderDetailsObject->setOrderAddress1($postDataArray['address_1']);
        $orderDetailsObject->setOrderAddress2($postDataArray['address_2']);
        $orderDetailsObject->setOrderCity($postDataArray['city']);
        $orderDetailsObject->setOrderState($postDataArray['state']);
        $orderDetailsObject->setOrderCountry($postDataArray['country']);    
        $orderDetailsObject->setOrderZipcode($postDataArray['zip_code']);
        $orderDetailsObject->setOrderPhone($postDataArray['phone_number']); 

        return $orderDetailsObject;
    }
    
    
}
