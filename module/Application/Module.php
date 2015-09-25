<?php

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

 use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
 use Zend\ModuleManager\Feature\ConfigProviderInterface;

 class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{    

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


    public function onBootstrap($e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
    }


    public function getServiceConfig() {
        return array(
            'factories' => array(
                'PUserTable' => function ($sm) {                        
                    $tableGateway = $sm->get('PUserTableGateway');
                    $table = new Model\PUserTable($tableGateway);
                    return $table;
                },
                'PUserTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\PUser());
                    return new \Zend\Db\TableGateway\TableGateway(Model\PUserTable::TABLE_NAME, $dbAdapter, null, $resultSetPrototype);
                },
                'ProjectTable' => function ($sm) {                        
                    $tableGateway = $sm->get('ProjectTableGateway');
                    $table = new Model\ProjectTable($tableGateway);
                    return $table;
                },
                'ProjectTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Project());
                    return new \Zend\Db\TableGateway\TableGateway(Model\ProjectTable::TABLE_NAME, $dbAdapter, null, $resultSetPrototype);
                },
                'IssueTable' => function ($sm) {                        
                    $tableGateway = $sm->get('IssueTableGateway');
                    $table = new Model\IssueTable($tableGateway);
                    return $table;
                },
                'IssueTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Issue());
                    return new \Zend\Db\TableGateway\TableGateway(Model\IssueTable::TABLE_NAME, $dbAdapter, null, $resultSetPrototype);
                },
                'TimeEntryTable' => function ($sm) {                        
                    $tableGateway = $sm->get('TimeEntryTableGateway');
                    $table = new Model\TimeEntryTable($tableGateway);
                    return $table;
                },
                'TimeEntryTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\TimeEntry());
                    return new \Zend\Db\TableGateway\TableGateway(Model\TimeEntryTable::TABLE_NAME, $dbAdapter, null, $resultSetPrototype);
                },
                'UserTable' => function ($sm) {                        
                    $tableGateway = $sm->get('UserTableGateway');
                    $table = new Model\UserTable($tableGateway);
                    return $table;
                },
                'UserTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\User());
                    return new \Zend\Db\TableGateway\TableGateway(Model\UserTable::TABLE_NAME, $dbAdapter, null, $resultSetPrototype);
                },
                
            )
        );
    }
}
