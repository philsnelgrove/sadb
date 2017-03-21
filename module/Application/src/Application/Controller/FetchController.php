<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

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
        // TODO Auto-generated FetchController::indexAction() default action
        return new ViewModel();
    }
}