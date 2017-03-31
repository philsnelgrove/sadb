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
use Zend\Http\Request as HttpRequest;
use Zend\Http\Headers;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ModelInterface;

class Module
{

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        // attach the JSON view strategy
        $app      = $e->getTarget();
        $locator  = $app->getServiceManager();
        $view     = $locator->get('ZendViewView');
        $strategy = $locator->get('ViewJsonStrategy');
        $view->getEventManager()->attach($strategy, 100);
        
        // attach a listener to check for errors
        $events = $e->getTarget()->getEventManager();
        $events->attach(MvcEvent::EVENT_RENDER, array($this, 'onRenderError'));      
        $events->attach(MvcEvent::EVENT_FINISH, array($this, 'onFinish'));
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
                'FetchPostsByPageForm' => function($sm) {
                    $form = new Form\FetchPostsByPageForm();
                    // $form->setInputFilter(new \Application\Form\FetchFilter);
                    $form->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty());
                    return $form;
                },
                'PostReportForm' => function($sm) {
                    $form = new Form\PostReportForm();
                    // $form->setInputFilter(new \Application\Form\FetchFilter);
                    $form->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty());
                    return $form;
                },
                'PageReportForm' => function($sm) {
                    $form = new Form\PageReportForm();
                    // $form->setInputFilter(new \Application\Form\FetchFilter);
                    $form->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty());
                    return $form;
                },
                'PresenceAddForm' => function($sm) {
                    $form = new Form\PresenceAddForm();
                    // $form->setInputFilter(new \Application\Form\FetchFilter);
                    $form->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty());
                return $form;
                },
            ),
        );
    }
    public function onRenderError($e)
    {
        // must be an error
        if (!$e->isError()) {
            return;
        }
        
        // Check the accept headers for application/json
        $request = $e->getRequest();
        if (!$request instanceof HttpRequest) {
            return;
        }
        
        $headers = $request->getHeaders();
        if (!$headers->has('Accept')) {
            return;
        }
        
        $accept = $headers->get('Accept');
        $match  = $accept->match('application/json');
        if (!$match || $match->getTypeString() == '*/*') {
            // not application/json
            return;
        }
        
        // make debugging easier if we're using xdebug!
        ini_set('html_errors', 0);
        
        // if we have a JsonModel in the result, then do nothing
        $currentModel = $e->getResult();
        if ($currentModel instanceof JsonModel) {
            return;
        }
        
        // create a new JsonModel - use application/api-problem+json fields.
        $response = $e->getResponse();
        $model = new JsonModel(array(
            "httpStatus" => $response->getStatusCode(),
            "title" => $response->getReasonPhrase(),
        ));
        
        // Find out what the error is
        $exception  = $currentModel->getVariable('exception');
        
        if ($currentModel instanceof ModelInterface && $currentModel->reason) {
            switch ($currentModel->reason) {
                case 'error-controller-cannot-dispatch':
                    $model->detail = 'The requested controller was unable to dispatch the request.';
                    break;
                case 'error-controller-not-found':
                    $model->detail = 'The requested controller could not be mapped to an existing controller class.';
                    break;
                case 'error-controller-invalid':
                    $model->detail = 'The requested controller was not dispatchable.';
                    break;
                case 'error-router-no-match':
                    $model->detail = 'The requested URL could not be matched by routing.';
                    break;
                default:
                    $model->detail = $currentModel->message;
                    break;
            }
        }
        
        if ($exception) {
            if ($exception->getCode()) {
                $e->getResponse()->setStatusCode($exception->getCode());
            }
            $model->detail = $exception->getMessage();
            
            // find the previous exceptions
            $messages = array();
            while ($exception = $exception->getPrevious()) {
                $messages[] = "* " . $exception->getMessage();
            };
            if (count($messages)) {
                $exceptionString = implode("n", $messages);
                $model->messages = $exceptionString;
            }
        }
        
        // set our new view model
        $model->setTerminal(true);
        $e->setResult($model);
        $e->setViewModel($model);
    }
    public function onFinish($e)
    {
        $response = $e->getResponse();
        if(method_exists($response, 'getHeaders'))
        {
            $headers = $response->getHeaders();
            $contentType = $headers->get('Content-Type');
            if($contentType)
            {
                if (strpos($contentType->getFieldValue(), 'application/json') !== false
                    && strpos($response->getContent(), 'httpStatus')) {
                    // This is (almost certainly!) an api-problem
                    $headers->addHeaderLine('Content-Type', 'application/api-problem+json');
                }
            }
        }
    }
}
