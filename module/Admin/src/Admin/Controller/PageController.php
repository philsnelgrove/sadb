<?php

namespace Admin\Controller;

use Zend\View\Model\ViewModel;
use Zend\Form\Element;
use Zend\Form\Form;
use Application\Controller\BaseController;
use Application\Entity\SocialMediaPresence;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class PageController extends BaseController
{
    public function indexAction()
    {
        $pages = $this->getObjectManager()->getRepository('\Application\Entity\Page')->findAll();
        return new ViewModel(array('pages' => $pages));
    }

    public function addAction()
    {
        if ($this->request->isPost()) {
            $page = new Page();
            $page->setTitle($this->getRequest()->getPost('title'));
            $page->setSocialMediaServiceId($this->getRequest()->getPost('serviceid'));
            $presence = $this->getObjectManager()->getRepository('\Application\Entity\SocialMediaPresence')->findOneBy(array('id'=>$this->getRequest()->getPost('presence')));
            $page->setSocialMediaPresence($presence);
            $presence->addPage($page);
            
            $this->getObjectManager()->persist($page);
            $this->getObjectManager()->persist($presence);
            $this->getObjectManager()->flush();
            $newId = $page->getId();

            return $this->redirect()->toRoute('pagelist');
        }
        // return new ViewModel();
        $em = $this->getEntityManager();
        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('PageAddForm');
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
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
        
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $page = $this->getObjectManager()->find('\Application\Entity\Page', $id);
        if ($this->request->isPost()) {
            if($this->getRequest()->getPost('title'))
                $page->setTitle($this->getRequest()->getPost('title'));
            if($this->getRequest()->getPost('serviceid'))
                $page->setSocialMediaServiceId($this->getRequest()->getPost('serviceid'));
            
            if($this->getRequest()->getPost('presence'))
            {
                $presence = $this->getObjectManager()->getRepository('\Application\Entity\SocialMediaPresence')->findOneBy(array('id'=>$this->getRequest()->getPost('presence')));
                $page->setSocialMediaPresence($presence);
                $presence->addPage($page);
            }

            $this->getObjectManager()->persist($page);
            $this->getObjectManager()->persist($presence);
            $this->getObjectManager()->flush();

            return $this->redirect()->toRoute('pagelist');
        }
        
        $em = $this->getEntityManager();
        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('PageEditForm');
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
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
        $form->setHydrator(new DoctrineObject($this->getEntityManager(),'Application\Entity\Page'));
        $form->bind($page);
        
        return array('form' => $form);

        // return new ViewModel(array('user' => $user));
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $page = $this->getObjectManager()->find('\Application\Entity\Page', $id);

        if ($this->request->isPost()) {
            $presence = $page->getSocialMediaPresence();
            $presence->removePage($page);
            $this->getObjectManager()->remove($page);
            $this->getObjectManager()->persist($presence);
            $this->getObjectManager()->flush();

            return $this->redirect()->toRoute('pagelist');
        }

        return new ViewModel(array('page' => $page));
    }
}