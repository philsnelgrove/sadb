<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Admin for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Controller;

use Application\Controller\BaseController;
use Application\Entity\SocialMediaGateway;
use Zend\Form\Element;
use Zend\Form\Form;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\View\Model\ViewModel;

class SocialMediaGatewayController extends BaseController
{
    public function indexAction()
    {
        $gateways = $this->getObjectManager()->getRepository('\Application\Entity\SocialMediaGateway')->findAll();
        return new ViewModel(array('gateways' => $gateways));
    }

    public function addAction()
    {
        if ($this->request->isPost()) {
            $gateway = new SocialMediaGateway();
            
            $gateway->setName($this->getRequest()->getPost('name'));
            $gateway->setAppId($this->getRequest()->getPost('appid'));
            $gateway->setAppSecret($this->getRequest()->getPost('appsecret'));

            $this->getObjectManager()->persist($gateway);
            $this->getObjectManager()->flush();
            $newId = $gateway->getId();

            return $this->redirect()->toRoute('gatewaylist');
        }
        // return new ViewModel();
        $em = $this->getEntityManager();
        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('socialMediaGatewayAddForm');
        
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
        $gateway = $this->getObjectManager()->find('\Application\Entity\SocialMediaGateway', $id);
        
        if ($this->request->isPost()) {
            if($this->getRequest()->getPost('name'))
                $gateway->setName($this->getRequest()->getPost('name'));
            if($this->getRequest()->getPost('presence'))
            {
                $presence = $this->getObjectManager()->getRepository('\Application\Entity\SocialMediaPresence')->findOneBy(array('id'=>$this->getRequest()->getPost('presence')));
                $gateway->setSocialMediaPresence($presence);
                $presence->setSocialMediaGateway($gateway);
            }

            $this->getObjectManager()->persist($gateway);
            $this->getObjectManager()->persist($presence);
            $this->getObjectManager()->flush();

            return $this->redirect()->toRoute('gatewaylist');
        }
        
        $em = $this->getEntityManager();
        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('socialMediaGatewayEditForm');
        
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
        $form->setHydrator(new DoctrineObject($this->getEntityManager(),'Application\Entity\SocialMediaGateway'));
        $form->bind($gateway);
        
        return array('form' => $form);

        // return new ViewModel(array('gateway' => $gateway));
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $gateway = $this->getObjectManager()->find('\Application\Entity\SocialMediaGateway', $id);
        
        if ($this->request->isPost()) {
            $this->getObjectManager()->remove($gateway);
            $this->getObjectManager()->flush();

            return $this->redirect()->toRoute('gatewaylist');
        }

        return new ViewModel(array('gateway' => $gateway));
    }
}
