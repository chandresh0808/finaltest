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

namespace Cart\Model;
 
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

class CartDao extends \Application\Model\AbstractDao
{
                     
    /**
     * Populate a UserSession Object 
     * Exchange/populate UserSession Array
     * 
     * @param List $paramList ParameterList
     * 
     * @return object userSession
     */
    public function exchangeArray($postDataArray, $cartObject = null)
    {

        if ($cartObject) {
            $cartObject = \Application\Model\Utility::setDateTimeForUpdation($cartObject);
        } else {
            $cartObject = new \Application\Entity\Cart();
            $cartObject = \Application\Model\Utility::setDateTimeForCreation($cartObject);
        }
        
        $cartObject->setCartIpAddress($postDataArray['ip_address']);
        $cartObject->setCartSessionId($postDataArray['cookie_value']);
        $cartObject->setUserId($postDataArray['user_id']);
        $cartObject->setEmailAddress($postDataArray['email']);

        return $cartObject;
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
                        ->getRepository('Application\Entity\Cart')
                        ->find($id);
    }
    
}
