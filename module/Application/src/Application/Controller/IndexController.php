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
        $view = new ViewModel();
        
        return $view;
    }
}