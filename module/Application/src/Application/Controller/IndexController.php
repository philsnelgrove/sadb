<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Enterprise;
use Application\Entity\SocialMediaPresence;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        // return new ViewModel();
        $objectManager = $this
        ->getServiceLocator()
        ->get('Doctrine\ORM\EntityManager');
        
        $user = new \Application\Entity\User();
        $user->setDisplayName('Master of the Universe');
        $user->setUsername("joan");
        $user->setEmail("joan@yahoo.com");
        $user->setPassword("password");
        $user->setState(1);
        $mySocialMediaPresence = new \Application\Entity\SocialMediaPresence();
        $mySocialMediaPresence->setName("Test Page Account");
        $objectManager->persist($mySocialMediaPresence);
        $myEnterprise = new \Application\Entity\Enterprise();
        $user->setEnterprise($myEnterprise);
        $objectManager->persist($user);
        $myEnterprise->addUser($user);
        $myEnterprise->setName("Dragon's Hangout");
        $mySocialMediaPresence->setParentEnterprise($myEnterprise);
        $myEnterprise->addSocialMediaPresence($mySocialMediaPresence);
        $objectManager->persist($myEnterprise);
        $objectManager->flush();
        
//         $mySocialMediaPresence->setParentEnterprise($myEnterprise);
//         $myEnterprise->addSocialMediaPresence($mySocialMediaPresence);
        $objectManager->persist($mySocialMediaPresence);
        
        $objectManager->persist($myEnterprise);
        
        $objectManager->flush();
        
        $user->setEnterprise($myEnterprise);    
        $objectManager->persist($user);
        $objectManager->flush();
        
        echo(var_dump($user));
    }
}
