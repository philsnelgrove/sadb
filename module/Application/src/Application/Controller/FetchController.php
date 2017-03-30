<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DoctrineModule\Form\Element\ObjectMultiCheckbox;
use Zend\Form\Element;
use Zend\Form\Form;
use Facebook\Facebook;
use Zend\View\View;
use Application\Entity\AccessToken;
use Application\Entity\Page;
use Application\Entity\Post;
use Application\Entity\PostDimension;
use Doctrine\Common\Collections\Criteria;

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
    public function indexAction()
    {
        return;
    }
    
    public function userAction()
    {
        $em = $this->getEntityManager();

        $fb_query = '';
        $app_id = '';
        $app_secret = '';
        $current_identity = $this->zfcUserAuthentication()->getIdentity();
        $access_token = $current_identity->getAccessToken()->getToken();
    
        $myPresence = $em->getRepository('Application\Entity\SocialMediaPresence')->findOneBy(array('id'=>'1'));
        $app_id = $myPresence->getSocialMediaGateway()->getAppId();
        $app_secret = $myPresence->getSocialMediaGateway()->getAppSecret();
        
        $fb = new Facebook([
            'app_id' => $app_id,
            'app_secret' => $app_secret,
            'default_graph_version' => 'v2.8',
            'default_access_token' => $access_token,
        ]);
        
//         echo("Facebook query parameters are " . $fb_query . "</br>");
//         echo("Facebook app_id is " . $app_id . "</br>");
//         echo("Facebook app_secret is " . $app_secret . "</br>");
//         echo("Facebook access_token is " . $access_token . "</br>");
        
        try {
            $response = $fb->get('/me/accounts');
        } catch(\Facebook\Exceptions\FacebookResponseException $e) 
        {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(\Facebook\Exceptions\FacebookSDKException $e)
        {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        
        $pages = $response->getDecodedBody();
        foreach($pages['data'] as $page)
        {
            $myPage = new Page();
            $myPage->setTitle($page['name']);
            $myPage->setSocialMediaServiceId($page['id']);
            $myPage->setSocialMediaPresence($myPresence);
            $em->persist($myPage);
        }
        $em->flush();
        return;
    }
    
    public function pageAction()
    {
        $em = $this->getEntityManager();
        if ($this->request->isPost()) {
            // die(var_dump($this->getRequest()->getPost()));
            $route='home';
            $fb_query = '';
            $app_id = '';
            $app_secret = '';
            $current_identity = $this->zfcUserAuthentication()->getIdentity();
            $access_token = $current_identity->getAccessToken()->getToken();
            
            $page_id = $this->getRequest()->getPost('page');
            $parameters = $this->getRequest()->getPost('dimensionTable');
            // die(var_dump($parameters));
            $fb_query = '['; 
            // implode("', '", array_values($parameters))
            foreach ($parameters as $key => $value)
            {
                $myDimension = $em->getRepository('Application\Entity\Dimension')->findOneBy(array('id'=>$value));
                $dimensionName = $myDimension->getName();
                $fb_query .= '\'' . $dimensionName . '\',';
            }
            $fb_query = substr($fb_query, 0, -1);
            $fb_query .= ']';
            // die($fb_query);
        
            $page = $em->getRepository('Application\Entity\Page')->findOneBy(array('id'=>$page_id));
            $service_id = $page->getSocialMediaServiceId();
            
            $myPresence = $em->getRepository('Application\Entity\SocialMediaPresence')->findOneBy(array('id'=>$page->getSocialMediaPresence()));
            $app_id = $myPresence->getSocialMediaGateway()->getAppId();
            $app_secret = $myPresence->getSocialMediaGateway()->getAppSecret();
            
            $fb = new Facebook([
                'app_id' => $app_id,
                'app_secret' => $app_secret,
                'default_graph_version' => 'v2.8',
                'default_access_token' => $access_token,
            ]);
        
            echo("Facebook query parameters are " . $fb_query . "</br>");
            echo("Facebook Page ID is " . $service_id . "</br>");
            echo("Facebook app_id is " . $app_id . "</br>");
            echo("Facebook app_secret is " . $app_secret . "</br>");
            echo("Facebook access_token is " . $access_token . "</br>");
            
            try {
                $response = $fb->get($service_id . '/insights/' . $fb_query);
            } catch(\Facebook\Exceptions\FacebookResponseException $e)
            {
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch(\Facebook\Exceptions\FacebookSDKException $e)
            {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
        
            $dimensions = $response->getDecodedBody();
            foreach($dimensions['data'] as $dimension)
            {
                // die(var_dump($post));
                if( isset($dimension['name']))
                {
                    $checkForExistingPageDimension = $em->getRepository('Application\Entity\PageDimension')->findOneBy(array('post'=>$post, 'dimension' => $dimension['name']));
                    $today = new \DateTime('today');
                    $record = $checkForExistingPageDimension[0]->getLastUpdated();
                    $date1 = new \DateTime(date('Y-m-d', strtotime($today->format('Y-m-d'))));
                    $date2 = new \DateTime(date('Y-m-d', strtotime($record->format('Y-m-d'))));
                    $diff = $date1->diff($date2)->days;
                    if($diff > 0)
                    {
                        $myPageDimension = new PageDimension();
                        $myPageDimension->setPage($page);
                        $myPageDimension->setDimension($dimension['name']);
                        $myPageDimension->setValue($dimension['values'][0]['value']);
                        $em->persist($myPostDimension);
                    }
                    else
                    {
                        $checkForExistingPageDimension->setValue($dimension['values'][0]['value']);
                        $em->persist($checkForExistingPageDimension);
                    }
                 }
            }
            $em->flush();
            return $this->redirect()->toRoute($route);
        }
        
        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('PageFetchForm');
        $form->add(array(
            'name' => 'page',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Page',
                'object_manager' => $em,
                'target_class'   => 'Application\Entity\Page',
                'property'       => 'title',
                'is_method'      => true,
                'find_method'    => array(
                    'name'   => 'findBy',
                    'params' => array(
                        'criteria' => array(), // <-- will be "enterprises this user is auth'd for"
                        'orderBy'  => array('title' => 'ASC'),
                    ),
                ),
            ),
        ));
    
        $form->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectMultiCheckbox',
            'name' => 'dimensionTable',
            'options' => array(
                'label' => 'Select dimensions to query',
                'object_manager' => $em,
                'target_class'   => 'Application\Entity\Dimension',
                'property'       => 'name',
            )
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
    
    public function postAction()
    {
        $em = $this->getEntityManager();
        if ($this->request->isPost()) {
            $route='home';
            $fb_query = '';
            $app_id = '';
            $app_secret = '';
            $current_identity = $this->zfcUserAuthentication()->getIdentity();
            $access_token = $current_identity->getAccessToken()->getToken();
            
            $post_id = $this->getRequest()->getPost('post');
            $parameters = $this->getRequest()->getPost('dimensionTable');
            // die(var_dump($parameters));
            $fb_query = '['; 
            // implode("', '", array_values($parameters))
            foreach ($parameters as $key => $value)
            {
                $myDimension = $em->getRepository('Application\Entity\Dimension')->findOneBy(array('id'=>$value));
                $dimensionName = $myDimension->getName();
                $fb_query .= '\'' . $dimensionName . '\',';
            }
            $fb_query = substr($fb_query, 0, -1);
            $fb_query .= ']';
            // die($fb_query);
        
            $post = $em->getRepository('Application\Entity\Post')->findOneBy(array('id'=>$post_id));
            $service_id = $post->getSocialMediaServiceId();
            
            $myPresence = $em->getRepository('Application\Entity\SocialMediaPresence')->findOneBy(array('id'=>$post->getPage()->getSocialMediaPresence()));
            $app_id = $myPresence->getSocialMediaGateway()->getAppId();
            $app_secret = $myPresence->getSocialMediaGateway()->getAppSecret();
            
            $fb = new Facebook([
                'app_id' => $app_id,
                'app_secret' => $app_secret,
                'default_graph_version' => 'v2.8',
                'default_access_token' => $access_token,
            ]);
        
            echo("Facebook query parameters are " . $fb_query . "</br>");
            echo("Facebook Page ID is " . $service_id . "</br>");
            echo("Facebook app_id is " . $app_id . "</br>");
            echo("Facebook app_secret is " . $app_secret . "</br>");
            echo("Facebook access_token is " . $access_token . "</br>");
            
            try {
                $response = $fb->get($service_id . '/insights/' . $fb_query);
            } catch(\Facebook\Exceptions\FacebookResponseException $e)
            {
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch(\Facebook\Exceptions\FacebookSDKException $e)
            {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
        
            $dimensions = $response->getDecodedBody();
            foreach($dimensions['data'] as $dimension)
            {
                if( isset($dimension['name']))
                {
                    $checkForExistingPostDimension = $em->getRepository('Application\Entity\PostDimension')->findBy(array('post'=>$post, 'dimension' => $dimension['name']), array('last_updated' => 'DESC')); //, 'last_updated' => 'today'));
                    $today = new \DateTime('today');
                    $record = $checkForExistingPostDimension[0]->getLastUpdated();
                    $date1 = new \DateTime(date('Y-m-d', strtotime($today->format('Y-m-d'))));
                    $date2 = new \DateTime(date('Y-m-d', strtotime($record->format('Y-m-d'))));
                    $diff = $date1->diff($date2)->days;
                    if($diff > 0)
                    {
                        $myPostDimension = new PostDimension();
                        $myPostDimension->setPost($post);
                        $myPostDimension->setDimension($dimension['name']);
                        $myPostDimension->setValue($dimension['values'][0]['value']);
                        $em->persist($myPostDimension);
                    }
                    else
                    {
                        $checkForExistingPostDimension->setValue($dimension['values'][0]['value']);
                        $em->persist($checkForExistingPostDimension);
                    }
                 }
            }
            $em->flush();
            return $this->redirect()->toRoute($route);
        }
        $em = $this->getEntityManager();
        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('PostFetchForm');
        $form->add(array(
            'name' => 'post',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Page',
                'object_manager' => $em,
                'target_class'   => 'Application\Entity\Post',
                'property'       => 'title',
                'is_method'      => true,
                'find_method'    => array(
                    'name'   => 'findBy',
                    'params' => array(
                        'criteria' => array(), // <-- will be "enterprises this user is auth'd for"
                        'orderBy'  => array('title' => 'ASC'),
                    ),
                ),
            ),
        ));

        $form->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectMultiCheckbox',
            'name' => 'dimensionTable',
            'options' => array(
                'label' => 'Select dimensions to query',
                'object_manager' => $em,
                'target_class'   => 'Application\Entity\Dimension',
                'property'       => 'name',
            )
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
    
    public function queryAction()
    {
        if (!session_id()) {
            session_start();
        }
        $request = $this->getRequest();
        $fb_query = '';
        $app_id = '';
        $app_secret = '';
        $current_identity = $this->zfcUserAuthentication()->getIdentity();
        $access_token = $current_identity->getAccessToken()->getToken();
        $em = $this->getEntityManager();
        
        if ($request->isPost()) 
        {
            $content=$request->getPost();
            foreach($content as $key => $value)
            {
                if (gettype($value) == "array" || gettype($value) == "object")
                {
                    foreach($value as $k => $v)
                    {
                        $myDimension = $em->getRepository('Application\Entity\Dimension')->findOneBy(array('id'=>$v));
                        // echo("Inner Key:" . $k . " value:" . $v . "</br>");
                        if (!$fb_query)
                            $fb_query = $myDimension->getName();
                        else 
                            $fb_query .= ',' . $myDimension->getName();
                    }
                }
                else 
                {
                    // echo("Key:" . $key . " value:" . $value . "</br>");
                    if ($key=="presence")
                    {
                        $myPresence = $em->getRepository('Application\Entity\SocialMediaPresence')->findOneBy(array('id'=>$value));
                        $app_id = $myPresence->getSocialMediaGateway()->getAppId();
                        $app_secret = $myPresence->getSocialMediaGateway()->getAppSecret();
                    }
                }
            }
//             $fb = new Facebook([
//                 'app_id' => $app_id,
//                 'app_secret' => $app_secret,
//                 'default_graph_version' => 'v2.8',
//                 'default_access_token' => $access_token,
//             ]);
            
//             echo("Facebook query parameters are " . $fb_query . "</br>");
//             echo("Facebook app_id is " . $app_id . "</br>");
//             echo("Facebook app_secret is " . $app_secret . "</br>");
//             echo("Facebook access_token is " . $access_token . "</br>");
            
//             try {
//                 // Get the \Facebook\GraphNodes\GraphUser object for the current user.
//                 // If you provided a 'default_access_token', the '{access-token}' is optional.
//                 $response = $fb->get('/me');
//             } catch(\Facebook\Exceptions\FacebookResponseException $e) {
//                 // When Graph returns an error
//                 echo 'Graph returned an error: ' . $e->getMessage();
//                 exit;
//             } catch(\Facebook\Exceptions\FacebookSDKException $e) {
//                 // When validation fails or other local issues
//                 echo 'Facebook SDK returned an error: ' . $e->getMessage();
//                 exit;
//             }
            
//             $me = $response->getGraphUser();
//             echo 'Logged in as ' . $me->getName();
        };
    }
    
    public function receiveTokenCallbackAction()
    {
        if (!session_id()) {
            session_start();
        }
        $request = $this->getRequest();
        $em = $this->getEntityManager();
        // grab the facebooky goodness
        $myPresence = $em->getRepository('Application\Entity\SocialMediaPresence')->findOneBy(array('id'=>'1'));
        $app_id = $myPresence->getSocialMediaGateway()->getAppId();
        $app_secret = $myPresence->getSocialMediaGateway()->getAppSecret();
        $fb = new Facebook([
            'app_id' => $app_id,
            'app_secret' => $app_secret,
            'default_graph_version' => 'v2.8',
        ]);
        $helper = $fb->getRedirectLoginHelper();
        
        try {
            $accessToken = $helper->getAccessToken();
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        
        if (isset($accessToken)) 
        {
            // Logged in!
            $_SESSION['facebook_access_token'] = (string) $accessToken;
            $current_identity = $this->zfcUserAuthentication()->getIdentity();
            $user_email = $current_identity->getEmail();
            // echo("User email:" . $user_email);
            
            // insert the results into Db records
            $myUser = $em->getRepository('Application\Entity\User')->findOneBy(array('email'=>$user_email));
            $myToken = new AccessToken();
            $myToken->setToken($accessToken);
            $em->persist($myToken);
            $myUser->setAccessToken($myToken);
            $em->persist($myUser);
            $em->flush();
            return;
        }
        else 
        {
            echo("Failure!");
        }
        return array('token' => '1234');
    }
    
    public function receiveCallbackAction()
    {
        if (!session_id()) {
            session_start();
        }
        $request = $this->getRequest();
        $em = $this->getEntityManager();
        // grab the facebooky goodness
        $myPresence = $em->getRepository('Application\Entity\SocialMediaPresence')->findOneBy(array('id'=>'4'));
        $app_id = $myPresence->getSocialMediaGateway()->getAppId();
        $app_secret = $myPresence->getSocialMediaGateway()->getAppSecret();
        $fb = new Facebook([
            'app_id' => $app_id,
            'app_secret' => $app_secret,
            'default_graph_version' => 'v2.8',
        ]);
        $helper = $fb->getRedirectLoginHelper();
    
        try {
            $accessToken = $helper->getAccessToken();
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
    
        if (isset($accessToken))
        {
            // Logged in!
            $_SESSION['facebook_access_token'] = (string) $accessToken;
            return array('token' => $accessToken);
    
            // parse the crap out of it
    
            // insert the results into Db records
        }
        else
        {
            echo("Failure!");
        }
        return array('token' => '1234');
    }
    
    public function fetchPostsByPageAction()
    {
        $em = $this->getEntityManager();
        
        if ($this->request->isPost()) {
            $route='home';
            $fb_query = '';
            $app_id = '';
            $app_secret = '';
            $current_identity = $this->zfcUserAuthentication()->getIdentity();
            $access_token = $current_identity->getAccessToken()->getToken();
    
            $myPresence = $em->getRepository('Application\Entity\SocialMediaPresence')->findOneBy(array('id'=>'1'));
            $app_id = $myPresence->getSocialMediaGateway()->getAppId();
            $app_secret = $myPresence->getSocialMediaGateway()->getAppSecret();
            $page = $this->getObjectManager()->getRepository('\Application\Entity\Page')->findOneBy(array('id'=>$this->getRequest()->getPost('page')));
            $page_id = $page->getSocialMediaServiceId();
    
            $fb = new Facebook([
                'app_id' => $app_id,
                'app_secret' => $app_secret,
                'default_graph_version' => 'v2.8',
                'default_access_token' => $access_token,
            ]);
    
            echo("Facebook query parameters are " . $fb_query . "</br>");
            echo("Facebook Page ID is " . $page_id . "</br>");
            echo("Facebook app_id is " . $app_id . "</br>");
            echo("Facebook app_secret is " . $app_secret . "</br>");
            echo("Facebook access_token is " . $access_token . "</br>");
    
            try {
                $response = $fb->get($page_id . '/feed/');
            } catch(\Facebook\Exceptions\FacebookResponseException $e)
            {
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch(\Facebook\Exceptions\FacebookSDKException $e)
            {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
    
            $posts = $response->getDecodedBody();
            foreach($posts['data'] as $post)
            {
                // var_dump($post);
                if( isset($post['message']))
                {
                    $checkForExistingPost = $em->getRepository('Application\Entity\Post')->findOneBy(array('social_media_service_id'=>$post['id']));
                    if(!$checkForExistingPost)
                    {
                        $myPost = new Post();
                        $myPost->setTitle($post['message']);
                        $myPost->setSocialMediaServiceId($post['id']);
                        $myPost->setPage($page);
                        $em->persist($myPost);
                    }
                    else 
                    {
                        echo("Ignoring Repeat of post '" . $post['message'] . "'");
                    }
                }
            }
            $em->flush();   
            return $this->redirect()->toRoute($route);
        }
        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('FetchPostsByPageForm');
        $form->add(array(
            'name' => 'page',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Page',
                'object_manager' => $em,
                'target_class'   => 'Application\Entity\Page',
                'property'       => 'title',
                'is_method'      => true,
                'find_method'    => array(
                    'name'   => 'findBy',
                    'params' => array(
                        'criteria' => array(), // <-- will be "enterprises this user is auth'd for"
                        'orderBy'  => array('title' => 'ASC'),
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
}