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

namespace Cart\Model;

use Application\Model\Constant as Constant;
use Zend\Session\Container;
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
class CartManager extends \Application\Model\AbstractCommonServiceMutator
{
    /*
     * Display package
     */

    public function displayPackageUsingCookie($cookieValue, $userId)
    {

        $cartDaoService = $this->getCartDaoService();
        $queryParamArray['cartSessionId'] = $cookieValue;
        
        //if ($userId) {
            $queryParamArray['userId'] = $userId;
        //}
        
        $entity = Constant::ENTITY_CART;
        $cartObject = $cartDaoService->getEntityByParameterList($queryParamArray, $entity);
        if (is_object($cartObject)) {
            return $cartObject;
        }
        return false;
    }

    /*
     * Delete cart item
     */

    public function deleteCartItem($postDataArray, $e)
    {
        $cartId = $postDataArray['cart_id'];
        $itemId = $postDataArray['item_id'];
        $cartDaoService = $this->getCartDaoService();

        $queryParamArray['id'] = $cartId;
        $cartObject = $cartDaoService->getEntityByParameterList($queryParamArray, Constant::ENTITY_CART);
        if (is_object($cartObject)) {
            $cartObject->setDeleteFlag(1);
            $cartObject = $cartDaoService->persistFlush($cartObject);

            /* deleting item entry */
            $itemObject = $cartObject->getItemList();
            if (is_object($itemObject)) {
                $itemObject[0]->setDeleteFlag(1);
                $itemObject = $cartDaoService->persistFlush($itemObject[0]);
            }
        }


        /* delete order, order details and payment method */
        $orderObject = $cartObject->getOrder();

        if (is_object($orderObject[0])) {

            $orderObject[0]->setDeleteFlag(1);
            $orderObject = $cartDaoService->persistFlush($orderObject[0]);

            $orderDetailsObject = $orderObject->getOrderDetails();
            if (is_object($orderDetailsObject)) {
                $orderDetailsObject[0]->setDeleteFlag(1);
                $orderDetailsObject = $cartDaoService->persistFlush($orderDetailsObject[0]);
            }


            $paymentMethodObject = $orderObject->getPaymentMethod();
            if ($paymentMethodObject) {
                $paymentMethodObject[0]->setDeleteFlag(1);
                $paymentMethodObject = $cartDaoService->persistFlush($paymentMethodObject[0]);
            }
        }
        
        /* deleteing session */
        $paypalSession = new Container('paypal');
        unset($paypalSession->cc);
        unset($paypalSession->cvv);

        $cartSession = new Container('cart');
        unset($cartSession->cartId);
        
        /* deleting cookie */
        \Application\Model\Utility::deleteCustomCookie($e);
        
        $response['status'] = 'fail';
        $response['message'] = 'Not able to delete an item from cart';
        if (is_object($itemObject)) {
            $response['status'] = 'success';
            $response['message'] = 'Item from cart is deleted successfully';
        }
        return $response;
    }

}
