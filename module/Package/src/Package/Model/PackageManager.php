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

namespace Package\Model;

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
class PackageManager extends \Application\Model\AbstractCommonServiceMutator
{
    /*
     * get package pricing
     */

    public function getDefaultPackagePriceList()
    {
        $packageDaoService = $this->getPackageDaoService();
        $defaultPackagePriceObject = $packageDaoService->getDefaultPackagePriceList();
        $responseArray = array();
        foreach ($defaultPackagePriceObject as $defaultPackagePrice) {
            $tempArray['package_id'] = $defaultPackagePrice->getPackageId();
            $tempArray['total_credits'] = $defaultPackagePrice->getTotalCredits();
            $tempArray['package_amount'] = $defaultPackagePrice->getPackageAmount();
            $tempArray['id'] = $defaultPackagePrice->getId();
            $packageDuration = $defaultPackagePrice->getPackageDuration();
            $responseArray[$packageDuration][] = $tempArray;
        }
        return $responseArray;
        //$serializedData = $this->convertObjectToArrayUsingJmsSerializer($defaultPackagePriceObject);
        //return $serializedData;
    }

    /*
     * Add package
     */

    public function addPackage($postDataArray, $e, $cookieValue)
    {

        $cartDaoService = $this->getCartDaoService();
        $itemDaoService = $this->getItemDaoService();

        if (empty($postDataArray['amount']) || empty($postDataArray['package_id'])) {
            $response['status'] = 'fail';
            $response['message'] = 'Please select any package';
        } else {
            
            /* checks is user already added item in cart*/
            if (!empty($cookieValue)) {
                $queryParam['cartSessionId'] = $cookieValue;
                $queryParam['cartIpAddress'] = \Application\Model\Utility::getIdAddress();                              
                $queryParam['userId'] = $postDataArray['user_id'];                              
                $cartObject = $cartDaoService->getEntityByParameterList($queryParam, Constant::ENTITY_CART);
                
                if (is_object($cartObject)) {
                    $response['status'] = 'fail';
                    $response['message'] = 'You already have a package in the cart. Please remove the package from the cart before adding a new one.';
                    return $response;                    
                }
                
            }
            
            $inputArray['amount'] = $postDataArray['amount'];
            
            /* Reading package */
            $queryParamArray['id'] = $postDataArray['package_id'];
            $inputArray['package_object'] = $cartDaoService->getEntityByParameterList($queryParamArray, Constant::ENTITY_PACKAGE);
          
            $inputArray['user_id'] = 0;
            if (!empty($postDataArray['user_id'])) {
                 $inputArray['user_id'] = $postDataArray['user_id'];
            }
            
            /* Reading package has credit */
            $queryParamArray['id'] = $postDataArray['package_has_credit_id'];
            $inputArray['package_has_credit_object'] = $cartDaoService->getEntityByParameterList($queryParamArray, Constant::ENTITY_PACKAGE_HAS_CREDITS);            
            
            $inputArray['credits'] = $postDataArray['credits'];
            $inputArray['email'] = $postDataArray['email'];           
            $inputArray['ip_address'] = \Application\Model\Utility::getIdAddress();
            $inputArray['cookie_value'] = \Application\Model\Utility::setCustomCookie($e);

            $card = $cartDaoService->createUpdateEntity($inputArray);

            if (is_object($card)) {
                $inputArray['quantity'] = Constant::DEFAULT_QUANTITY;
                $inputArray['cart_id'] = $card->getId();
                $itemObject = $itemDaoService->createUpdateEntity($inputArray);
            }

            if (is_object($itemObject)) {
                $response['status'] = 'success';
            } else {
                $response['status'] = 'fail';
                $response['message'] = 'Not able to add package to cart, Please try again';
            }
        }
        
        return $response;
    }
  

}
