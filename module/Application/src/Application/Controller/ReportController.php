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
        if ($this->request->isPost()) {
            $filename = '';
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
            array_push($resultset, array(
                $csvHeader,
                'dimension',
                'value',
                'date collected'
            ));
            foreach ($result as $key => $postDimension) {
                if ($postDimension->getLastUpdated() >= $startDate && $postDimension->getLastUpdated() <= $endDate) {
                    array_push($resultset, array(
                        $postDimension->getPost()->getSocialMediaServiceId(),
                        $postDimension->getDimension(),
                        $postDimension->getValue(),
                        $postDimension->getLastUpdated()->format('Y-m-d')
                    ));
                    $filenameadder = $postDimension->getPost()->getSocialMediaServiceId() . '_';
                }
            }
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
        
        // $sourceArray = array(array("item 1"=>"value 1", "item 2"=>"value 2"), array("item 3"=>"value 3", "item 4"=>"value 4"));
        // $testerz = new JsonModel($sourceArray);
        
        // return($testerz);
        
        // ***************************
        $em = $this->getEntityManager();
//         if ($this->request->isPost()) 
//         {
            $filename = 'default_report.csv';
            $postId = $this->getRequest()->getPost('post');
            $startDate = new \DateTime($this->getRequest()->getPost('startdate'));
            $endDate = new \DateTime($this->getRequest()->getPost('enddate'));
            $resultset = [];
            $post = $em->getRepository('Application\Entity\Post')->findOneBy(array(
                'id' => $postId
            ));
            
            $result = $em->getRepository('Application\Entity\PostDimension')->findBy(array(
                'post' => $post
            ), array(
                'last_updated' => 'ASC'
            ));
            array_push($resultset, array(
                'post id',
                'dimension',
                'value',
                'date collected'
            ));
            foreach ($result as $key => $postDimension) 
            {
                if ($postDimension->getLastUpdated() >= $startDate && $postDimension->getLastUpdated() <= $endDate) 
                {
                    array_push($resultset, array(
                        $postDimension->getPost()->getSocialMediaServiceId(),
                        $postDimension->getDimension(),
                        $postDimension->getValue(),
                        $postDimension->getLastUpdated()->format('Y-m-d')
                    ));
                }
            }
            
//             $testerz = new JsonModel($resultset);
//             return($testerz);

            // $contacts = array( 'foo' => 'bar' );
//             $response = $this->getResponse();
//             $response->getHeaders()->addHeaderLine( 'Content-Type', 'application/json' );
//             $response->setContent(json_encode($resultset));
//             return $response;
                       
            $filename = 'test_report.json';
            $view = new JsonModel();
            $view->setTemplate('download/download-json')
                ->setVariable('results', $resultset)
                ->setTerminal(true);
            $output = $this->getServiceLocator()
                ->get('viewrenderer')
                ->render($view);
            
            $response = $this->getResponse();
            $headers = $response->getHeaders();
            $headers->addHeaderLine('Content-Type', 'application/json')
                ->addHeaderLine('Content-Disposition', sprintf("attachment; filename=\"%s\"", $filename))
                ->addHeaderLine('Accept-Ranges', 'bytes')
                ->addHeaderLine('Content-Length', strlen($output));
            $response->setContent($output);
            return $response;
//         }
//         // $resultset = array(array("item 1"=>"value 1", "item 2"=>"value 2"), array("item 3"=>"value 3", "item 4"=>"value 4"));
//         $testerz = new JsonModel($resultset);        
//         return($testerz);
    }
}