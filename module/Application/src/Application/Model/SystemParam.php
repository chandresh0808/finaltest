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

namespace Application\Model;

use Application\Model\Constant as Constant;
use Application\Model\Utility as Utility;
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

class SystemParam extends \Application\Model\AbstractCommonServiceMutator
{ 

    public function getSystemParamValueByKey($key)
    { 
        $systemParamDAOService = $this->getSystemParamDaoService();
        
        $queryParameterArray['paramKey'] = $key;
        $entity = Constant::ENTITY_SYSTEM_PARAM;
        
        $systemParam = $systemParamDAOService->getEntityByParameterList($queryParameterArray, $entity);
        
        $paramValue = '';
        if (is_object($systemParam)) {
            $paramValue = $systemParam->getParamValue();
        }
        return $paramValue;
    }
}
