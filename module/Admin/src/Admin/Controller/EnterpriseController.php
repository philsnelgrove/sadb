<?php

namespace Admin\Controller;

use Zend\View\Model\ViewModel;
use Application\Entity\User;
use Zend\Form\Element;
use Zend\Form\Form;
use Application\Entity\Enterprise;
use Application\Controller\BaseController;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class EnterpriseController extends BaseController
{
    public function indexAction()
    {
        $enterprises = $this->getObjectManager()->getRepository('\Application\Entity\Enterprise')->findAll();
        return new ViewModel(array('enterprises' => $enterprises));
    }

    public function addAction()
    {
        if ($this->request->isPost()) {
            $enterprise = new Enterprise();
            $enterprise->setName($this->getRequest()->getPost('name'));
            $this->getObjectManager()->persist($enterprise);
            $this->getObjectManager()->flush();
            $newId = $enterprise->getId();

            return $this->redirect()->toRoute('enterpriselist');
        }
        // return new ViewModel();
        $em = $this->getEntityManager();
        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('EnterpriseAddForm');
        
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
        $enterprise = $this->getObjectManager()->find('\Application\Entity\Enterprise', $id);

        if ($this->request->isPost()) {
            if($this->getRequest()->getPost('name'))
                $enterprise->setName($this->getRequest()->getPost('name'));
            $this->getObjectManager()->persist($enterprise);
            $this->getObjectManager()->flush();

            return $this->redirect()->toRoute('enterpriselist');
        }
        
        $em = $this->getEntityManager();
        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('EnterpriseEditForm');
        $form->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
        $form->setHydrator(new DoctrineObject($this->getEntityManager(),'Application\Entity\Enterprise'));
        $form->bind($enterprise);
        
        return array('form' => $form);

        // return new ViewModel(array('enterprise' => $enterprise));
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $enterprise = $this->getObjectManager()->find('\Application\Entity\Enterprise', $id);
        $enterpriseId = $enterprise->getEnterprise()->getId();
        $enterprise = $this->_objectManager->find('\Application\Entity\Enterprise', $enterpriseId);
        $enterprise->removeUser($enterprise);

        if ($this->request->isPost()) {
            $this->getObjectManager()->remove($enterprise);
            $this->getObjectManager()->flush();

            return $this->redirect()->toRoute('enterpriselist');
        }

        return new ViewModel(array('enterprise' => $enterprise));
    }
}