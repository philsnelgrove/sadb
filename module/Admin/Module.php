<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Admin for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Admin;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface
{

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php'
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/', __NAMESPACE__)
                )
            )
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getFormElementConfig()
    {
        return array(
//             'invokables' => array(
//                 'UserAddForm' => 'Admin\Form\UserAddForm',
//                 'UserAddForm' => 'Admin\Form\UserAddForm',
//             ),
            'initializers' => array(
                'ObjectManagerInitializer' => function ($element, $formElements) {
                    // look if the form implements the ObjectManagerAwareInterface
                    if ($element instanceof ObjectManagerAwareInterface) {
                        // locate the EntityManager using the serviceLocator
                        $services = $formElements->getServiceLocator();
                        $entityManager = $services->get('Doctrine\ORM\EntityManager');
                        // set the forms EntityManager or Objectmanager, 2 names for the same thing
                        $element->setObjectManager($entityManager);
                    }
                }
            ),
            'factories' => array(
                'userAddForm' => function ($sm) {
                    $form = new Form\UserAddForm();
                    // $form->setInputFilter(new \Admin\Form\FetchFilter);
                    $form->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty());
                    return $form;
                },
                'userEditForm' => function ($sm) {
                    $form = new Form\UserEditForm();
                    // $form->setInputFilter(new \Admin\Form\FetchFilter);
                    $form->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty());
                    return $form;
                },
                'enterpriseAddForm' => function ($sm) {
                    $form = new Form\EnterpriseAddForm();
                    // $form->setInputFilter(new \Admin\Form\FetchFilter);
                    $form->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty());
                    return $form;
                },
                'enterpriseEditForm' => function ($sm) {
                    $form = new Form\EnterpriseEditForm();
                    // $form->setInputFilter(new \Admin\Form\FetchFilter);
                    $form->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty());
                    return $form;
                },
                'socialMediaPresenceAddForm' => function ($sm) {
                    $form = new Form\SocialMediaPresenceAddForm();
                    // $form->setInputFilter(new \Admin\Form\FetchFilter);
                    $form->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty());
                    return $form;
                },
                'socialMediaPresenceEditForm' => function ($sm) {
                    $form = new Form\SocialMediaPresenceEditForm();
                    // $form->setInputFilter(new \Admin\Form\FetchFilter);
                    $form->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty());
                    return $form;
                },
                'socialMediaGatewayAddForm' => function ($sm) {
                    $form = new Form\SocialMediaGatewayAddForm();
                    // $form->setInputFilter(new \Admin\Form\FetchFilter);
                    $form->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty());
                    return $form;
                },
                'socialMediaGatewayEditForm' => function ($sm) {
                    $form = new Form\SocialMediaGatewayEditForm();
                    // $form->setInputFilter(new \Admin\Form\FetchFilter);
                    $form->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty());
                    return $form;
                },
                'pageAddForm' => function ($sm) {
                    $form = new Form\PageAddForm();
                    // $form->setInputFilter(new \Admin\Form\FetchFilter);
                    $form->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty());
                    return $form;
                },
                'pageEditForm' => function ($sm) {
                    $form = new Form\PageEditForm();
                    // $form->setInputFilter(new \Admin\Form\FetchFilter);
                    $form->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty());
                    return $form;
                },
                'postDimensionAddForm' => function ($sm) {
                    $form = new Form\PostDimensionEditForm();
                    // $form->setInputFilter(new \Admin\Form\FetchFilter);
                    $form->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty());
                    return $form;
                },
                'postDimensionEditForm' => function ($sm) {
                    $form = new Form\PostDimensionEditForm();
                    // $form->setInputFilter(new \Admin\Form\FetchFilter);
                    $form->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty());
                    return $form;
                },
            ),
        );
    }
}
