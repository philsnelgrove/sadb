<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

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
        $em = $this->getEntityManager();
        if ($this->request->isPost()) {
            $route='home';
            // $current_identity = $this->zfcUserAuthentication()->getIdentity();
            
            $requestPost = $this->getRequest()->getPost();
            $postId = $this->getRequest()->getPost('post');
            $dimensionIdArray = $this->getRequest()->getPost('dimensionTable');
            $dimensionArray = [];
            $startDate = $this->getRequest()->getPost('startdate');
            $endDate = $this->getRequest()->getPost('enddate');
            
            $post = $em->getRepository('Application\Entity\Post')->findOneBy(array('id'=>$postId));
            
            // echo("Post ID:" . $postId . "</br>");
            echo("Post:" . $post->getTitle() . "</br>");
            foreach($dimensionIdArray as $key => $dimension)
            {
                $myDimension = $em->getRepository('Application\Entity\Dimension')->findOneBy(array('id'=>$dimension));
                array_push($dimensionArray, $myDimension);
                echo("Dimension ID:" . $myDimension->getName() . "</br>");
            }
            echo("Start Date:" . $startDate . "</br>");
            echo("End Date:" . $endDate . "</br>");
            
            // replace this with a query builder assembly that considers all criteria for the result set
            $resultArray = $em->getRepository('Application\Entity\PostDimension')->findBy(array('post'=>$post));
            
            return $this->redirect()->toRoute($route);
        }
        // $em = $this->getEntityManager();
        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('PostReportForm');
        $form->add(array(
            'name' => 'post',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Page',
                'object_manager' => $em,
                'target_class'   => 'Application\Entity\Post',
                'property'       => 'title',
                'is_method'      => true,
                'find_method'    => array(
                    'name'   => 'findBy',
                    'params' => array(
                        'criteria' => array(), // <-- will be "enterprises this user is auth'd for"
                        'orderBy'  => array('title' => 'ASC'),
                    ),
                ),
            ),
        ));
    
        $form->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectMultiCheckbox',
            'name' => 'dimensionTable',
            'options' => array(
                'label' => 'Select dimensions to query',
                'object_manager' => $em,
                'target_class'   => 'Application\Entity\Dimension',
                'property'       => 'name',
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
                'step' => '1', // days; default step interval is 1 day
            )
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
                'step' => '1', // days; default step interval is 1 day
            )
        ));
    
        $form->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
        return array('form' => $form);
    }
    
    public function pageAction()
    {
        // TODO Auto-generated FetchController::indexAction() default action
        return new ViewModel();
    }
    
    protected function csvAction($filename, $resultset)
    {
        $view = new ViewModel();
        $view->setTemplate('download/download-csv')
        ->setVariable('results', $resultset)
        ->setTerminal(true);
    
        if (!empty($columnHeaders)) {
            $view->setVariable(
    
                'columnHeaders', $columnHeaders
                );
        }
    
        $output = $this->getServiceLocator()
        ->get('viewrenderer')
        ->render($view);
    
        $response = $this->getResponse();
    
        $headers = $response->getHeaders();
        $headers->addHeaderLine('Content-Type', 'text/csv')
        ->addHeaderLine(
    
            'Content-Disposition',
            sprintf("attachment; filename=\"%s\"", $filename)
            )
            ->addHeaderLine('Accept-Ranges', 'bytes')
            ->addHeaderLine('Content-Length', strlen($output));
    
            $response->setContent($output);
    
            return $response;
    }
}