<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'user' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/user',
                    'defaults' => array(
                        '__NAMESPACE__' => 'ZfcUser\Controller',
                        'controller' => 'User',
                        'action'     => 'index',
                    ),
                ),
            ),
            'csv' => array(
                'type' => 'Segment',
                'options' => array(
                'route'    => '/report/csv[/:parameter]',
                    //'route'    => '/[:controller[/:action[/:parameter]]]',
                'defaults' => array(
                        'controller' => 'Application\Controller\Report',
                        'action'     => 'csv',
                    ),
                ),
            ),
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '[/:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Fetch' => 'Application\Controller\FetchController',
            'Application\Controller\Setup' => 'Application\Controller\SetupController',
            'Application\Controller\Report' => 'Application\Controller\ReportController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
            'download/download-csv'   => __DIR__ . '/../view/application/download/download-csv.phtml',
            'download/download-json'   => __DIR__ . '/../view/application/download/download-json.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
            'zfc-user' => __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'application_entities' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Application/Entity')
            ),
    
            'orm_default' => array(
                'drivers' => array(
                    'Application\Entity' => 'application_entities'
                )
            )
        ),
        'migrations_configuration' => array(
            'orm_default' => array(
                'directory' => 'data/DoctrineORMModule/Migrations',
                'namespace' => 'Application\Migrations',
                'table' => 'migrations',
            ),
        ),
    ),
    'navigation' => array(
        'default' => array(
            array(
                'label' => 'Administration',
                'route' => 'admin/default',
                'pages' => array(
                    array(
                        'label' => 'Index',
                        'route' => 'admin/default',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                    array(
                        'label' => 'Edit Models',
                        'route' => 'admin/default',
                        'controller' => 'EditModel',
                        'action' => 'index'
                    ),
                    array(
                        'label' => 'Setup SADB',
                        'route' => 'application/default',
                        'controller' => 'Setup',
                        'action' => 'index'
                    ),
                ),
            ),
            array(
                'label' => 'SADB',
                'route' => 'application/default',
                'pages' => array(
                    array(
                        'label' => 'Data Fetch',
                        'route' => 'application/default',
                        'controller' => 'Fetch',
                        'action' => 'index',
                    ),
                    array(
                        'label' => 'Report',
                        'route' => 'application/default',
                        'controller' => 'Report',
                        'action' => 'index'
                    ),
                ),
            ),
            array(
                'label' => 'User',
                'route' => 'user',
                'pages' => array(
                    array(
                        'label' => 'Profile',
                        'route' => 'user',
                    ),
                    array(
                        'label' => 'Sign Out',
                        'route' => 'zfcuser/logout'
                    ),
                ),
            ),
        ),
    ),
);
