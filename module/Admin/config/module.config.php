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
            'admin' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/admin',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action[/:id]]]',
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
);
