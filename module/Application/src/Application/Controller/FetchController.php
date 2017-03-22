<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
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
        //$view = new ViewModel();
        
        $em = $this->getEntityManager();
        $dimension_array = $em->getRepository('Application\Entity\Dimension')->findAll();
        
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
                        'criteria' => array(),
                        'orderBy'  => array('name' => 'ASC'),
                    ),
                ),
            ),
        ));
        
        foreach($dimension_array as $dimension)
        {
            $form->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => $dimension->getName(),
                'options' => array(
                    'label' => $dimension->getName(),
                    'use_hidden_element' => true,
                    'checked_value' => $dimension->getName(),
                    'unchecked_value' => ''
                )
            ));
        }
        
        $form->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                echo($comment);
            }
        }
        
        return array('form' => $form);
    }
}