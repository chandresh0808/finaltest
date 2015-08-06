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

class ItemDao extends \Application\Model\AbstractDao
{
                     
    /**
     * Populate a UserSession Object 
     * Exchange/populate UserSession Array
     * 
     * @param List $paramList ParameterList
     * 
     * @return object userSession
     */
    public function exchangeArray($postDataArray, $itemObject = null)
    {

        if ($itemObject) {
            $itemObject = \Application\Model\Utility::setDateTimeForUpdation($itemObject);
        } else {
            $itemObject = new \Application\Entity\Item();
            $itemObject = \Application\Model\Utility::setDateTimeForCreation($itemObject);
        }
        
        $itemObject->setCartId($postDataArray['cart_id']);
        $itemObject->setPackage($postDataArray['package_object']);
        $itemObject->setUserId($postDataArray['user_id']);
        $itemObject->setItemPrice($postDataArray['amount']);
        $itemObject->setQuantity($postDataArray['quantity']);
        $itemObject->setPackageHasCredits($postDataArray['package_has_credit_object']);

        return $itemObject;
    }
        
    
}
