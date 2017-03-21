<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;
use Application\Form\DimensionForm;

use Application\Entity\Dimension;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Doctrine\ORM;

class IndexController extends BaseController
{

    public function indexAction()
    {
         $form = new DimensionForm();
         $form->get('submit')->setValue('Add');

         $request = $this->getRequest();
         if ($request->isPost()) {
             $dimension = new Dimension();
             $form->setInputFilter($dimension->getInputFilter());
             $form->setData($request->getPost());

             if ($form->isValid()) {
                 $dimension->exchangeArray($form->getData());
                 $this->getDimensionTable()->saveDimension($dimension);

                 // Redirect to list of dimensions
                 return $this->redirect()->toRoute('dimension');
             }
         }
         return array('form' => $form);
    }

    // action for both - edit and add
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $mode = "edit";

        $dimension = $this->getEntityManager()->find('Application\Entity\Dimension', $id);
        if(!$dimension instanceof Dimension) {
            $dimension = new Dimension();
            $mode = "add";
        }

        $form = new DimensionForm($this->getEntityManager());
        // assign hydrator
        $hydrator = new DoctrineHydrator($this->getEntityManager(), get_class($dimension));
        $form->setHydrator($hydrator);

        // bind object to form
        $form->bind($dimension);
        if($mode == "add") {
            $form->get('submit')->setValue('Add');    
        } else {
            $form->get('submit')->setAttribute('value', 'Save');   
        } 

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {

                if($mode == "add") {
                    $this->getEntityManager()->persist($dimension);
                    $msg = 'Add Dimension.';
                } else {
                    $msg = 'Dimension ___';
                }
                $this->getEntityManager()->flush();

                $this->FlashMessenger()->setNamespace(\Zend\Mvc\Controller\Plugin\FlashMessenger::NAMESPACE_INFO)
                        ->addMessage($msg);
                return $this->redirect()->toRoute('dimension');
            }
        }
        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        // here be code for delete action
    }
}