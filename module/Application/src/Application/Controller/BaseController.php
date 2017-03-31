<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Controller\AbstractRestfulController;

// class BaseController extends AbstractActionController
class BaseController extends AbstractRestfulController
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;
    protected $_objectManager;

    /**
     * for managing entities via Doctrine
     * @return Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->em;
    }
    

    protected function getObjectManager()
    {
        if (!$this->_objectManager) {
            $this->_objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
    
        return $this->_objectManager;
    }
}