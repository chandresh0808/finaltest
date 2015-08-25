<?php
/* To avoid below issue 
 * Maximum function nesting level of '100' reached, aborting
 */
ini_set('xdebug.max_nesting_level', 500);

/* Gets CMS Base link  based on Env */
$config = $this->application_config_helper();
$applicationEnv = getenv('APPLICATION_ENV');
$cmsBaseLink = $config['cmsBaseUrl'][$applicationEnv];
$DMSAboutUsLink = $config['DMS_url']['about_us'];

/* Displaying user name and hiding sign-up/sign-in menu if user is logged in */
$authenticationService = $this->authenticate_view_helper();
$showNotLoggedInMenuOption = 1;
if ($authenticationService->hasIdentity()) {
    $showNotLoggedInMenuOption = 0;
    $userObject = $authenticationService->getIdentity();
    $userName = $userObject->getFirstName() . " " . $userObject->getLastName();    
}
?>