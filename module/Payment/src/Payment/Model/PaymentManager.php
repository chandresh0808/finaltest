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

namespace Payment\Model;

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
class PaymentManager extends \Application\Model\AbstractCommonServiceMutator
{
    /*
     * Paypal config
     */

    private $_paypalConfig;

    /*
     * Gets paypal config
     */

    public function getPaypalConfig()
    {
        return $this->_paypalConfig;
    }

    /*
     * Sets paypal config
     */

    public function setPaypalConfig($paypalConfig)
    {
        $this->_paypalConfig = $paypalConfig;
    }

    /*
     * Populate checkout form
     */

    public function populateCheckoutForm($checkoutForm, $orderAndPaymentDetails, $userObject)
    {
        $userManagerService = $this->getUserManagerService();
        $stateList = $userManagerService->getStateList();
        foreach ($stateList as $state) {
            $stateListArray[$state->getStateCd()] = $state->getStateName();
        }
        $checkoutForm->get('state')->setValueOptions($stateListArray);
        $checkoutForm->get('state')->setValue('OH');

        $yearList = \Application\Model\Utility::getYearList();
        $monthList = \Application\Model\Utility::getMonthList();
        $checkoutForm->get('expire_month')->setValueOptions($monthList);
        $checkoutForm->get('expire_year')->setValueOptions($yearList);
        $currentMonth = date('m');
        $checkoutForm->get('expire_month')->setValue($currentMonth);

        if (count($orderAndPaymentDetails) > 0) {            
            $checkoutForm->get('full_name')->setValue($orderAndPaymentDetails['full_name']);
            $checkoutForm->get('email')->setValue($orderAndPaymentDetails['email']);
            $checkoutForm->get('phone_number')->setValue($orderAndPaymentDetails['phone_number']);
            $checkoutForm->get('address_1')->setValue($orderAndPaymentDetails['address_1']);
            $checkoutForm->get('address_2')->setValue($orderAndPaymentDetails['address_2']);
            $checkoutForm->get('city')->setValue($orderAndPaymentDetails['city']);
            $checkoutForm->get('state')->setValue($orderAndPaymentDetails['state']);
            $checkoutForm->get('country')->setValue($orderAndPaymentDetails['country']);
            $checkoutForm->get('zip_code')->setValue($orderAndPaymentDetails['zip_code']);
            $checkoutForm->get('card_holder_name')->setValue($orderAndPaymentDetails['card_holder_name']);
            $checkoutForm->get('expire_month')->setValue(substr($orderAndPaymentDetails['expiry_year_month'], 0, 2));
            $checkoutForm->get('expire_year')->setValue(substr($orderAndPaymentDetails['expiry_year_month'], -4));
        } else {
            if (is_object($userObject)) {
                $fullName = $userObject->getFirstName() . " " . $userObject->getLastName();
                $checkoutForm->get('full_name')->setValue($fullName);
                $checkoutForm->get('email')->setValue($userObject->getUsername());
                $checkoutForm->get('state')->setValue($userObject->getState());
                $checkoutForm->get('address_1')->setValue($userObject->getAddress1());
                $checkoutForm->get('address_2')->setValue($userObject->getAddress2());
                $checkoutForm->get('city')->setValue($userObject->getCity());
                $checkoutForm->get('state')->setValue($userObject->getState());
                $checkoutForm->get('country')->setValue($userObject->getCountry());
                $checkoutForm->get('zip_code')->setValue($userObject->getZipcode());
                $checkoutForm->get('phone_number')->setValue($userObject->getPhoneNumber());
            }
        }



        return $checkoutForm;
    }

    /*
     * Save order details
     */

    public function saveOrderDetails($postData, $checkoutForm)
    {

        $paymentService = $this->getPaymentService();
        $validationResponse = $paymentService->validateOrderDetails($postData);

        if (false == $validationResponse['valid']) {
            $responseArray['status'] = 'fail';
            $responseArray['error_message'] = $validationResponse['error'];
            $responseArray['checkout_form'] = $this->populateCheckoutForm($checkoutForm, $postData);
        } else {

            $responseArray['status'] = 'fail';

            /* input array for order entity/table */
            $inputDataArray['cart_id'] = $postData['hidden_cart_id'];
            $inputDataArray['user_id'] = $postData['user_id'];
            $inputDataArray['order_total'] = $postData['package_amount'];
            $inputDataArray['order_status'] = Constant::ORDER_STATUS_PENDING;
            $inputDataArray['order_email'] = $postData['email'];
            $orderObject = $this->createOrderEntry($inputDataArray);

            /* input array for order details entity/table */
            if (is_object($orderObject)) {
                $inputDataArray['order_id'] = $orderObject->getId();
                $inputDataArray['full_name'] = htmlspecialchars($postData['full_name']);
                $inputDataArray['address_1'] = htmlspecialchars($postData['address_1']);
                $inputDataArray['address_2'] = htmlspecialchars($postData['address_2']);
                $inputDataArray['city'] = htmlspecialchars($postData['city']);
                $inputDataArray['state'] = $postData['state'];
                $inputDataArray['country'] = htmlspecialchars($postData['country']);
                $inputDataArray['zip_code'] = $postData['zip_code'];
                $inputDataArray['phone_number'] = str_replace("-", '', $postData['phone_number']);
                $orderDetailsObject = $this->createOrderDetailsEntry($inputDataArray);
            }

            if (is_object($orderDetailsObject)) {

                $inputDataArray['order_id'] = $orderObject->getId();
                $inputDataArray['user_id'] = $postData['user_id'];

                $salt = Constant::PAYMENT_METHOD_SALT;
                $expireMonthYear = $postData['expire_month'] . $postData['expire_year'];

                $inputDataArray['last_four_digits'] = \Application\Model\Utility::encrypt(substr($postData['card_number'], -4), $salt);
                $inputDataArray['credit_card_type'] = \Application\Model\Utility::encrypt($postData['credit_card_type'], $salt);
                $inputDataArray['card_holder_name'] = \Application\Model\Utility::encrypt($postData['card_holder_name'], $salt);
                $inputDataArray['expiry_year_month'] = \Application\Model\Utility::encrypt($expireMonthYear, $salt);

                $paymentMethodObject = $this->createPaymentMethodEntry($inputDataArray);
            }

            if ($paymentMethodObject) {
                $responseArray['status'] = 'success';
                $responseArray['order_id'] = $orderObject->getId();
                $responseArray['cart_id'] = $postData['hidden_cart_id'];
                $paypalSession = new Container('paypal');
                $cartSession = new Container('cart');
                $cartSession->cartId = $responseArray['cart_id'];
                $paypalSession->cc = \Application\Model\Utility::encrypt($postData['card_number'], $salt);
                $paypalSession->cvv = \Application\Model\Utility::encrypt($postData['cvv_number'], $salt);
            }
        }




        return $responseArray;
    }

    /*
     * update order details
     */

    public function updateOrderDetails($cartId, $postData)
    {

        $orderDaoService = $this->getOrderDaoService();

        $queryParamArray['id'] = $cartId;
        $entity = Constant::ENTITY_CART;
        $cartObject = $orderDaoService->getEntityByParameterList($queryParamArray, $entity);
        $orderObject = $cartObject->getOrder();
        $orderDetailsObject = $orderObject[0]->getOrderDetails();
        $paymentMethodObject = $orderObject[0]->getPaymentMethod();

        /* order object */
        $orderObject[0]->setOrderEmail($postData['email']);

        /* order details object */
        $orderDetailsObject[0]->setOrderFirstName(htmlspecialchars($postData['full_name']));
        $orderDetailsObject[0]->setOrderPhone(str_replace("-", '', $postData['phone_number']));
        $orderDetailsObject[0]->setOrderAddress1(htmlspecialchars($postData['address_1']));
        $orderDetailsObject[0]->setOrderAddress2(htmlspecialchars($postData['address_2']));
        $orderDetailsObject[0]->setOrderCity(htmlspecialchars($postData['city']));
        $orderDetailsObject[0]->setOrderState($postData['state']);
        $orderDetailsObject[0]->setOrderCountry(htmlspecialchars($postData['country']));
        $orderDetailsObject[0]->setOrderZipcode($postData['zip_code']);
        
        /* payment object */
        $salt = Constant::PAYMENT_METHOD_SALT;
        $expireMonthYear = $postData['expire_month'] . $postData['expire_year'];
        $cartHolderName = \Application\Model\Utility::encrypt($postData['card_holder_name'], $salt);
        $lastFourDigits = \Application\Model\Utility::encrypt(substr($postData['card_number'], -4), $salt);
        $expireMonthYear = \Application\Model\Utility::encrypt($expireMonthYear, $salt);

        $paymentMethodObject[0]->setCreditHolderName($cartHolderName);
        $paymentMethodObject[0]->setLastFourDigits($lastFourDigits);
        $paymentMethodObject[0]->setExpiryYearMonth($expireMonthYear);

        $orderObject = $orderDaoService->persistFlush($orderObject[0]);
        $orderDetailsObject = $orderDaoService->persistFlush($orderDetailsObject[0]);
        $paymentMethodObject = $orderDaoService->persistFlush($paymentMethodObject[0]);

        if (is_object($orderObject) && is_object($orderDetailsObject) && is_object($paymentMethodObject)) {

            $paypalSession = new Container('paypal');
            unset($paypalSession->cc);
            unset($paypalSession->cvv);
            $paypalSession->cc = \Application\Model\Utility::encrypt($postData['card_number'], $salt);
            $paypalSession->cvv = \Application\Model\Utility::encrypt($postData['cvv_number'], $salt);

            $responseArray['status'] = 'success';
        } else {
            $responseArray['status'] = 'fail';
        }

        return $responseArray;
    }

    /*
     * Create a entry in order table
     * @param array $inputDataArray
     * 
     * @return object $orderObject
     */

    public function createOrderEntry($inputDataArray)
    {
        $orderDaoService = $this->getOrderDaoService();
        $orderObject = $orderDaoService->createUpdateEntity($inputDataArray);
        return $orderObject;
    }

    /*
     * Create a entry in order details table
     * @param array $inputDataArray
     * 
     * @return object $orderDetailsObject
     */

    public function createOrderDetailsEntry($inputDataArray)
    {
        $orderDetailsDaoService = $this->getOrderDetailsDaoService();
        $orderDetailsObject = $orderDetailsDaoService->createUpdateEntity($inputDataArray);
        return $orderDetailsObject;
    }

    /*
     * Create a entry in payment method table
     * @param array $inputDataArray
     * 
     * @return object $orderDetailsObject
     */

    public function createPaymentMethodEntry($inputDataArray)
    {
        $paymentMethodDaoService = $this->getPaymentMethodDaoService();
        $paymentMethodObject = $paymentMethodDaoService->createUpdateEntity($inputDataArray);
        return $paymentMethodObject;
    }

    /*
     * gets order and payment details
     */

    public function getOrderAndPaymentDetails($cartId)
    {
        $orderDaoService = $this->getOrderDaoService();

        if ($cartId) {
            $queryParamArray['id'] = $cartId;
            $entity = Constant::ENTITY_CART;
            $cartObject = $orderDaoService->getEntityByParameterList($queryParamArray, $entity);

            if (is_object($cartObject)) {

                $orderObject = $cartObject->getOrder();
                $itemObject = $cartObject->getItemList();
                if (is_object($itemObject)) {
                    $responseArray['package_name'] = $itemObject['0']->getPackage()->getName();
                    $responseArray['package_credits'] = $itemObject['0']->getPackageHasCredits()->getTotalCredits();
                    $responseArray['package_amount'] = $itemObject['0']->getPackageHasCredits()->getPackageAmount();

                    $responseArray['package_id'] = $itemObject['0']->getPackage()->getId();
                    $responseArray['phc_id'] = $itemObject['0']->getPackageHasCredits()->getId();


                    $responseArray['cart_id'] = $cartObject->getId();
                    $responseArray['item_id'] = $itemObject['0']->getId();
                    $responseArray['package_duration'] = $itemObject['0']->getPackageHasCredits()->getPackageDuration();
                    $responseArray['user_id'] = $cartObject->getUserId();
                }


                $orderDetailsObject = $orderObject[0]->getOrderDetails();
                $paymentMethodObject = $orderObject[0]->getPaymentMethod();               


                $responseArray['email'] = $orderObject[0]->getOrderEmail();
                $responseArray['order_id'] = $orderObject[0]->getId();

                $responseArray['full_name'] = $orderDetailsObject[0]->getOrderFirstName();
                $responseArray['phone_number'] = $orderDetailsObject[0]->getOrderPhone();
                $responseArray['address_1'] = $orderDetailsObject[0]->getOrderAddress1();
                $responseArray['address_2'] = $orderDetailsObject[0]->getOrderAddress2();
                $responseArray['city'] = $orderDetailsObject[0]->getOrderCity();
                $responseArray['state'] = $orderDetailsObject[0]->getOrderState();
                $responseArray['country'] = $orderDetailsObject[0]->getOrderCountry();
                $responseArray['zip_code'] = $orderDetailsObject[0]->getOrderZipcode();

                $salt = Constant::PAYMENT_METHOD_SALT;
                $responseArray['card_holder_name'] = \Application\Model\Utility::decrypt($paymentMethodObject[0]->getCreditHolderName(), $salt);
                $responseArray['last_four_digits'] = \Application\Model\Utility::decrypt($paymentMethodObject[0]->getLastFourDigits(), $salt);
                $responseArray['expiry_year_month'] = \Application\Model\Utility::decrypt($paymentMethodObject[0]->getExpiryYearMonth(), $salt);
            }
        }        
        return $responseArray;
    }

    /*
     * place order
     */

    public function placeOrder($orderAndPaymentDetails, $e)
    {
        $logService = $this->getLogService();       
                  
        try {
            $paypalConfig = $this->getPaypalConfig();          
            $paymentService = $this->getPaymentService();
            $paymentHistoryDaoService = $this->getPaymentHistoryDaoService();

            /* Get speck config object */
            $speckConfigObject = $paymentService->getSpeckConfig($paypalConfig);

            /* Get curl object */
            $curlClientObject = $paymentService->getCurlClient();

            /* Gets speck paypal request object */
            $speckPaypalRequestObject = $paymentService->getSpeckPaypalRequest($curlClientObject, $speckConfigObject);

            /* payment details object from speck paypal */
            $paymentDetailsInputArray['amt'] = $orderAndPaymentDetails['package_amount'];
            $speckPaymentDetails = $paymentService->getSpeckPaymentDetails($paymentDetailsInputArray);

            /* speck paypal dodirect payment object */
            $doDirectPaymentDetailsInputArray['paymentDetails'] = $speckPaymentDetails;
            $doDirectPaymentObject = $paymentService->getSpeckDoDirectPayment(
                    $doDirectPaymentDetailsInputArray, $orderAndPaymentDetails
            );

            /* Address object */
            $addressObject = $paymentService->getSpeckAddress($orderAndPaymentDetails);
            $doDirectPaymentObject->setAddress($addressObject);

            /* log paypal request */
            $tempInputArray = $orderAndPaymentDetails;
            unset($tempInputArray['card_holder_name']);
            unset($tempInputArray['last_four_digits']);
            unset($tempInputArray['expiry_year_month']);
                       
            $paymentHistoryInputArray['order_id'] = $orderAndPaymentDetails['order_id'];
            $paymentHistoryInputArray['payment_request'] = json_encode($tempInputArray);
            $paymentHistoryObject = $paymentHistoryDaoService->createUpdateEntity($paymentHistoryInputArray);
                        
            $response = $speckPaypalRequestObject->send($doDirectPaymentObject);
            $serializedData = $this->convertObjectToArrayUsingJmsSerializer($response);

            /* log paypal response */
            $paymentHistoryObject->setPaymentResponse(json_encode($serializedData));
            $paymentHistoryDaoService->persistFlush($paymentHistoryObject);

            if ($response->getTransactionId()) {
                
                /*activity log start*/
                $systemActivityDaoService = $this->getSystemActivityDaoService();
                $userId = $orderAndPaymentDetails['user_id'];     
                $entity = Constant::ENTITY_USER;
                $queryParamArray['id'] = $userId;
                $userObject = $systemActivityDaoService->getEntityByParameterList($queryParamArray, $entity);
                $fullName = $userObject->getFirstName() . " " . $userObject->getLastName();
                $creditPoints = $orderAndPaymentDetails['package_credits'];
                $comment = "{$fullName} has purchased {$creditPoints} credits";          
                $code = Constant::ACTIVITY_CODE_PAC;
                $systemActivityObject = $systemActivityDaoService->createSystemActivityLog($code, $userId, $comment);
                /*activity log end*/
                
                
                $responseArray['status'] = 'success';
                /* Insert package details for user in db */
                $this->insertPackageDetailsIntoDb($orderAndPaymentDetails);
                $currentDateTime = date('Y-m-d H:i:s');
                $numberOfDays = $orderAndPaymentDetails['package_duration'];
                $nextMonthDateTime = date('Y-m-d H:i:s', strtotime("+{$numberOfDays} day"));

                /* send order confirmation mail */
                $orderAndPaymentDetails['package_start_date'] = $currentDateTime;
                $orderAndPaymentDetails['package_start_end'] = $nextMonthDateTime;
                $this->sendOrderConfirmationMail($orderAndPaymentDetails);
                $succcessSession = new Container('Success');
                $succcessSession['response'] = 'success';
                /* delete cookie and session */
                //$this->deleteCartCookieAndSession($e);
                
                /* update the order status */
                $queryParamArray['id'] = $orderAndPaymentDetails['order_id'];
                $entity = Constant::ENTITY_ORDER;
                $orderObject = $paymentHistoryDaoService->getEntityByParameterList($queryParamArray, $entity);
                $orderObject->setOrderStatus('Completed');
                $paymentHistoryDaoService->persistFlush($orderObject);
                
                
            } else {
                $responseArray = $response->getErrorMessages();
                if (count($responseArray) > 0) {
                    $errorMessage = $responseArray[0];
                   $logService->debug($errorMessage);     
                    $responseArray['status'] = 'fail';
                    $responseArray['errorMessage'] = $errorMessage;
                    if (strcmp($errorMessage, Constant::PAYPAL_ACK_ERROR) == 0) {
                        $responseArray['errorMessage'] = Constant::PAYPAL_CUSTOM_MSG;
                        $errorMessage = "Custom Message: - " . $responseArray['errorMessage'];
                        $logService->debug($errorMessage);     
                    }
                }
            }
        } catch (\Exception $exc) {
            $errorMessage = $exc->getMessage();
            $responseArray['status'] = 'fail';
            $responseArray['errorMessage'] = $errorMessage;
            $errorMessage = "With in catch block: - " . $errorMessage;
            $logService->debug($errorMessage);                 
        }
        return $responseArray;
    }

    /*
     * Delete order and cart item
     */

    public function deleteCartCookieAndSession($e)
    {

        \Application\Model\Utility::deleteCustomCookie($e);
        $cartSession     = new Container('cart');
        $paypalSession   = new Container('paypal');
        $succcessSession = new Container('Success');
        unset($cartSession->cartId);
        unset($paypalSession->cc);
        unset($paypalSession->cvv);
        unset($succcessSession->response);

        //$cartSession->getManager()->getStorage()->clear();
    }

    /*
     * Inserts package details into db
     */

    public function insertPackageDetailsIntoDb($orderAndPaymentDetails)
    {

        $userManagerService = $this->getUserManagerService();
        $currentDtTm = new \DateTime("now");

        $numberOfDays = $orderAndPaymentDetails['package_duration'];
        $nextMonthDateTime = new \DateTime('now');
        $nextMonthDateTime->modify("+{$numberOfDays} days");

        /* entry into user has package */
        $userHasPackageInputArray['user_id'] = $orderAndPaymentDetails['user_id'];
        $userHasPackageInputArray['package_id'] = $orderAndPaymentDetails['package_id'];
        $userHasPackageInputArray['package_has_credits_id'] = $orderAndPaymentDetails['phc_id'];
        $userHasPackageInputArray['package_effective_dt_tm'] = $currentDtTm;
        $userHasPackageInputArray['package_expiry_dt_tm'] = $nextMonthDateTime;
        $userHasPackageObject = $userManagerService->createUserHasPackageEntry($userHasPackageInputArray);

        /* entry into user credit history table */
        $userCreditHistoryInputArray['user_id'] = $orderAndPaymentDetails['user_id'];
        $userCreditHistoryInputArray['user_has_package_id'] = $userHasPackageObject->getId();
        $userCreditHistoryInputArray['total_credits'] = $orderAndPaymentDetails['package_credits'];
        $userCreditHistoryInputArray['used_credits'] = 0;
        $userCreditHistoryObject = $userManagerService->createUserCreditHistoryEntry($userCreditHistoryInputArray);

        if (is_object($userHasPackageObject) && is_object($userCreditHistoryObject)) {
            return true;
        } else {
            //TODO Need to this info            
            return false;
        }
    }

    /*
     * send email
     * @param object $user
     * @param string $password
     */

    public function sendOrderConfirmationMail($orderAndPaymentDetails)
    {
        $template = $this->getMailTemplateService()->getOrderConfirmationTemplate($orderAndPaymentDetails);
        
        $systemParamService = $this->getSystemParamService();
        $key = 'Payment Confirmation';
        $fromEmail = '';
        $fromEmail = $systemParamService->getSystemParamValueByKey($key);
        
        $mailService = $this->getMailService();
        $mailService->sendMail($template, $orderAndPaymentDetails['email'], $fromEmail);
    }

}
