<?php

namespace Admin\Controller;

use Zend\View\Model\ViewModel;
use Zend\Form\Element;
use Zend\Form\Form;
use Application\Controller\BaseController;
use Application\Entity\Dimension;

class DimensionController extends BaseController
{
    public function indexAction()
    {
        $dimensions = $this->getObjectManager()->getRepository('\Application\Entity\Dimension')->findAll();
        return new ViewModel(array('dimensions' => $dimensions));
    }

    public function addAction()
    {
        if ($this->request->isPost()) {
            $dimension = new Dimension();
            $dimension->setDisplayName($this->getRequest()->getPost('displayname'));
            $dimension->setEmail($this->getRequest()->getPost('email'));
            $dimension->setPassword($bcrypt->create($this->getRequest()->getPost('password')));
            $dimension->setState($this->getRequest()->getPost('state'));
            $dimension->setUsername($this->getRequest()->getPost('username'));
            
            $this->getObjectManager()->persist($dimension);
            $this->getObjectManager()->flush();
            $newId = $dimension->getId();

            return $this->redirect()->toRoute('dimensionlist');
        }
        // return new ViewModel();
        $em = $this->getEntityManager();
        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('DimensionAddForm');        
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
        $user = $this->getObjectManager()->find('\Application\Entity\User', $id);
        $bcrypt = new Bcrypt;
        $bcrypt->setCost(14);

        if ($this->request->isPost()) {
            if($this->getRequest()->getPost('displayName'))
                $user->setDisplayName($this->getRequest()->getPost('displayName'));
            if($this->getRequest()->getPost('email'))
                $user->setEmail($this->getRequest()->getPost('email'));
            if($this->getRequest()->getPost('password'))
                $user->setPassword($bcrypt->create($this->getRequest()->getPost('password')));
            if($this->getRequest()->getPost('state'))
                $user->setState($this->getRequest()->getPost('state'));
            if($this->getRequest()->getPost('username'))
                $user->setUsername($this->getRequest()->getPost('username'));
            if($this->getRequest()->getPost('enterprise'))
            {
                $enterprise = $this->getObjectManager()->getRepository('\Application\Entity\Enterprise')->findOneBy(array('id'=>$this->getRequest()->getPost('enterprise')));
                $user->setEnterprise($enterprise);
                $enterprise->addUser($user);
            }

            $this->getObjectManager()->persist($user);
            $this->getObjectManager()->persist($enterprise);
            $this->getObjectManager()->flush();

            return $this->redirect()->toRoute('userlist');
        }
        
        $em = $this->getEntityManager();
        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('UserEditForm');
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
        $form->setHydrator(new DoctrineObject($this->getEntityManager(),'Application\Entity\User'));
        $form->bind($user);
        
        return array('form' => $form);

        // return new ViewModel(array('user' => $user));
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $user = $this->getObjectManager()->find('\Application\Entity\User', $id);
        $enterpriseId = $user->getEnterprise()->getId();
        $enterprise = $this->_objectManager->find('\Application\Entity\Enterprise', $enterpriseId);
        $enterprise->removeUser($user);

        if ($this->request->isPost()) {
            $this->getObjectManager()->remove($user);
            $this->getObjectManager()->flush();

            return $this->redirect()->toRoute('userlist');
        }

        return new ViewModel(array('user' => $user));
    }
}