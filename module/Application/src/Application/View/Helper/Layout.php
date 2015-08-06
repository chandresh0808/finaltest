<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\View\Helper;

/**
 * Description of Layout
 *
 * 
 */
class Layout {

    /**
     * Layout title
     * 
     * @param  \Zend\Mvc\MvcEvent $e The MvcEvent instance
     * @return void
     */
    public function setLayoutTitle($e) {
        $siteName = 'Audit Companion';
        $matches = $e->getRouteMatch();
        if (!$matches) {
            $vm = $e->getViewModel();
            $vm->setTemplate('error/404');

            $e->setViewModel($vm);

            $response = $e->getResponse();
            if ($e->getRequest() instanceof HttpRequest)
                $response->setStatusCode(404);

            $e->setResponse($response);
            $e->setResult($vm);

            return false;
        }
        $action = $matches->getParam('action');

        // Getting the view helper manager from the application service manager
        $viewHelperManager = $e->getApplication()->getServiceManager()->get('viewHelperManager');

        // Getting the headTitle helper from the view helper manager
        $headTitleHelper = $viewHelperManager->get('headTitle');

        // Setting a separator string for segments
        $headTitleHelper->setSeparator(' | ');

        // Exceptional action names
        $exception = array(
            'sign-up' => 'Sign Up',
            'login' => 'Sign In',
         //   'manage-users' => 'Manage users'    
        );

        if (array_key_exists($action, $exception)) {
            $action = $exception[$action];
        }
        // Setting the site name as title segments
        $action = ucwords(str_replace('-', ' ', $action));
        
        $headTitleHelper->append($siteName);
        $headTitleHelper->append($action);
    }
   
}
