<?php

/**
 * Defines a Application Module functionality
 * 
 * PHP version 5
 * 
 * @category   Module
 * @package    Application
 * @subpackage Controller
 * @author     Costrategix Team <team@costrategix.com>
 * @copyright  2015 CoS
 * @license    http://www.costrategix.com Proprietary 
 * @version    GIT: 1.7
 * @link       http://www.costrategix.com 
 * 
 */

namespace Application\Controller;

use Application\Controller\AbstractCoreController as AbstractCoreController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractCoreController
{
    public function indexAction()
    {
        return new ViewModel();
    }
}
