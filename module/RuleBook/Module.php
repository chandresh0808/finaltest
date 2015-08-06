<?php

/**
 * Module configurations
 * 
 * PHP version 5
 * 
 * @category   Module
 * @package    Api
 * @subpackage Congif
 * @author     Costrategix Team <team@costrategix.com>
 * @copyright  2015 CoS
 * @license    http://www.costrategix.com Proprietary 
 * @version    GIT: 1.7
 * @link       http://www.costrategix.com 
 * 
 */

namespace RuleBook;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    
    public function getServiceConfig()
    {
        return array(            
            'invokables' => array(                                       
            ),
           'factories' => array(
                'rule_book_manager_service' => function($sm) {
                    $ruleBookManager = new Model\RuleBookManager();
                    $serviceList = array('entity_manager', 'rule_book_dao_service', 'rulebook_has_risk_dao_service', 'excel_service', 
                        'risk_has_job_function_dao_service','risk_dao_service','rulebook_has_risk_dao_service','transactions_dao_service',
                        'job_function_has_transactions_dao_service','job_function_dao_service','log_service');                    
                    $ruleBookManager->setServiceList($serviceList, $sm);
                    return $ruleBookManager;
                },
                'rule_book_dao_service' => function($sm) {
                    $ruleBookDao = new Model\RuleBookDao();
                    $serviceList = array('entity_manager');
                    $ruleBookDao->setServiceList($serviceList, $sm);
                    return $ruleBookDao;
                },
                'rulebook_has_risk_dao_service' => function($sm) {
                    $rulebookHasRiskDao = new Model\RulebookHasRiskDao();
                    $serviceList = array('entity_manager');
                    $rulebookHasRiskDao->setServiceList($serviceList, $sm);
                    return $rulebookHasRiskDao;
                },
                'excel_service' => function ($sm) {
                    $excelService = new Model\ExcelService();
                    $serviceList = array('entity_manager');
                    $excelService->setServiceList($serviceList, $sm);
                    return $excelService;
                },
                'risk_has_job_function_dao_service' => function ($sm) {
                    $riskHasJobFunctionDao = new Model\RiskHasJobFunctionDao();
                    $serviceList = array('entity_manager');
                    $riskHasJobFunctionDao->setServiceList($serviceList, $sm);
                    return $riskHasJobFunctionDao;
                },
                'risk_dao_service' => function ($sm) {
                    $riskDaoService = new Model\RiskDao();
                    $serviceList = array('entity_manager');
                    $riskDaoService->setServiceList($serviceList, $sm);
                    return $riskDaoService;
                },
                'rulebook_has_risk_dao_service' => function ($sm) {
                    $rulebookHasRiskDaoService = new Model\RulebookHasRiskDao();
                    $serviceList = array('entity_manager');
                    $rulebookHasRiskDaoService->setServiceList($serviceList, $sm);
                    return $rulebookHasRiskDaoService;
                },
                'job_function_dao_service' => function ($sm) {
                    $jobFunctionDaoService = new Model\JobFunctionDao();
                    $serviceList = array('entity_manager');
                    $jobFunctionDaoService->setServiceList($serviceList, $sm);
                    return $jobFunctionDaoService;
                },
                'transactions_dao_service' => function ($sm) {
                    $transactionsDaoService = new Model\TransactionsDao();
                    $serviceList = array('entity_manager');
                    $transactionsDaoService->setServiceList($serviceList, $sm);
                    return $transactionsDaoService;
                },
                'job_function_has_transactions_dao_service' => function ($sm) {
                    $jobFunctionHasTransactionDaoService = new Model\JobFunctionHasTransactionDao();
                    $serviceList = array('entity_manager');
                    $jobFunctionHasTransactionDaoService->setServiceList($serviceList, $sm);
                    return $jobFunctionHasTransactionDaoService;
                },
            ),   
        );
    }

    
}


