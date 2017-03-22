<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Dimension;
use Application\Entity\User;
use Application\Entity\Enterprise;
use Application\Entity\AccessToken;
use Application\Entity\SocialMediaGateway;
use Application\Entity\SocialMediaPresence;

/**
 * SetupController
 *
 * @author
 *
 * @version
 *
 */
class SetupController extends BaseController
{

    /**
     * The default action - show the home page
     */
    public function indexAction()
    {
        // TODO Auto-generated SetupController::indexAction() default action
        return new ViewModel();
    }
    public function populateDimensionAction()
    {
        $dimension_array = array(
            "post_story_adds_unique",
            "post_story_adds",
            "post_story_adds_by_action_type_unique",
            "post_story_adds_by_action_type",
            "post_impressions_unique",
            "post_impressions",
            "post_impressions_paid_unique",
            "post_impressions_paid",
            "post_impressions_organic_unique",
            "post_impressions_organic",
            "post_impressions_viral_unique",
            "post_impressions_viral",
            "post_impressions_by_story_type_unique",
            "post_impressions_by_story_type",
            "post_impressions_fan_unique",
            "post_impressions_fan",
            "post_impressions_fan_paid_unique",
            "post_impressions_fan_paid",
            "post_impressions_by_paid_non_paid_unique",
            "post_impressions_by_paid_non_paid",
            "post_video_length",
            "post_video_retention_graph",
            "post_video_retention_graph_autoplayed",
            "post_video_retention_graph_clicked_to_play",
            "post_video_avg_time_watched",
            "post_video_views",
            "post_video_views_unique",
            "post_video_views_autoplayed",
            "post_video_views_clicked_to_play",
            "post_video_views_organic",
            "post_video_views_organic_unique",
            "post_video_complete_views_organic",
            "post_video_complete_views_organic_unique",
            "post_video_views_paid",
            "post_video_views_paid_unique",
            "post_video_complete_views_paid",
            "post_video_complete_views_paid_unique",
            "post_video_complete_views_30s",
            "post_video_complete_views_30s_unique",
            "post_video_complete_views_30s_autoplayed",
            "post_video_complete_views_30s_clicked_to_play",
            "post_video_complete_views_30s_organic",
            "post_video_complete_views_30s_paid",
            "post_consumptions_unique",
            "post_consumptions",
            "post_consumptions_by_type_unique",
            "post_consumptions_by_type",
            "post_negative_feedback_unique",
            "post_negative_feedback",
            "post_negative_feedback_by_type_unique",
            "post_negative_feedback_by_type",
            "post_engaged_fan",
            "post_fan_reach",
            "post_storytellers",
            "post_storytellers_by_action_type",
            "post_engaged_users",
            "post_stories",
            "post_stories_by_action_type",
            "post_interests_impressions_unique",
            "post_interests_impressions",
            "post_interests_consumptions_unique",
            "post_interests_consumptions",
            "post_interests_consumptions_by_type_unique",
            "post_interests_consumptions_by_type",
            "post_interests_action_by_type_unique",
            "post_video_views_10s",
            "post_video_views_10s_unique",
            "post_video_views_10s_paid",
            "post_video_views_10s_organic",
            "post_video_views_10s_clicked_to_play",
            "post_video_views_10s_organic",
            "post_video_views_10s_autoplayed",
            "post_video_views_10s_sound_on",
            "post_video_views_sound_on"
        );
        $em = $this->getEntityManager();
        $testDimension = $em->getRepository('Application\Entity\Dimension')->findAll();
        if($testDimension)
        {
            echo("Dimension population already performed:");
        }
        else 
        {    
            foreach ($dimension_array as $dimension)
            {
                $myDimension = new Dimension();
                $myDimension->setName($dimension);

                $this->getEntityManager()->persist($myDimension);
            }
            $this->getEntityManager()->flush();
//             var_dump($dimension_array);
            echo("</br>dimension model populated");
        }
    }
    public function initialEntriesAction()
    {
        $user_array = array(
            1=>array(
                'enterprise_id'=>1,
                'username'=>"admin",
                'email'=>"admin@email.com",
                'display_name'=>"Administrator",
                'password'=>"password"
            ),
        );
        $enterprise_array = array(
            1=>array(
                'name'=>"SADB Application Administration",
            ),
        );
        $em = $this->getEntityManager();
        $testEnterprise = $em->getRepository('Application\Entity\Enterprise')->findOneBy(array('name'=>'SADB Application Administration'));
        $myEnterprise = new Enterprise();
        if($testEnterprise)
        {
            // var_dump($testEnterprise);
            $myEnterprise=$testEnterprise;
            echo("Enterprise generation already performed</br>");
        }
        else
        {
            foreach ($enterprise_array as $enterprise)
            {
//                 $myEnterprise = new Enterprise();
                $myEnterprise->setName($enterprise['name']);
        
                $this->getEntityManager()->persist($myEnterprise);
            }
            $this->getEntityManager()->flush();
            // var_dump($myEnterprise);
            echo("enterprise model populated</br>");
        }
        
        $testUser = $em->getRepository('Application\Entity\User')->findOneBy(array('username'=>'admin'));
        if($testUser)
        {
            // echo("Dimension population already performed:");
            //var_dump($testUser);
            echo("User generation already performed</br>");
        }
        else
        {
            foreach ($user_array as $user)
            {
                $myUser = new User();
                $myUser->setEnterprise($myEnterprise);
                $myUser->setUsername($user['username']);
                $myUser->setEmail($user['email']);
                $myUser->setDisplayname($user['display_name']);
                $myUser->setPassword($user['password']);
        
                $this->getEntityManager()->persist($myUser);
            }
            $this->getEntityManager()->flush();
            // var_dump($myUser);
            echo("user model populated</br>");
        }
    }
    
    public function socialMediaAction()
    {
        $config = $this->getServiceLocator()->get('Config');
        $em = $this->getEntityManager();
        
        $token_array = array(
            1=>array(
                'token'=>'1234',
            ),
        );
        $gateway_array = array(
            1=>array(
                'name'=>"Facebook",
                'app_id'=>$config['facebook_constants']['test_page']['app_id'],
                'app_secret'=>$config['facebook_constants']['test_page']['app_secret'],
            ),
        );
        $presence_array = array(
            1=>array(
                'name'=>"Test Facebook Page",
            ),
        );
        
        $testToken = $em->getRepository('Application\Entity\AccessToken')->findOneBy(array('token'=>'1234'));
        $myToken = new AccessToken();
        if($testToken)
        {
            $myToken=$testToken;
            echo("Token generation already performed</br>");
        }
        else
        {
            foreach ($token_array as $token)
            {
                //                 $myEnterprise = new Enterprise();
                $myToken->setToken($token['token']);
                $em->persist($myToken);
            }
//            $this->getEntityManager()->flush();
            echo("token model populated</br>");
        }
        
        $testGateway = $em->getRepository('Application\Entity\SocialMediaGateway')->findOneBy(array('name'=>'Facebook'));
        $myGateway = new SocialMediaGateway();
        if($testGateway)
        {
            $myGateway=$testGateway;
            echo("Social Media Gateway generation already performed</br>");
        }
        else
        {
            foreach ($gateway_array as $gateway)
            {
                $myGateway->setAccessToken($myToken);
                $myGateway->setAppId($gateway['app_id']);
                $myGateway->setAppSecret($gateway['app_secret']);
                $myGateway->setName($gateway['name']);
                
                $myToken->setSocialMediaGateway($myGateway);
                $em->persist($myGateway);
                $em->persist($myToken);
            }
            // $em->flush();
            echo("Social Media Gateway model populated</br>");
        }
        
        $testPresence = $em->getRepository('Application\Entity\SocialMediaPresence')->findOneBy(array('name'=>'Test Facebook Page'));
        $myPresence = new SocialMediaPresence();
        if($testPresence)
        {
            $myPresence=$testPresence;
            echo("Social Media Presence generation already performed</br>");
            var_dump($myPresence);
        }
        else
        {
            $testEnterprise = $em->getRepository('Application\Entity\Enterprise')->findOneBy(array('name'=>'SADB Application Administration'));
            foreach ($presence_array as $presence)
            {
                $myPresence->setName($presence['name']);
                $myPresence->setSocialMediaGateway($myGateway);
                $myPresence->setParentEnterprise($testEnterprise);
        
                $myGateway->setSocialMediaPresence($myPresence);
                $testEnterprise->addSocialMediaPresence($myPresence);
                $em->persist($myPresence);
                $em->persist($testEnterprise);
            }
            // $em->flush();
            echo("Social Media Presence model populated</br>");
        }
        $em->flush();
    }
    private function populateEntity()
    {
        //
    }
    public function getFacebookTokenAction()
    {
        if (!session_id()) {
            session_start();
        }
        $em = $this->getEntityManager();
        $myPresence = $em->getRepository('Application\Entity\SocialMediaPresence')->findOneBy(array('id'=>'1'));
        $app_id = $myPresence->getSocialMediaGateway()->getAppId();
        $app_secret = $myPresence->getSocialMediaGateway()->getAppSecret();
        $fb = new \Facebook\Facebook([
            'app_id' => $app_id,
            'app_secret' => $app_secret,
            'default_graph_version' => 'v2.5',
            //                'default_access_token' => $app_id . '|' . $app_secret,
        ]);
        $helper = $fb->getRedirectLoginHelper();
        // $permissions = [];
        // $loginUrl = $helper->getLoginUrl('http://{your-website}/login-callback.php', $permissions);
        $loginUrl = $helper->getLoginUrl('http://localhost/application/fetch/receive_token_callback');
        return array('login_url' => $loginUrl);
    }
}