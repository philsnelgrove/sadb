<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
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
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }

    public function getServiceConfiguration()
    {
        return array(
            'factories' => array(
                'db_adapter' => function ($sm) {
                    $config = $sm->get('Configuration');
                    $dbAdapter = new \Zend\Db\Adapter\Adapter($config['db']);
                    return $dbAdapter;
                }
            )
        );
    }

    public function getFormElementConfig()
    {
        return array(
            'invokables' => array(
                'DimensionForm' => 'Application\Form\DimensionForm',
            ),
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
                'PostFetchForm' => function($sm) {
                    $form = new Form\PostFetchForm();
                    // $form->setInputFilter(new \Application\Form\FetchFilter);
                    $form->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty());
                    return $form;
                },
                'PageFetchForm' => function($sm) {
                    $form = new Form\PageFetchForm();
                    // $form->setInputFilter(new \Application\Form\FetchFilter);
                    $form->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty());
                    return $form;
                },
            ),
        );
    }
}
