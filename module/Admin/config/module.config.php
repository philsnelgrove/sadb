<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\AccessToken' => 'Admin\Controller\AccessTokenController',
            'Admin\Controller\Dimension' => 'Admin\Controller\DimensionController',
            'Admin\Controller\Enterprise' => 'Admin\Controller\EnterpriseController',
            'Admin\Controller\GuestAccessToken' => 'Admin\Controller\GuestAccessTokenController',
            'Admin\Controller\Index' => 'Admin\Controller\IndexController',
            'Admin\Controller\Page' => 'Admin\Controller\PageController',
            'Admin\Controller\PageDimension' => 'Admin\Controller\PageDimensionController',
            'Admin\Controller\Post' => 'Admin\Controller\PostController',
            'Admin\Controller\PostDimension' => 'Admin\Controller\PostDimensionController',
            'Admin\Controller\PostType' => 'Admin\Controller\PostTypeController',
            'Admin\Controller\SocialMediaGateway' => 'Admin\Controller\SocialMediaGatewayController',
            'Admin\Controller\SocialMediaPresence' => 'Admin\Controller\SocialMediaPresenceController',
            'Admin\Controller\User' => 'Admin\Controller\UserController',
            'Admin\Controller\EditModel' => 'Admin\Controller\EditModelController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'userlist' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/admin/user/index',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\User',
                        'action'     => 'index',
                    ),
                ),
            ),
            'enterpriselist' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/admin/enterprise/index',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Enterprise',
                        'action'     => 'index',
                    ),
                ),
            ),
            'presencelist' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/admin/socialmediapresence/index',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\SocialMediaPresence',
                        'action'     => 'index',
                    ),
                ),
            ),
            'gatewaylist' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/admin/socialmediagateway/index',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\SocialMediaGateway',
                        'action'     => 'index',
                    ),
                ),
            ),
            'pagelist' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/admin/page/index',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Page',
                        'action'     => 'index',
                    ),
                ),
            ),
            'postlist' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/admin/post/index',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Post',
                        'action'     => 'index',
                    ),
                ),
            ),
            'postdimensionlist' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/admin/postdimension/index',
                    'defaults' => array(
                        'controller' => 'Admin\Controller\PostDimension',
                        'action'     => 'index',
                    ),
                ),
            ),
            'admin' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/admin',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '[/:controller[/:action[/:id]]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Admin' => __DIR__ . '/../view',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
        ),
     ),
);
