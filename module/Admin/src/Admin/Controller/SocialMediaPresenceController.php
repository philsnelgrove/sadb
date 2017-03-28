<?php

namespace Admin\Controller;

use Zend\View\Model\ViewModel;
use Application\Entity\SocialMediaPresence;
use Zend\Form\Element;
use Zend\Form\Form;
use Application\Controller\BaseController;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class SocialMediaPresenceController extends BaseController
{
    public function indexAction()
    {
        $presences = $this->getObjectManager()->getRepository('\Application\Entity\SocialMediaPresence')->findAll();
        return new ViewModel(array('presences' => $presences));
    }

    public function addAction()
    {
        if ($this->request->isPost()) {
            $presence = new SocialMediaPresence();
            
            $enterprise = $this->getObjectManager()->getRepository('\Application\Entity\Enterprise')->findOneBy(array('id'=>$this->getRequest()->getPost('enterprise')));
            $gateway = $this->getObjectManager()->getRepository('\Application\Entity\SocialMediaGateway')->findOneBy(array('id'=>$this->getRequest()->getPost('gateway')));
            $presence->setParentEnterprise($enterprise);
            $presence->setSocialMediaGateway($gateway);
            $enterprise->addSocialMediaPresence($presence);
            $gateway->setSocialMediaPresence($presence);
            
            $presence->setName($this->getRequest()->getPost('name'));
            $presence->setSocialMediaGateway($gateway);

            $this->getObjectManager()->persist($presence);
            $this->getObjectManager()->persist($enterprise);
            $this->getObjectManager()->persist($gateway);
            $this->getObjectManager()->flush();
            $newId = $presence->getId();

            return $this->redirect()->toRoute('presencelist');
        }
        // return new ViewModel();
        $em = $this->getEntityManager();
        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('socialMediaPresenceAddForm');
        $form->add(array(
            'name' => 'enterprise',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Parent Enterprise',
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
            'name' => 'gateway',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Social Media Gateway',
                'object_manager' => $em,
                'target_class'   => 'Application\Entity\SocialMediaGateway',
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
        $presence = $this->getObjectManager()->find('\Application\Entity\SocialMediaPresence', $id);
        
        if ($this->request->isPost()) {
            if($this->getRequest()->getPost('name'))
                $presence->setName($this->getRequest()->getPost('name'));
            if($this->getRequest()->getPost('enterprise'))
            {
                $enterprise = $this->getObjectManager()->getRepository('\Application\Entity\Enterprise')->findOneBy(array('id'=>$this->getRequest()->getPost('enterprise')));
                $presence->setParentEnterprise($enterprise);
                $enterprise->addSocialMediaPresence($presence);
            }
            if($this->getRequest()->getPost('gateway'))
            {
                $gateway = $this->getObjectManager()->getRepository('\Application\Entity\SocialMediaGateway')->findOneBy(array('id'=>$this->getRequest()->getPost('gateway')));
                $presence->setSocialMediaGateway($gateway);
                $gateway->setSocialMediaPresence($presence);
            }

            $this->getObjectManager()->persist($presence);
            $this->getObjectManager()->persist($gateway);
            $this->getObjectManager()->persist($enterprise);
            $this->getObjectManager()->flush();

            return $this->redirect()->toRoute('presencelist');
        }
        
        $em = $this->getEntityManager();
        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('SocialMediaPresenceEditForm');
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
            'name' => 'gateway',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Social Media Gateway',
                'object_manager' => $em,
                'target_class'   => 'Application\Entity\SocialMediaGateway',
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
        $form->setHydrator(new DoctrineObject($this->getEntityManager(),'Application\Entity\SocialMediaPresence'));
        $form->bind($presence);
        
        return array('form' => $form);

        // return new ViewModel(array('presence' => $presence));
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $presence = $this->getObjectManager()->find('\Application\Entity\SocialMediaPresence', $id);
        $enterpriseId = $presence->getEnterprise()->getId();
        $enterprise = $this->_objectManager->find('\Application\Entity\Enterprise', $enterpriseId);
        $enterprise->removeSocialMediaPresence($presence);

        if ($this->request->isPost()) {
            $this->getObjectManager()->remove($presence);
            $this->getObjectManager()->flush();

            return $this->redirect()->toRoute('presencelist');
        }

        return new ViewModel(array('presence' => $presence));
    }
}