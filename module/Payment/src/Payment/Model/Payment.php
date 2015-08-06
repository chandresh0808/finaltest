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
class Payment extends \Application\Model\AbstractDao
{
    /*
     * Return curl request object
     */

    public function getCurlClient()
    {
        $client = new \Zend\Http\Client;
        $client->setMethod('POST');
        $client->setAdapter(new \Zend\Http\Client\Adapter\Curl);
        return $client;
    }

    /*
     * get speck config object
     */

    public function getSpeckConfig($paypalConfig)
    {
        $paypalConfig = new \SpeckPaypal\Element\Config($paypalConfig);
        return $paypalConfig;
    }

    /*
     * Return speck request object
     * @param array $paypalConfig
     * @param object $client
     * 
     * @return object $paypalRequest
     */

    public function getSpeckPaypalRequest($curlClientObject, $speckConfigObject)
    {

        $paypalRequest = new \SpeckPaypal\Service\Request;
        $paypalRequest->setClient($curlClientObject);
        $paypalRequest->setConfig($speckConfigObject);
        return $paypalRequest;
    }

    /*
     * Gets speck payment details
     * @param array $paymentArray
     * 
     * @return object $paymentDetails
     */

    public function getSpeckPaymentDetails($paymentDetailsInputArray)
    {
        $paymentDetails = new \SpeckPaypal\Element\PaymentDetails($paymentDetailsInputArray);
        return $paymentDetails;
    }

    /*
     * Gets speck DoDirect Payment 
     * @param array $paymentDetailsInputArray
     * 
     * @return object $payment
     */

    public function getSpeckDoDirectPayment($doDirectPaymentDetailsInputArray, $orderAndPaymentDetails)
    {

        $doDirectPaymentObject = new \SpeckPaypal\Request\DoDirectPayment($doDirectPaymentDetailsInputArray);
        $ipAddress = \Application\Model\Utility::getIdAddress();

        $fullName = explode(' ', $orderAndPaymentDetails['card_holder_name'], 2);
        $firstName = $fullName[0];
        $lastName = $fullName[1];

        $salt = Constant::PAYMENT_METHOD_SALT;
        $paypalSession = new Container('paypal');
        $creditCardNumber = \Application\Model\Utility::decrypt($paypalSession->cc, $salt);
        $cvv = \Application\Model\Utility::decrypt($paypalSession->cvv, $salt);
        $cartType = \Application\Model\Utility::getCardType($creditCardNumber);
        
        //$paypalSession->getManager()->getStorage()->clear();

        $doDirectPaymentObject->setCardNumber($creditCardNumber);
        $doDirectPaymentObject->setExpirationDate($orderAndPaymentDetails['expiry_year_month']);
        $doDirectPaymentObject->setFirstName($firstName);
        $doDirectPaymentObject->setLastName($lastName);
        $doDirectPaymentObject->setIpAddress($ipAddress);
        $doDirectPaymentObject->setCreditCardType($cartType);
        $doDirectPaymentObject->setCvv2($cvv);
        return $doDirectPaymentObject;
    }

    /*
     * Gets speck address
     * 
     * @return object $payment
     */

    public function getSpeckAddress($orderAndPaymentDetails)
    {

        $addressObject = new \SpeckPaypal\Element\Address;
        $addressObject->setStreet($orderAndPaymentDetails['address_1']);
        $addressObject->setStreet2($orderAndPaymentDetails['address_2']);
        $addressObject->setCity($orderAndPaymentDetails['city']);
        $addressObject->setState($orderAndPaymentDetails['state']);
        $addressObject->setZip($orderAndPaymentDetails['zip_code']);
        $addressObject->setCountryCode($orderAndPaymentDetails['country']);

        return $addressObject;
    }

    /*
     * Validate order details
     * 
     */

    public function validateOrderDetails($inputArray)
    {

        $result = array('valid' => true);
        $errorMessage = array();

        if (empty($inputArray['email'])) {
            $result['valid'] = false;
            $errorMessage['email'] = "Please enter email id";
        } else {
            $isEmailIdValid = \Application\Model\Utility::isEmailIdValid($inputArray['email']);
            if (!$isEmailIdValid) {
                $result['valid'] = false;
                $errorMessage['email'] = "Invalid emailid";
            } 
        }

        if (empty($inputArray['full_name'])) {
            $result['valid'] = false;
            $errorMessage['full_name'] = "Please enter full name";
        }

        if (empty($inputArray['phone_number'])) {
            $result['valid'] = false;
            $errorMessage['phone_number'] = "Please enter phone number";
        } else {
            $isPhoneValidValid = \Application\Model\Utility::hasAnyCharacter($inputArray['phone_number']);
            if ($isPhoneValidValid) {
                $result['valid'] = false;
                $errorMessage['phone_number'] = "Invalid phone number";
            } 
        }
        
        if (empty($inputArray['address_1'])) {
            $result['valid'] = false;
            $errorMessage['address_1'] = "Please enter address";
        }
        
        if (empty($inputArray['city'])) {
            $result['valid'] = false;
            $errorMessage['city'] = "Please enter city";
        }

        if (empty($inputArray['zip_code'])) {
            $result['valid'] = false;
            $errorMessage['zip_code'] = "Please enter zip code";
        }
        
        if (empty($inputArray['card_number'])) {
            $result['valid'] = false;
            $errorMessage['card_number'] = "Please enter card number";
        } else {
            $isCardValid = \Application\Model\Utility::hasAnyCharacter($inputArray['card_number']);
            if ($isCardValid) {
                $result['valid'] = false;
                $errorMessage['card_number'] = "Invalid card number";
            } 
        }
        
        if (empty($inputArray['card_holder_name'])) {
            $result['valid'] = false;
            $errorMessage['card_holder_name'] = "Please enter card holder name";
        }
        
        if (empty($inputArray['cvv_number'])) {
            $result['valid'] = false;
            $errorMessage['cvv_number'] = "Please enter cvv";
        } else {
            $isCvvValid = \Application\Model\Utility::hasAnyCharacter($inputArray['cvv_number']);
            if ($isCvvValid) {
                $result['valid'] = false;
                $errorMessage['cvv_number'] = "Invalid ccv";
            } 
        }
        
        $result['error'] = $errorMessage;
        return $result;
    }

}
