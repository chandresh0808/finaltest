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

class OrderDao extends \Application\Model\AbstractDao
{
                     

    /**
     * Populate a UserSession Object 
     * Exchange/populate UserSession Array
     * 
     * @param List $paramList ParameterList
     * 
     * @return object userSession
     */
    public function exchangeArray($postDataArray, $orderObject = null)
    {

        if ($orderObject) {
            $orderObject = \Application\Model\Utility::setDateTimeForUpdation($orderObject);
        } else {
            $orderObject = new \Application\Entity\Order();
            $orderObject = \Application\Model\Utility::setDateTimeForCreation($orderObject);
        }
        
        $orderObject->setCartId($postDataArray['cart_id']);
        $orderObject->setUserId($postDataArray['user_id']);
        $orderObject->setOrderTotal($postDataArray['order_total']);
        $orderObject->setOrderStatus($postDataArray['order_status']);
        $orderObject->setOrderEmail($postDataArray['order_email']);       

        return $orderObject;
    }
    
    
    /**
     * function to get order details by id
     *
     * @param int $id - orderId to get entity
     *
     * @return Application\Entity\Order
     */
    public function read($id) {
        return $this->getEntityManager()
                        ->getRepository('Application\Entity\Order')
                        ->find($id);
    }

}
