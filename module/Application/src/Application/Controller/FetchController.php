<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DoctrineModule\Form\Element\ObjectMultiCheckbox;
use Zend\Form\Element;
use Zend\Form\Form;
use Facebook\Facebook;
use Zend\View\View;

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
        $em = $this->getEntityManager();
        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('fetchForm');
        
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
                        'criteria' => array(),
                        'orderBy'  => array('name' => 'ASC'),
                    ),
                ),
            ),
        ));
        
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
        
        // $form->setAttribute('action', $this->url('fetch', array('action' => 'post')))->prepare();
        
        return array('form' => $form);
    }
    
    public function postAction()
    {
        $request = $this->getRequest();
        $fb_query = '';
        $app_id = '';
        $app_secret = '';
//        $access_token = '';
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
            $fb = new Facebook([
                'app_id' => $app_id,
                'app_secret' => $app_secret,
                'default_graph_version' => 'v2.5',
//                'default_access_token' => $app_id . '|' . $app_secret,
            ]);
            
            echo("Facebook query parameters are " . $fb_query . "</br>");
            echo("Facebook app_id is " . $app_id . "</br>");
            echo("Facebook app_secret is " . $app_secret . "</br>");
            
            // Use one of the helper classes to get a Facebook\Authentication\AccessToken entity.
            //   $helper = $fb->getRedirectLoginHelper();
            //   $helper = $fb->getJavaScriptHelper();
            //   $helper = $fb->getCanvasHelper();
            //   $helper = $fb->getPageTabHelper();
            
            // var_dump($helper);
            
            try {
                // Get the \Facebook\GraphNodes\GraphUser object for the current user.
                // If you provided a 'default_access_token', the '{access-token}' is optional.
                $response = $fb->get('/me'.$fb_query);
            } catch(\Facebook\Exceptions\FacebookResponseException $e) {
                // When Graph returns an error
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch(\Facebook\Exceptions\FacebookSDKException $e) {
                // When validation fails or other local issues
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
            
            $me = $response->getGraphUser();
            echo 'Logged in as ' . $me->getName();
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
        $myPresence = $em->getRepository('Application\Entity\SocialMediaPresence')->findOneBy(array('id'=>'4'));
        $app_id = $myPresence->getSocialMediaGateway()->getAppId();
        $app_secret = $myPresence->getSocialMediaGateway()->getAppSecret();
        $fb = new Facebook([
            'app_id' => $app_id,
            'app_secret' => $app_secret,
            'default_graph_version' => 'v2.5',
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
            
            // inject the token for displaying - dev
            return array('token' => $accessToken);
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
            'default_graph_version' => 'v2.5',
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
}