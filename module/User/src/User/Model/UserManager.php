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

namespace User\Model;

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
class UserManager extends \Application\Model\AbstractCommonServiceMutator
{

    public function createUserSessionEntry($inputDataArray)
    {
        $userSessionDaoService = $this->getUserSessionDaoService();
        $userSessionObject = $userSessionDaoService->createUpdateEntity($inputDataArray);
        return $userSessionObject;
    }

    /*
     * Create a entry in user_has_salt table
     * @param array $inputDataArray
     * 
     * @return object $userHasSalt
     * 
     */

    public function createAuthSaltEntry($inputDataArray)
    {
        $systemSaltDaoService = $this->getSystemSaltDaoService();
        $systemSaltObject = $systemSaltDaoService->createUpdateEntity($inputDataArray);
        return $systemSaltObject;
    }

    /*
     * Check for user has session
     * @param string $sessionGuid
     * 
     * @return bool $result
     * 
     */

    public function isUserHasSession($sessionGuid)
    {
        $userSessionDaoService = $this->getUserSessionDaoService();
        $queryParamArray['sessionGuid'] = $sessionGuid;
        $entity = Constant::ENTITY_USER_SESSION;
        $userSessionObject = $userSessionDaoService->getEntityByParameterList($queryParamArray, $entity);
        $result = false;
        if (is_object($userSessionObject)) {

            /* Check is session is older then one hour */
            $currentEpochTime = \Application\Model\Utility::getCurrentEpochTime();
            $dbEpochTime = $userSessionObject->getLastRequestDtTm();
            $tenMinEpochTimeValue = Constant::USER_SESSION_EXPIRE_TIME;

            $checkEpochTime = $dbEpochTime + $tenMinEpochTimeValue;

            if ($checkEpochTime < $currentEpochTime) {
                return false;
            }

            /* update the user_session updatedDtTm Field */
            $userSessionObject->setLastRequestDtTm($currentEpochTime);
            $userSessionObject->setUpdatedDtTm(new \DateTime("now"));
            $userSessionDaoService->update($userSessionObject);

            $result = $userSessionObject;
        }
        return $result;
    }

    /*
     * Read user salt using ref_id
     * @param int $refId
     * 
     * @return object $systemSaltObject
     */

    public function getSystemSaltUsingId($refId)
    {
        $systemSaltDaoService = $this->getSystemSaltDaoService();
        $queryParamArray['id'] = $refId;
        $entity = Constant::ENTITY_SYSTEM_SALT;
        $systemSaltObject = $systemSaltDaoService->getEntityByParameterList($queryParamArray, $entity);
        return $systemSaltObject;
    }

    /*
     * Read user role name 
     * @param int $roleName
     * 
     * @return object $roleObject
     */

    public function getRoleIdUsingName($roleName)
    {
        $roleDaoService = $this->getRoleDaoService();
        $queryParamArray['name'] = $roleName;
        $entity = Constant::ENTITY_ROLE;
        $roleObject = $roleDaoService->getEntityByParameterList($queryParamArray, $entity);
        return $roleObject;
    }

    /*
     * delete user session
     * @param object $userSession
     * 
     * @return object $userSession
     */

    public function deleteUserSession($userSession)
    {
        $userSessionDaoService = $this->getUserSessionDaoService();
        $userSession->setDeleteFlag(Constant::SET_DELETE_FLAG);
        $userSession = $userSessionDaoService->update($userSession);
        return $userSession;
    }

    /*
     * Delete all user session inactive for 1 hour
     */

    public function deleteInactiveUserSession()
    {
        $userSessionDaoService = $this->getUserSessionDaoService();
        $result = $userSessionDaoService->deleteInactiveUserSession();
        return $result;
    }

    /*
     * Create new user
     */

    public function createUser($postDataArray, $signUpForm)
    {

        $userService = $this->getUserService();

        $inputArray['email_id'] = $postDataArray['email'];
        $inputArray['password'] = $postDataArray['password'];
        $inputArray['confirm_password'] = $postDataArray['confirm_password'];
        $inputArray['first_name'] = htmlspecialchars($postDataArray['first_name']);
        $inputArray['last_name'] = htmlspecialchars($postDataArray['last_name']);
        $inputArray['phone_number'] = str_replace("-", '', $postDataArray['phone_number']);
        $inputArray['terms_condition'] = $postDataArray['terms_condition'];
        $inputArray['free_trial_flag'] = Constant::FLAG_FREE_TRAIL_SET;
        $inputArray['active_package_flag'] = Constant::FLAG_ACTIVE_PACKAGE_UNSET;
        $inputArray['activation_code'] = Null;
        $inputArray['active_flag'] = 0;
        $inputArray['is_blocked'] = 0; 

        $responseArray = $userService->validateUser($inputArray);

        if (false == $responseArray['valid']) {
            $response['status'] = 'fail';
            $response['error_message'] = $responseArray['error'];
            $response['sign_up_form'] = $userService->populateSignUpForm($signUpForm, $postDataArray);
        } else {
            $inputArray['password'] = md5($postDataArray['password']);

            $userDaoService = $this->getUserDaoService();

            $userObject = $userDaoService->createUpdateEntity($inputArray);

            if (is_object($userObject)) {
                $insertedUserId = $userObject->getId();
                $role = 'user';
                $this->createUserRole($insertedUserId, $role);
                $activationCode = \Application\Model\Utility::urlBase64Encode($inputArray['email_id']);
                $this->sendSignUpConfirmationMail($userObject, $activationCode);
            }

            $response['status'] = 'success';
            $response['user'] = $userObject;
        }

        return $response;
    }

    /*
     * Create Associate user
     */

    public function createAssociateUser($postDataArray, $parentUserObject)
    {

        $userService = $this->getUserService();
        $inputArray['first_name'] = htmlspecialchars($postDataArray['associate_first_Name']);
        $inputArray['last_name'] = htmlspecialchars($postDataArray['associate_last_name']);
        $inputArray['email_id'] = $postDataArray['associate_email'];

        $responseArray = $userService->validateAssociateUser($inputArray);
        if (false == $responseArray['valid']) {
            $response['status'] = 'fail';
            $response['error_message'] = $responseArray['error'];
        } else {
            $userDaoService = $this->getUserDaoService();

            $parentUserId = $parentUserObject->getId();
            $inputArray['parent_user_id'] = $parentUserId;
            $inputArray['free_trial_flag'] = Constant::FLAG_FREE_TRAIL_SET;
            $inputArray['active_package_flag'] = Constant::FLAG_ACTIVE_PACKAGE_UNSET;
            $inputArray['active_flag'] = 0;
            $inputArray['is_blocked'] = 0; 
            $userObject = $userDaoService->createUpdateEntity($inputArray);

            if (is_object($userObject)) {
                $insertedUserId = $userObject->getId();
                $role = 'associate';
                $this->createUserRole($insertedUserId, $role);
                $activationCode = \Application\Model\Utility::urlBase64Encode($inputArray['email_id']) . "/" . $role;
                $this->sendSignUpConfirmationMail($userObject, $activationCode);
            }

            $response['status'] = 'success';
            $response['message'] = 'Associate user account created successfully! Users get listed below, once they activate their account.';
        }
        return $response;
    }

    /*
     * Create user role
     */

    public function createUserRole($userId, $role)
    {
        $roleId = $this->getRoleIdUsingName($role)->getId();
        $inputArray['user_id'] = $userId;
        $inputArray['role_id'] = $roleId;
        $userHasRoleDaoService = $this->getUserHasRoleDaoService();
        $userHasRoleObject = $userHasRoleDaoService->createUpdateEntity($inputArray);
    }

    /*
     * send email
     * @param object $user
     * @param string $password
     */

    public function sendSignUpConfirmationMail($userObject, $activationCode)
    {
        $template = $this->getMailTemplateService()->getSignUpConfirmationTemplate($userObject, $activationCode);

        $systemParamService = $this->getSystemParamService();
        $key = 'User Sign up';
        $fromEmail = '';
        $fromEmail = $systemParamService->getSystemParamValueByKey($key);

        $mailService = $this->getMailService();
        $mailService->sendMail($template, $userObject->getUsername(), $fromEmail);
    }

    /*
     * Activate user
     * @param string $activationCode
     * 
     * @return activated user id
     */

    public function activateUser($activateCode)
    {
        $userDaoService = $this->getUserDaoService();

        $decodedActivatonCode = \Application\Model\Utility::urlBase64Decode($activateCode);
        $queryParameterArray['username'] = $decodedActivatonCode;
        //$queryParameterArray['activeFlag'] = 0;
        $entity = Constant::ENTITY_USER;

        $user = $userDaoService->getEntityByParameterList($queryParameterArray, $entity);
        if (is_object($user)) {
            $user->setActiveFlag(1);
            $userDaoService->persistFlush($user);

            $this->sendUserWelcomeMail($user);

            return true;
        }
        return false;
    }

    /*
     * send email
     * @param object $user
     * @param string $password
     */

    public function sendUserWelcomeMail($userObject)
    {
        $template = $this->getMailTemplateService()->getUserWelcomeTemplate($userObject);

        $systemParamService = $this->getSystemParamService();
        $key = 'User Sign up';
        $fromEmail = '';
        $fromEmail = $systemParamService->getSystemParamValueByKey($key);

        $mailService = $this->getMailService();
        $mailService->sendMail($template, $userObject->getUsername(), $fromEmail);
    }

    /*
     * Get user details
     */

    public function getUserAccountDetails()
    {
        $authenticationService = $this->getAuthenticationService();
        $userObject = $authenticationService->getIdentity();
        if (is_object($userObject)) {
            $serializedData = $this->convertObjectToArrayUsingJmsSerializer($userObject);
            return $userObject;
        }
        return false;
    }

    /*
     * Get user using given param
     */

    public function getUserByParameterList($inputArray)
    {

        $userDaoService = $this->getUserDaoService();
        $entity = Constant::ENTITY_USER;

        $user = $userDaoService->getEntityByParameterList($inputArray, $entity);
        if (is_object($user)) {
            return $user;
        }
        return false;
    }

    /*
     * validate reset password
     * 
     * @param array $postData
     * 
     * @return array $responseArray
     * 
     */

    public function validateResetForgotPasswordData($postData)
    {
        $userService = $this->getUserService();
        $responseArray = $userService->validateResetForgotPasswordData($postData);
        return $responseArray;
    }

    /*
     * populate user account form
     */

    public function populateUserAccountForm($userAccountForm, $userObject)
    {

        $stateList = $this->getStateList();
        foreach ($stateList as $state) {
            $stateListArray[$state->getStateCd()] = $state->getStateName();
        }
        $userAccountForm->get('state')->setValueOptions($stateListArray);
        if ($userObject->getFirstName()) {
            $userAccountForm->get('first_name')->setValue($userObject->getFirstName());
        }

        if ($userObject->getLastName()) {
            $userAccountForm->get('last_name')->setValue($userObject->getLastName());
        }

        if ($userObject->getUsername()) {
            $userAccountForm->get('email')->setValue($userObject->getUsername());
        }
        
        if ($userObject->getPhoneNumber()) {
            $userAccountForm->get('phone_number')->setValue($userObject->getPhoneNumber());
        }

        if ($userObject->getAddress1()) {
            $userAccountForm->get('address_1')->setValue($userObject->getAddress1());
        }
        if ($userObject->getAddress2()) {
            $userAccountForm->get('address_2')->setValue($userObject->getAddress2());
        }

        if ($userObject->getCity()) {
            $userAccountForm->get('city')->setValue($userObject->getCity());
        }

        if ($userObject->getState()) {
            $userAccountForm->get('state')->setValue($userObject->getState());
        } else {
            $userAccountForm->get('state')->setValue('OH');
        }

        if ($userObject->getZipcode()) {
            $userAccountForm->get('zip_code')->setValue($userObject->getZipcode());
        }

        return $userAccountForm;
    }

    /*
     * Get user using given param
     */

    public function getStateList($countryId = 223)
    {
        $userDaoService = $this->getUserDaoService();
        $entity = Constant::ENTITY_STATE;
        $inputArray['countryId'] = $countryId;
        $stateObject = $userDaoService->getEntityListByParameterList($inputArray, $entity);
        return $stateObject;
    }

    /*
     * update user account
     */

    public function updateUserAccount($postDataArray, $userObject, $userAccountForm)
    {
        $userService = $this->getUserService();
        $validationResponse = $userService->validateUserAccountData($postDataArray, $userObject);

        if (false == $validationResponse['valid']) {
            $response['status'] = 'fail';
            $response['error_message'] = $validationResponse['error'];
            $response['user_account_form'] = $this->populateUserAccountFormWithPostData($userAccountForm, $postDataArray);
        } else {
            $userObject = $this->populateUserObjectWithPostData($postDataArray, $userObject);
            $userDaoService = $this->getUserDaoService();
            $userObject = $userDaoService->persistFlush($userObject);

            if (is_object($userObject)) {
                $response['status'] = 'success';
                $response['success_message'] = 'User information updated successfully';
            }
        }

        return $response;
    }

    /*
     * Populate user object with post data
     */

    public function populateUserObjectWithPostData($postDataArray, $userObject)
    {

        if ($postDataArray['first_name']) {
            $userObject->setFirstName(htmlspecialchars($postDataArray['first_name']));
        }

        if ($postDataArray['last_name']) {
            $userObject->setLastName(htmlspecialchars($postDataArray['last_name']));
        }

        if ($postDataArray['password']) {
            $userObject->setPassword(md5($postDataArray['password']));
        }        
        
        $userObject->setPhoneNumber(str_replace("-", '', $postDataArray['phone_number']));
        $userObject->setAddress1(htmlspecialchars($postDataArray['address_1']));
        $userObject->setAddress2(htmlspecialchars($postDataArray['address_2']));
        $userObject->setCity(htmlspecialchars($postDataArray['city']));
        $userObject->setState($postDataArray['state']);
        $userObject->setCountry(htmlspecialchars($postDataArray['country']));
        $userObject->setZipcode($postDataArray['zip_code']);

        return $userObject;
    }

    /*
     * populate user account form
     */

    public function populateUserAccountFormWithPostData($userAccountForm, $postDataArray)
    {
        $userAccountForm->get('first_name')->setValue($postDataArray['first_name']);
        $userAccountForm->get('last_name')->setValue($postDataArray['last_name']);
        $userAccountForm->get('phone_number')->setValue($postDataArray['phone_number']);
        $userAccountForm->get('address_1')->setValue($postDataArray['address_1']);
        $userAccountForm->get('address_2')->setValue($postDataArray['address_2']);
        $userAccountForm->get('city')->setValue($postDataArray['city']);
        $userAccountForm->get('state')->setValue($postDataArray['state']);
        $userAccountForm->get('zip_code')->setValue($postDataArray['zip_code']);
        return $userAccountForm;
    }

    /*
     * Create user has package entry
     */

    public function createUserHasPackageEntry($inputDataArray)
    {
        $userHasPackageDaoService = $this->getUserHasPackageDaoService();
        $userHasPackageDaoObject = $userHasPackageDaoService->createUpdateEntity($inputDataArray);
        return $userHasPackageDaoObject;
    }

    /*
     * Create user has package entry
     */

    public function createUserCreditHistoryEntry($inputDataArray)
    {
        $userCreditHistoryDaoService = $this->getUserCreditHistoryDaoService();
        $userCreditHistoryDaoObject = $userCreditHistoryDaoService->createUpdateEntity($inputDataArray);
        return $userCreditHistoryDaoObject;
    }

    /*
     * Read user id 
     * @param int $userId
     * 
     * @return object $roleObject
     */

    public function getRoleByUserId($userId)
    {
        $userHasRoleDaoService = $this->getUserHasRoleDaoService();
        $queryParamArray['userId'] = $userId;
        $entity = Constant::ENTITY_USER_HAS_ROLE;
        $userHasRoleObject = $userHasRoleDaoService->getEntityByParameterList($queryParamArray, $entity);
        if (is_object($userHasRoleObject)) {
            $roleId = $userHasRoleObject->getRoleId();
            $roleDaoService = $this->getRoleDaoService();
            $queryParamRoleArray['id'] = $roleId;
            $entityRole = Constant::ENTITY_ROLE;
            $roleObject = $roleDaoService->getEntityByParameterList($queryParamRoleArray, $entityRole);
        }
        return $roleObject;
    }

    /*
     * Read User Object
     * @return analysis credit history array
     */

    public function getUserCreditHistory($userObject)
    {

        $expiredUsedCount = 0;
        $totalExpiredCount = 0;

        $userHasPackageDaoService = $this->getUserHasPackageDaoService();
        $userCreditHistoryDaoService = $this->getUserCreditHistoryDaoService();

        $expiredPackages = $userHasPackageDaoService->expiredPackages($userObject->getId());

        foreach ($expiredPackages as $expiredPackage) {
            $expiredUsedCount += $expiredPackage['credit_analysis_points_used'];
            $totalExpiredCount += $expiredPackage['total_credit_analysis_points'];
        }

        $expired_count = $totalExpiredCount - $expiredUsedCount;

        $userHasPackageObjectList = $userObject->getUserHasPackageList();
        $totalPoints = 0;
        $creditPoints = 0;
        foreach ($userHasPackageObjectList as $userHasPackageObject) {
            $userCreditHistoryObject = $userHasPackageObject->getUserCreditHistory()->first();
            $usedCreditPoints += $userCreditHistoryObject->getCreditAnalysisPointsUsed();
            $totalPoints += $userCreditHistoryObject->getTotalCreditAnalysisPoints();
        }
        $availablePoints = ($totalPoints - $usedCreditPoints);
        $usedCreditPoints += $expiredUsedCount;
        
        if ($availablePoints < 0) {
            $availablePoints = 0;
        }
        
        $responseArray['remainingPoints'] = $availablePoints;
        $responseArray['usedCreditPoints'] = $usedCreditPoints;
        $responseArray['expiredCredits'] = $expired_count;
        return $responseArray;
    }

    public function dataForAssociateUserList($paramArray)
    {
        $userDaoService = $this->getUserDaoService();

        $sortColumnNameMap = array(0 => "u.first_name", 1 => "u.last_name", 2 => "u.username");
        $inputParamArray['associate_user_list'] = true;
        $inputParamArray['user_id'] = $paramArray['user_id'];
        $inputParamArray['sort_column'] = $sortColumnNameMap[$paramArray['iSortCol_0']];
        $inputParamArray['sort_order'] = $paramArray['sSortDir_0'];
        $inputParamArray['limit'] = $paramArray['iDisplayLength'];
        $inputParamArray['offset'] = $paramArray['iDisplayStart'];

        $completedAssociateUserRequestResponse = $userDaoService->userListRequest($inputParamArray);

        $completedAssociateUserRequestArray = $completedAssociateUserRequestResponse['user_request_array'];
        $count = $completedAssociateUserRequestResponse['total_count'];                
        
        $inputArrayForDataTables = \Application\Model\Utility::customizeResultForDataTable($completedAssociateUserRequestArray, $count);

        return $inputArrayForDataTables;
    }

    public function deleteUser($userId)
    {
        try {
            $userDaoService = $this->getUserDaoService();
            $userObject = $userDaoService->read($userId);

            if (is_object($userObject)) {
                $userObject->setDeleteFlag(1);
                $userObject = $userDaoService->persistFlush($userObject);
                if (is_object($userObject)) {
                    $response = \Application\Model\Utility::getResponseArray('success', 'User deleted successfully');
                } else {
                    $response = \Application\Model\Utility::getResponseArray('fail', 'Not able to delete, Please try again');
                }
            }
        } catch (Exception $ex) {
            $response = \Application\Model\Utility::getResponseArray('fail', 'Not able to delete, Please try again');
        }

        return $response;
    }
    
    
    public function adminDeleteUser($userId)
    {
        $deleteFailedUsers = '';
        $userDaoService = $this->getUserDaoService();
        $inputParamArray['associate_user_list_admin'] = true;
        $inputParamArray['user_id'] = $userId;
        $associateUserList = $userDaoService->userListRequest($inputParamArray);        
        if (!empty($associateUserList)) {
            foreach($associateUserList['user_request_array'] as $associateUser){                
                $deleteResponse = $this->deleteUser($associateUser['id']);
                if ($deleteResponse['status'] == 'fail') {
                    $deleteFailedUsers .= $associateUser['id'];
                }               
            }
        }
        $deleteResponse = $this->deleteUser($userId);
        if ($deleteResponse['status'] == 'fail') {
            $deleteFailedUsers .= $userId;
        }
        
        if (empty($deleteFailedUsers)){
            $response = \Application\Model\Utility::getResponseArray('success', 'User deleted successfully');
        } else {
            $response = \Application\Model\Utility::getResponseArray('fail', $deleteFailedUsers.'User could not be deleted successfully');
        }
        return $response;
    }
    
    

    public function setPassword($activateCode)
    {
        $decodedActivatonCode = \Application\Model\Utility::urlBase64Decode($activateCode);
        $inputArray['username'] = $decodedActivatonCode;
        $userObject = $this->getUserByParameterList($inputArray);
        if (is_object($userObject)) {
            $verificatioinCode = \Application\Model\Utility::generateSalt(Constant::SALT_CHAR_LENGTH);
            $userObject->setActivationCode($verificatioinCode);
            $userObject = $this->save($userObject);
            if (is_object($userObject)) {
                return $verificatioinCode;
            }
        }
        return false;
    }

    public function dataForUserList($paramArray)
    {
        $userDaoService = $this->getUserDaoService();

        $sortColumnNameMap = array(0 => "u.last_name", 1 => "u.username", 2 => "available", 4 => "u.active_flag");
        $inputParamArray['sort_column'] = $sortColumnNameMap[$paramArray['iSortCol_0']];
        $inputParamArray['sort_order'] = $paramArray['sSortDir_0'];
        $inputParamArray['limit'] = $paramArray['iDisplayLength'];
        $inputParamArray['offset'] = $paramArray['iDisplayStart'];
        $inputParamArray['search_query'] = $paramArray['sSearch'];

        $completedUserRequestResponse = $userDaoService->userListRequest($inputParamArray);        
        $completedUserResponseArray = $completedUserRequestResponse['user_request_array'];
        $count = $completedUserRequestResponse['total_count'];
        $inputArrayForDataTables = array();
        foreach ($completedUserResponseArray as $userArray) {                        
                         
            if ($userArray['active_flag'] == 1) {
                $status = 'Active';
            } else {
                $status = 'Inactive';
            }

            if ($userArray['is_blocked'] == 1) {
                $status = 'Blocked';
            }                
            
            if (empty($userArray['available'])) {
                $userArray['available'] = 0;
            }

            $completedUserRequestarray[] = array(
                'id' => $userArray['id'],
                'name' => $userArray['last_name'] . ', ' . $userArray['first_name'],
                'email' => $userArray['username'],
                'available_credit' => $userArray['available'],
                'last_login' => $userArray['last_login'],
                'status' => $status,
                'roleId' => $roleId,
                'state' => $userArray['active_flag']
            );
            
        }
        if (count($completedUserRequestarray) <= 0) {
            $completedUserRequestarray = array();
        }
        
        $inputArrayForDataTables = \Application\Model\Utility::customizeResultForDataTable($completedUserRequestarray, $count);        

        return $inputArrayForDataTables;
    }
    
    
    public function adminBlockUser($userId) {
        $blockFailedUsers = '';
        $userDaoService = $this->getUserDaoService();
        $inputParamArray['associate_user_list_admin'] = true;
        $inputParamArray['user_id'] = $userId;
        $associateUserList = $userDaoService->userListRequest($inputParamArray);        
        if (!empty($associateUserList)) {
            foreach($associateUserList['user_request_array'] as $associateUser){                
                $blockResponse = $this->blockUser($associateUser['id']);
                if ($blockResponse['status'] == 'fail') {
                    $blockFailedUsers .= $associateUser['id'];
                } else {
                    $associateUserIdArray[] = $associateUser['id'];
                }
                
            }
        }
        $blockResponse = $this->blockUser($userId);
        if ($blockResponse['status'] == 'fail') {
            $blockFailedUsers .= $userId;
        }
        
        if (empty($blockFailedUsers)){
            $response['status'] = 'success';
            $response['message'] = 'User blocked successfully';
            $response['associateUserIdArray'] = $associateUserIdArray;
        } else {
            $response = \Application\Model\Utility::getResponseArray('fail', $blockFailedUsers.'User could not be bocked');
        }
        return $response;
        
    }
    
    
    public function adminUnBlockUser($userId) {
        $unBlockFailedUsers = '';        
        $userDaoService = $this->getUserDaoService();
        $inputParamArray['associate_user_list_admin'] = true;
        $inputParamArray['user_id'] = $userId;
        $associateUserList = $userDaoService->userListRequest($inputParamArray);
        if (!empty($associateUserList)) {
            foreach($associateUserList['user_request_array'] as $associateUser){                
                $unBlockResponse = $this->unBlockUser($associateUser['id']);                
                if ($unBlockResponse['status'] == 'fail') {
                    $unBlockFailedUsers .= $associateUser['id'];
                } else {                    
                    $userObject = $userDaoService->read($associateUser['id']);
                    $userObject->setActiveFlag(1);
                    $userObject = $this->save($userObject);
                    $associateUserIdArray[] = $associateUser['id'];
                }
                
            }
        }
        $unBlockResponse = $this->unBlockUser($userId);
        if ($unBlockResponse['status'] == 'fail') {
            $unBlockFailedUsers .= $userId;
        }
        
        if (empty($unBlockFailedUsers)){
            $response['status'] = 'success';
            $response['message'] = 'User unblocked successfully';
            $response['associateUserIdArray'] = $associateUserIdArray;            
        } else {
            $response = \Application\Model\Utility::getResponseArray('fail', $unBlockFailedUsers.'User could not be unbocked');
        }
        return $response;
        
    }
    
    
    
    
    public function blockUser($userId)
    {
        try {
            $userDaoService = $this->getUserDaoService();
            $userObject = $userDaoService->read($userId);

            if (is_object($userObject)) {
                $userObject->setIsBlocked(1);
                $userObject = $userDaoService->persistFlush($userObject);
                if (is_object($userObject)) {
                    $response = \Application\Model\Utility::getResponseArray('success', 'User blocked successfully');
                } else {
                    $response = \Application\Model\Utility::getResponseArray('fail', 'Not able to block, Please try again');
                }
            }
        } catch (Exception $ex) {
            $response = \Application\Model\Utility::getResponseArray('fail', 'Not able to block, Please try again');
        }

        return $response;
    }

    public function unBlockUser($userId)
    {
        try {
            $userDaoService = $this->getUserDaoService();
            $userObject = $userDaoService->read($userId);

            if (is_object($userObject)) {
                $userObject->setIsBlocked(0);
                $userObject = $userDaoService->persistFlush($userObject);
                if (is_object($userObject)) {
                    $response = \Application\Model\Utility::getResponseArray('success', 'User unblocked successfully');
                } else {
                    $response = \Application\Model\Utility::getResponseArray('fail', 'Not able to unblock, Please try again');
                }
            }
        } catch (Exception $ex) {
            $response = \Application\Model\Utility::getResponseArray('fail', 'Not able to unblock, Please try again');
        }

        return $response;
    }

    /*
     * Send reset password
     */

    public function sendResetPasswordFromAdmin($postData)
    {
        $systemParamService = $this->getSystemParamService();
        $key = 'Support';
        $fromEmail = $systemParamService->getSystemParamValueByKey($key);

        $inputArray['id'] = $postData['user_id'];

        $userObject = $this->getUserByParameterList($inputArray);
        if (is_object($userObject)) {
            $verificatioinCode = \Application\Model\Utility::generateSalt(Constant::SALT_CHAR_LENGTH);
            $userObject->setActivationCode($verificatioinCode);
            $userObject->setActiveFlag(0);
            $userObject = $this->save($userObject);
            if (is_object($userObject)) {
                $template = $this->getMailTemplateService()->getForgotPasswordTemplate($userObject, $verificatioinCode);
                $email = $userObject->getUsername();
                $this->getMailService()->sendMail($template, $email, $fromEmail);
                $response['status'] = 'success';
                $response['message'] = "Password reset link has been sent to user";
            } else {
                $response['status'] = 'fail';
                $response['message'] = "Not able to generate verification code";
            }
        } else {
            $response['status'] = 'fail';
            $response['message'] = "Not able to send password reset link, try again";
        }
        return $response;
    }

    /*
     * Add custom packages
     */

    public function addCustomPackage($postData)
    {

        $userHasPackageDaoService = $this->getUserHasPackageDaoService();
        $packageHasCreditsDaoService = $this->getPackageHasCreditsDaoService();


        if (empty($postData['custom_credit']) || $postData['custom_credit'] <= 0 || $postData['custom_credit'] > 999) {
            $response['status'] = 'fail';
            $response['message'] = "Please enter valid number between 1 to 999";
            return $response;
        }
        
        
        $isUserObject = $this->isUserActive($postData['user_id']);
        if (!is_object($isUserObject)) {
            return $isUserObject;
        }
        
        
        $userCreditHistoryArray = $this->getUserCreditHistory($isUserObject); 
        $availablePoints = $userCreditHistoryArray['remainingPoints'];
        
        $totalPoints = $availablePoints + $postData['custom_credit'];
                
        if ($totalPoints > Constant::CUSTOM_PACKAGE_MAX_POINT) {
            $response['status'] = 'fail';
            $response['message'] = "Your credit points are exceeding 999";
            return $response;
        }
                
        $queryParamRoleArray['type'] = Constant::CUSTOM_PACKAGE_TYPE;
        $entityRole = Constant::ENTITY_PACKAGE;
        $packageObject = $userHasPackageDaoService->getEntityByParameterList($queryParamRoleArray, $entityRole);
        $packageId = $packageObject->getId();
        $packageDuration = Constant::CUSTOM_PACKAGE_DURATION;
        $totalCredits = $postData['custom_credit'];
        $expireDateFromUser = $postData['expire_date'];        
                
        $packageHasCreditsInputArray['package_id'] = $packageId;
        $packageHasCreditsInputArray['package_duration'] = $packageDuration;
        $packageHasCreditsInputArray['total_credits'] = $totalCredits;
        $packageHasCreditsInputArray['package_amount'] = 0;
        $packageHasCreditsInputArray['max_report_per_day'] = 0;


        $packageHasCreditsObject = $packageHasCreditsDaoService->createUpdateEntity($packageHasCreditsInputArray);

        /* entry into user has package */
        $currentDtTm = new \DateTime("now");
        //$nextMonthDateTime = new \DateTime('now');
        //$nextMonthDateTime->modify("+{$packageDuration} days");
        $nextMonthDateTime = new \DateTime($expireDateFromUser . "23:59:59");
                
        $userHasPackageInputArray['user_id'] = $postData['user_id'];
        $userHasPackageInputArray['package_id'] = $packageId;
        $userHasPackageInputArray['package_has_credits_id'] = $packageHasCreditsObject->getId();
        $userHasPackageInputArray['package_effective_dt_tm'] = $currentDtTm;
        $userHasPackageInputArray['package_expiry_dt_tm'] = $nextMonthDateTime;
        $userHasPackageObject = $this->createUserHasPackageEntry($userHasPackageInputArray);

        /* entry into user credit history table */
        $userCreditHistoryInputArray['user_id'] = $postData['user_id'];
        $userCreditHistoryInputArray['user_has_package_id'] = $userHasPackageObject->getId();
        $userCreditHistoryInputArray['total_credits'] = $totalCredits;
        $userCreditHistoryInputArray['used_credits'] = 0;
        $userCreditHistoryObject = $this->createUserCreditHistoryEntry($userCreditHistoryInputArray);

        if (is_object($userHasPackageObject) && is_object($userCreditHistoryObject)) {
            $response['status'] = 'success';
            $response['message'] = "{$totalCredits} Credit point(s) added successfully";
        } else {
            $response['status'] = 'fail';
            $response['message'] = "Not able to add credit points, Try again";
        }
        return $response;
    }

    public function expireCustomPackage($postData, $loggedInObject)
    {

        if (empty($postData['custom_credit']) || $postData['custom_credit'] <= 0 || $postData['custom_credit'] > $postData['remaining_points']) {
            $response['status'] = 'fail';
            $response['message'] = "Please enter valid number between 1 to {$postData['remaining_points']}";
            return $response;
        }
        
        $isObject = $this->isUserActive($postData['user_id']);
        if (!is_object($isObject)) {
            return $isObject;
        }
        
        $userObject = $isObject;
        $userDaoService = $this->getUserDaoService();
        $removeCreditPoints = $postData['custom_credit'];

        try {

            //$inputArray['id'] = $postData['user_id'];
            //$userObject = $this->getUserByParameterList($inputArray);
            $userHasPackageObjectList = $userObject->getUserHasPackageList();

            foreach ($userHasPackageObjectList as $userHasPackageObject) {
                $userCreditHistoryObjectList = $userHasPackageObject->getUserCreditHistory();
                foreach ($userCreditHistoryObjectList as $userCreditHistoryObject) {
                    $totalPoints = $userCreditHistoryObject->getTotalCreditAnalysisPoints();

                    if ($totalPoints > 0) {

                        if ($removeCreditPoints == 0) {
                            $response['status'] = 'success';
                            $response['message'] = "{$postData['custom_credit']} credit point(s) removed successfully";
                            return $response;
                        }

                        if ($totalPoints > $removeCreditPoints) {
                            $totalPoints = $totalPoints - $removeCreditPoints;
                            $removeCreditPoints = 0;
                        } else {
                            $removeCreditPoints = $removeCreditPoints - $totalPoints;
                            $totalPoints = 0;
                        }
                        $userCreditHistoryObject->setTotalCreditAnalysisPoints($totalPoints);
                        $userDaoService->save($userCreditHistoryObject);
                    }
                }
            }
        } catch (Exception $ex) {
            $response['status'] = 'fail';
            $response['message'] = "Not able to remove credit points, try again";
            return $response;
        }

        
            /* activity log start */
           $systemActivityDaoService = $this->getSystemActivityDaoService();                                
           $code = Constant::ACTIVITY_CODE_ACR;
           $userId = $loggedInObject->getId();
           $fullName = $loggedInObject->getFirstName() . " " . $loggedInObject->getLastName();
           $comment = "{$fullName} has removed {$postData['custom_credit']} credits";
           $systemActivityDaoService->createSystemActivityLog($code, $userId, $comment);
           /* activity log end */
        
        if ($removeCreditPoints == 0) {
            $response['status'] = 'success';
            $response['message'] = "{$postData['custom_credit']} credit point(s) are removed successfully";
            return $response;
        }
    }
    
    
    /*
     * Check is user active
     */
    
    public function isUserActive($userId) {
                
        $inputArray['id'] = $userId;
        $inputArray['activeFlag'] = 1;
        $inputArray['isBlocked'] = 0;
        $userObject = $this->getUserByParameterList($inputArray);
        
        if (!is_object($userObject)) {
            $response['status'] = 'fail';
            $response['message'] = "User is inactive/blocked";                                
            return $response;
        }
        return $userObject;
    }

}
