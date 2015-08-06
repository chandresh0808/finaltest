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
class ApplicationManager extends \Application\Model\AbstractCommonServiceMutator
{

    /*
     * Data for analysis report
     */

    public function dataForSystemActivity($paramArray)
    {

        $systemActivityDaoService = $this->getSystemActivityDaoService();

        //Sorting column mapping
        $sortColumnNameMap = array(0 => "a.type", 1 => "u.firstName", 2 => "sa.createdDtTm");

        $inputParamArray['sort_column'] = $sortColumnNameMap[$paramArray['iSortCol_0']];
        $inputParamArray['sort_order'] = $paramArray['sSortDir_0'];
        $inputParamArray['limit'] = $paramArray['iDisplayLength'];
        $inputParamArray['offset'] = $paramArray['iDisplayStart'];
        $inputParamArray['search_query'] = $paramArray['sSearch'];
                
        $systemActivityResponse = $systemActivityDaoService->searchSystemActivity($inputParamArray);

        $systemActivityObjectList = $systemActivityResponse['system_activity_object_list'];
        $count = $systemActivityResponse['total_count'];

        $inputArrayForDataTables = \Application\Model\Utility::customizeResultForDataTable($systemActivityObjectList, $count);
        return $inputArrayForDataTables;
    }
    
}
