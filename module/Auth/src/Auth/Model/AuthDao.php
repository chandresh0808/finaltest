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

namespace Auth\Model;
 
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

class AuthDao extends \Application\Model\AbstractCommonServiceMutator
{
    
    /**
     * Get a user object using paramters
     * 
     * @param List $paramList paramter list
     * 
     * @return User\Entity\User
     */
    public function getUserByParameterList($paramList)
    {
        try {
            if (!empty($paramList)) {
                $paramList['deleteFlag'] = 0;
                $returnObject = $this->getEntityManager()
                    ->getRepository('Application\Entity\User')
                    ->findOneBy($paramList);
                return $returnObject;
            }
        } catch (\Exception $exc) {
           //@TODO: Need log this error
            throw new \Exception($exc);
        }
    }
    
}
