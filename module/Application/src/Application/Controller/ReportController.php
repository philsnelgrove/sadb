<?php
namespace Application\Controller;

use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

/**
 * ReportController
 *
 * @author
 *
 * @version
 *
 */
class ReportController extends BaseController
{

    /**
     * The default action - show the home page
     */
    public function indexAction()
    {
        // TODO Auto-generated FetchController::indexAction() default action
        return new ViewModel();
    }

    public function postAction()
    {
        if (! session_id()) {
            session_start();
        }
        $em = $this->getEntityManager();
        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('PostReportForm');
        $form->add(array(
            'name' => 'post',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Page',
                'object_manager' => $em,
                'target_class' => 'Application\Entity\Post',
                'property' => 'title',
                'is_method' => true,
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array(), // <-- will be "enterprises this user is auth'd for"
                        'orderBy' => array(
                            'title' => 'ASC'
                        )
                    )
                )
            )
        ));
        
        $form->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectMultiCheckbox',
            'name' => 'dimensionTable',
            'options' => array(
                'label' => 'Select dimensions to query',
                'object_manager' => $em,
                'target_class' => 'Application\Entity\Dimension',
                'property' => 'name'
            )
        ));
        
        $form->add(array(
            'type' => 'Zend\Form\Element\Date',
            'name' => 'startdate',
            'options' => array(
                'label' => 'Start Date'
            ),
            'attributes' => array(
                'min' => '2012-01-01',
                'max' => '2020-01-01',
                'step' => '1'
            ) // days; default step interval is 1 day

        ));
        $form->add(array(
            'type' => 'Zend\Form\Element\Date',
            'name' => 'enddate',
            'options' => array(
                'label' => 'End Date'
            ),
            'attributes' => array(
                'min' => '2012-01-01',
                'max' => '2020-01-01',
                'step' => '1'
            ) // days; default step interval is 1 day

        ));
        
        $form->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton'
            )
        ));
        return array(
            'form' => $form
        );
    }

    public function pageAction()
    {
        if (! session_id()) {
            session_start();
        }
        $em = $this->getEntityManager();
        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('PageReportForm');
        $form->add(array(
            'name' => 'page',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Page',
                'object_manager' => $em,
                'target_class' => 'Application\Entity\Page',
                'property' => 'title',
                'is_method' => true,
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array(), // <-- will be "enterprises this user is auth'd for"
                        'orderBy' => array(
                            'title' => 'ASC'
                        )
                    )
                )
            )
        ));
        
        $form->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectMultiCheckbox',
            'name' => 'dimensionTable',
            'options' => array(
                'label' => 'Select dimensions to query',
                'object_manager' => $em,
                'target_class' => 'Application\Entity\Dimension',
                'property' => 'name'
            )
        ));
        
        $form->add(array(
            'type' => 'Zend\Form\Element\Date',
            'name' => 'startdate',
            'options' => array(
                'label' => 'Start Date'
            ),
            'attributes' => array(
                'min' => '2012-01-01',
                'max' => '2020-01-01',
                'step' => '1'
            ) // days; default step interval is 1 day

        ));
        $form->add(array(
            'type' => 'Zend\Form\Element\Date',
            'name' => 'enddate',
            'options' => array(
                'label' => 'End Date'
            ),
            'attributes' => array(
                'min' => '2012-01-01',
                'max' => '2020-01-01',
                'step' => '1'
            ) // days; default step interval is 1 day

        ));
        
        $form->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton'
            )
        ));
        return array(
            'form' => $form
        );
    }

    protected function csvAction()
    {
        if (! session_id()) {
            session_start();
        }
        $em = $this->getEntityManager();
        $filenameadder = '';
        $filename = '';
        if ($this->request->isPost()) {
//            die(var_dump($this->getRequest()->getPost()));
            $postId = $this->getRequest()->getPost('post');
            if (! $postId) {
                $pageId = $this->getRequest()->getPost('page');
                $filename .= 'page_';
                $isPage = true;
            } else {
                $filename .= 'post_';
                $isPage = false;
            }
            $startDate = new \DateTime($this->getRequest()->getPost('startdate'));
            $endDate = new \DateTime($this->getRequest()->getPost('enddate'));
            $resultset = [];
            if ($isPage) {
                $csvHeader = 'page id';
                $page = $em->getRepository('Application\Entity\Page')->findOneBy(array(
                    'id' => $pageId
                ));
                $result = $em->getRepository('Application\Entity\PageDimension')->findBy(array(
                    'page' => $page
                ), array(
                    'last_updated' => 'ASC'
                ));
            } else {
                $csvHeader = 'post id';
                $post = $em->getRepository('Application\Entity\Post')->findOneBy(array(
                    'id' => $postId
                ));
                $result = $em->getRepository('Application\Entity\PostDimension')->findBy(array(
                    'post' => $post
                ), array(
                    'last_updated' => 'ASC'
                ));
            }
//            die(var_dump($result));
//            die(var_dump($this->getRequest()->getPost('dimensionTable')));
            array_push($resultset, array(
                $csvHeader,
                'dimension',
                'value',
                'date collected'
            ));
            $selectedDimensionIds = $this->getRequest()->getPost('dimensionTable');
            $selectedDimensions = [];
            foreach ($selectedDimensionIds as $id)
            {
                $selectedDimensions[] = $em->getRepository('Application\Entity\Dimension')->find($id)->getName();
            }
//            die(var_dump($selectedDimensions));
            foreach ($result as $key => $postDimension) {
                $dimensionName = $postDimension->getDimension();
                $workingValues = $postDimension->getValues();
//                die(print_r($workingValues,true).'</br>');
//                echo(var_dump($postDimension).print_r($selectedDimensions, true));
                if ($postDimension->getLastUpdated() >= $startDate && $postDimension->getLastUpdated() <= $endDate && in_array($dimensionName, $selectedDimensions)) 
                {
                    if(array_key_exists('default', $workingValues[0]))
                    {
//                         echo($dimensionName . ':' . print_r($workingValues[0]['default'], true) . '</br>');
                        // if the first key is "default" then just add the value
                        array_push($resultset, array(
                            $postDimension->getPost()->getSocialMediaServiceId(),
                            $dimensionName,
                            $workingValues[0]['default'],
                            $postDimension->getLastUpdated()->format('Y-m-d')
                        ));
                    }
                    else 
                    {
                        foreach ($workingValues as $k => $v)
                        {
//                             echo($dimensionName . '_' . key($v) . ':' . current($v) . '</br>');
                            array_push($resultset, array(
                                $postDimension->getPost()->getSocialMediaServiceId(),
                                $dimensionName . '_' . key($v),
                                current($v),
                                $postDimension->getLastUpdated()->format('Y-m-d')
                            ));
                        }
                    }
                    $filenameadder = $postDimension->getPost()->getSocialMediaServiceId() . '_';
                }
                else 
                {
                    //echo "no results";
                    //return;
                }
            }
            // test return, REMOVE OR COMMENT
            // return;
        }
        $filename .= $filenameadder;
        $filename .= $endDate->format('Y-m-d');
        $filename .= '.csv';
        $view = new ViewModel();
        $view->setTemplate('download/download-csv')
            ->setVariable('results', $resultset)
            ->setTerminal(true);
        $output = $this->getServiceLocator()
            ->get('viewrenderer')
            ->render($view);
        
        $response = $this->getResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine('Content-Type', 'text/csv')
            ->addHeaderLine('Content-Disposition', sprintf("attachment; filename=\"%s\"", $filename))
            ->addHeaderLine('Accept-Ranges', 'bytes')
            ->addHeaderLine('Content-Length', strlen($output));
        $response->setContent($output);
        return $response;
    }

    protected function jsonAction()
    {
        if (! session_id()) {
            session_start();
        }
        
        $em = $this->getEntityManager();
        if ($this->request->isPost()) 
        {
            $postId = $this->params()->fromPost('post');
            $startDate = new \DateTime($this->params()->fromPost('startdate'));
            $endDate = new \DateTime($this->params()->fromPost('enddate'));
            
            // the code below is for testing; when uncommented, 
            // it allows the endpoint to respond to a "get" like...
            // /application/report/json?post[]=1012034048927553_1012041805593444&post[]=1012034048927553_1015785951885696&startdate=2016-1-1&enddate=2017-12-30
            // ... for example; handy for testing in a browser
            
//         }
//         else 
//         {
//             $ array_push($resultset, array(
//                             $postDimension->getPost()->getSocialMediaServiceId(),
//                             $postDimension->getDimension(),
//                             $postDimension->getValues(),
//                             $postId = $this->params()->fromQuery('post');
//             $startDate = new \DateTime($this->params()->fromQuery('startdate'));
//             $endDate = new \DateTime($this->params()->fromQuery('enddate'));
//         }
            $responseset = [];
            foreach($postId as $post_id)
            {
                $resultset = [];
                $post = $em->getRepository('Application\Entity\Post')->findOneBy(array(
                    'social_media_service_id' => $post_id
                ));
                $result = $em->getRepository('Application\Entity\PostDimension')->findBy(
                    array(
                        'post' => $post,
                    ),
                    array(
                        'last_updated' => 'ASC',
                    )
                );
                foreach ($result as $key => $postDimension) 
                {
                    if ($postDimension->getLastUpdated() >= $startDate && $postDimension->getLastUpdated() <= $endDate) 
                    {
                        array_push($resultset, array($post_id=>array(
                                $postDimension->getDimension(),
                                $postDimension->getValue(),
                                $postDimension->getLastUpdated()->format('Y-m-d')
                            )
                        ));
                    }
                    
                }
                array_push($responseset, $resultset);
            }
            return new JsonModel($responseset);           
        }
        return new JsonModel(array('error'=>'This endpoint responds to POST requests only'));
    }
}