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

class AccessTokenController extends BaseController
{
    public function indexAction()
    {
        $tokens = $this->getObjectManager()->getRepository('\Application\Entity\AccessToken')->findAll();
        return new ViewModel(array('tokens' => $tokens));
    }
}
