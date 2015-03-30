<?php

/**
 * Provides Rest Api for CMS
 * 
 * PHP version 5
 * 
 * @category   Module
 * @package    Api
 * @subpackage Controller
 * @author     Costrategix Team <team@costrategix.com>
 * @copyright  2015 CoS
 * @license    http://www.costrategix.com Proprietary 
 * @version    GIT: 1.7
 * @link       http://www.costrategix.com 
 * 
 */


namespace Api\Controller;

use Application\Controller\AbstractCoreController as AbstractCoreController;
use Zend\View\Model\ViewModel;

class CMSApiController extends AbstractCoreController
{
    public function indexAction()
    {
        return new ViewModel();
    }
}
