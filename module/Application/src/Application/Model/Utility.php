<?php

/**
 * Contains Commonly/reusable functions
 * 
 * 
 * PHP version 5
 * 
 * @category   Module
 * @package    Application
 * @subpackage Model
 * @author     Costrategix Team <team@costrategix.com>
 * @copyright  2015 CoS
 * @license    http://www.costrategix.com Proprietary 
 * @version    GIT: 1.7
 * @link       http://www.costrategix.com 
 * 
 */

namespace Application\Model;

/**
 * Contains Commonly/reusable functions
 * 
 * 
 * PHP version 5
 * 
 * @category   Module
 * @package    Application
 * @subpackage Model
 * @author     Costrategix Team <team@costrategix.com>
 * @copyright  2015 CoS
 * @license    http://www.costrategix.com Proprietary 
 * @version    GIT: 1.7
 * @link       http://www.costrategix.com 
 * 
 */

class Utility 
{
    
    /**
     * Set data time fields for updation
     * 
     * @param Doctrine\ORM\Entity
     *
     * @return Doctrine\ORM\Entity
     */
    public function setDateTimeForUpdation($object)
    {
        $date = date_create(date('Y-m-d H:i:s'));
        $object->setUpdatedDtTm($date);
        return $object;
    }
    
    /**
     * Set default fields for creation
     * 
     * @param Doctrine\ORM\Entity
     *
     * @return Doctrine\ORM\Entity
     */
    public function setDateTimeForCreation($object)
    { 
        $date = date_create(date('Y-m-d H:i:s'));        
        $object->setUpdatedDtTm($date);
        $object->setCreatedDtTm($date);
        $object->setDeleteFlag(0);
        return $object;
    }
    
    /*
     *  return CurrentDateTime Object
     */
    public function getCurrentEpochTime() {
        $epochTime = time();   
        return $epochTime;
    }
}
