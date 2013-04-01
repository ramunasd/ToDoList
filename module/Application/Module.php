<?php

namespace Application;

use Zend\Mvc\ModuleRouteListener,
    Zend\Mvc\MvcEvent,
    Zend\Db\ResultSet\ResultSet,
    Zend\Db\TableGateway\TableGateway;

class Module
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
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Application\Authentication\Storage\Session' => function($sm){
                    return new Authentication\Storage\Session('auth');  
                },
                'AuthService' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $authService = new \Zend\Authentication\AuthenticationService();
                    $authService->setAdapter(new Authentication\Adapter\Table($dbAdapter));
                    $authService->setStorage($sm->get('Application\Authentication\Storage\Session'));
                    return $authService;
                },
                'UsersTableGateway' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\User());
                    return new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
                },
                'AclService' => function($sm){
                    $storage = $sm->get('Application\Authentication\Storage\Session');
                    return new Service\Acl(new Acl\Acl, $storage->read());
                },
            ),
        );
    }
    
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                // This will overwrite the native navigation helper
                'navigation' => function(\Zend\View\HelperPluginManager $pm) {
                    // Setup ACL:
                    $acl = new Acl\Acl();
                    // Get an instance of the proxy helper
                    $navigation = $pm->get('Zend\View\Helper\Navigation');
                    // Store ACL and role in the proxy helper:
                    $navigation->setAcl($acl);
                    // Get session user
                    $storage = $pm->getServiceLocator()->get('Application\Authentication\Storage\Session');
                    $user = $storage->read();
                    $navigation->setRole($user->role);
                    // Return the new navigation helper instance
                    return $navigation;
                },
                'acl' => function(\Zend\View\HelperPluginManager $pm){
                    $storage = $pm->getServiceLocator()->get('Application\Authentication\Storage\Session');
                    return new View\Helper\Acl(new Acl\Acl, $storage->read());
                }
            )
        );
    }
}
