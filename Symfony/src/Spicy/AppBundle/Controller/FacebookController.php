<?php

namespace Spicy\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Facebook\Facebook;

class FacebookController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SpicyAppBundle:Default:index.html.twig', array('name' => $name));
    }
    
    public function loginAction() {
        
        $accessToken=null;
        
        $fb = new Facebook([
            'app_id' => '729891760354276',
            'app_secret' => 'f99b1e647c6db07975a37cc287c0d91b',
            'default_graph_version' => 'v2.5',
          ]);
        
        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email', 'user_likes']; // optional
        $loginUrl = $helper->getLoginUrl($this->generateUrl('mimizik_app_fb_token'), $permissions);
        
        //$this->redirect($loginUrl);
        var_dump($loginUrl);
        
        return $this->redirect($loginUrl);
    }
    
    public function tokenAction() {
        $fb = new Facebook([
            'app_id' => '729891760354276',
            'app_secret' => 'f99b1e647c6db07975a37cc287c0d91b',
            'default_graph_version' => 'v2.5',
        ]);

        $helper = $fb->getRedirectLoginHelper();
        
        var_dump($helper->getCode());
        
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

        if (isset($accessToken)) {
          // Logged in!
          $_SESSION['facebook_access_token'] = (string) $accessToken;

          // Now you can redirect to another page and use the
          // access token from $_SESSION['facebook_access_token']
        }
        
        var_dump($accessToken);
        return $this->render('SpicyAppBundle:Facebook:token.html.twig',array(
            'accessToken'=>$accessToken
                
        ));
    }
}
