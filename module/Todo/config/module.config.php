<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Todo\Controller\Index' => 'Todo\Controller\IndexController',
			'Todo\Controller\Task' => 'Todo\Controller\TaskController',
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
			'AclAccess' => 'Todo\Controller\Plugin\AclAccess',
         ),
     ),
    'router' => array(
        'routes' => array(
            'todo' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/todo[/][:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Todo\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'task' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/todo/task/[:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9a-zA-Z]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Todo\Controller\Task',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'todo' => __DIR__ . '/../view',
        ),
        'strategies' => array(
           'ViewJsonStrategy',
        ),
    ),
    'service_manager' => array(
         'factories' => array(
             'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
         ),
     ),
     'navigation' => array(
        'default' => array(
            array(
                'label' => 'Tasks',
                'route' => 'todo',
                'action' => 'index',
            	'pages' => array(
                    array(
                        'label' => 'Incomplete',
                        'route' => 'todo',
                        'action' => 'index',
                    ),
                    array(
                        'label' => 'Today tasks',
                        'route' => 'todo',
                        'action' => 'today',
                    ),
                    array(
                        'label' => 'Overdue',
                        'route' => 'todo',
                        'action' => 'overdue',
                    ),
                    array(
                        'label' => 'Important',
                        'route' => 'todo',
                        'action' => 'important',
                    ),
                ),
            ),
            array(
                'label' => 'Logout',
                'route' => 'logout',
            ),
        ),
    ),
);
