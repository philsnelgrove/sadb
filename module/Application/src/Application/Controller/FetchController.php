<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DoctrineModule\Form\Element\ObjectMultiCheckbox;
use Zend\Form\Element;
use Zend\Form\Form;

/**
 * FetchController
 *
 * @author
 *
 * @version
 *
 */
class FetchController extends BaseController
{

    /**
     * The default action - show the home page
     */
    public function indexAction()
    {
        $em = $this->getEntityManager();
        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('fetchForm');
        
        $form->add(array(
            'name' => 'enterprise',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Enterprise',
                'object_manager' => $em,
                'target_class'   => 'Application\Entity\Enterprise',
                'property'       => 'name',
                'is_method'      => true,
                'find_method'    => array(
                    'name'   => 'findBy',
                    'params' => array(
                        'criteria' => array(),
                        'orderBy'  => array('name' => 'ASC'),
                    ),
                ),
            ),
        ));
        
        $form->add(array(
            'name' => 'presence',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Social Media Presence',
                'object_manager' => $em,
                'target_class'   => 'Application\Entity\SocialMediaPresence',
                'property'       => 'name',
                'is_method'      => true,
                'find_method'    => array(
                    'name'   => 'findBy',
                    'params' => array(
                        'criteria' => array(), // <-- will be "enterprises this user is auth'd for"
                        'orderBy'  => array('name' => 'ASC'),
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
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
        
        // $form->setAttribute('action', $this->url('fetch', array('action' => 'post')))->prepare();
        
        return array('form' => $form);
    }
    
    public function postAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                echo($comment);
            }
        };
    }
}