<?php
namespace Todo;

use Zend\Mvc\MvcEvent,
    Zend\ModuleManager\Feature\AutoloaderProviderInterface,
    Zend\ModuleManager\Feature\ConfigProviderInterface,
    Zend\Db\ResultSet\ResultSet,
    Zend\Db\TableGateway\TableGateway;

class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'onDispatch'));
    }
    
    public function onDispatch(MvcEvent $e)
    {
        $locator = $e->getApplication()->getServiceManager();
        $authService = $locator->get('AuthService');
        $controller = $e->getTarget();
        if(!$authService->hasIdentity() && !$controller instanceof \Application\Controller\UserController){
            $controller->plugin('redirect')->toRoute('login');
            $e->stopPropagation();
            $e->setError('identity_not_found');
            return false;
        }
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

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Todo\Model\TasksTable' => function($sm) {
                    $tableGateway = $sm->get('TasksTableGateway');
                    $table = new Model\TasksTable($tableGateway);
                    return $table;
                },
                'TasksTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Task());
                    return new TableGateway('tasks', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}
