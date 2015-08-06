<?php
/* Value from partial view */
$cmsBaseLink = $this->cmsBaseLink;
$DMSAboutUsLink = $this->dmsAboutUslink;
$showNotLoggedInMenuOption = $this->showNotLoggedInMenuOption;
$userName = $this->userName;

/* Logged in */
$logRef = $this->url('user-account');
if ($showNotLoggedInMenuOption) {
    /* Not logged in */
    $logRef = $this->url('sign-in');
}
?>