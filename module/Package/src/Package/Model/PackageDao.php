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

namespace Package\Model;
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

class PackageDao extends \Application\Model\AbstractDao
{
                     
    /*
     * get default package precing
     * @return $defaultPackagePriceArray
     */
    public function getDefaultPackagePriceList() {
        
         $query = $this->getEntityManager()->createQuery(
            "SELECT phc FROM Application\Entity\Package p
             INNER JOIN Application\Entity\PackageHasCredits phc WITH phc.packageId = p.id                  
            WHERE p.deleteFlag = '0' and  phc.deleteFlag = '0' and p.type = 'default'
            GROUP BY phc.packageDuration, phc.packageAmount
            ORDER BY phc.id ASC"
        );     
        
        $result = $query->getResult();
        return $result;
        
    }
    
    public function read($id) {
        $queryParamArray['id'] = $id;
        $entity = Constant::ENTITY_PACKAGE;
        $packageObject = $this->getEntityByParameterList($queryParamArray, $entity);    
        return $packageObject;
    }
    
}
