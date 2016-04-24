<?php

namespace Spicy\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Facebook\Facebook;

class FacebookController extends Controller
{
    public function testAction()
    {       
        
        return $this->render('SpicyAppBundle:Facebook:test.html.twig');
    }
    
    public function loginAction() 
    {        
        if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY'))            
            throw new \Exception ('Pas autorisé', 403);
        
        $facebookManager = $this->container->get('mimizik.app.facebook.manager');       
        
        if(!isset($_SESSION['facebook_access_token']) || $_SESSION['facebook_access_token']!='')
        {                        
            $fb=$facebookManager->getFacebookObject();

            $helper = $fb->getRedirectLoginHelper();
            $permissions = ['manage_pages', 'publish_pages']; // optional
            $loginUrl = $helper->getLoginUrl($this->generateUrl('mimizik_app_fb_token',array(),true), $permissions);

            return $this->redirect($loginUrl);        
        }
        else
        {
            return $this->redirect($this->generateUrl('mimizik_app_fb_token'));
        }
        
        return $this->render('SpicyAppBundle:Facebook:login.html.twig',array(
            'accessToken'=>$accessToken
                
        ));
    }
    
    public function tokenAction() {
        
        if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY'))            
            throw new \Exception ('Pas autorisé', 403);
        
        $facebookManager = $this->container->get('mimizik.app.facebook.manager');
        $em=$this->getDoctrine()->getManager();
        $video=$em->getRepository('SpicySiteBundle:Video')->find($_SESSION['id_video_publish']);
        $pageId=$this->container->getParameter('app_fb_page_id');
        
        if ($video == null) {
            throw $this->createNotFoundException('Video inexistant');
        }
        
        $fb=$facebookManager->getFacebookObject();
        
        $helper = $fb->getRedirectLoginHelper();
        
        
        
        try {
          $accessToken = $helper->getAccessToken();
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
          // When Graph returns an error
          echo 'Graph returned an error: ' . $e->getMessage();
          exit;
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
          // When validation fails or other local issues
          echo 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }                
        
        $response=$fb->get("/$pageId?fields=access_token",$accessToken);

        $accessToken=json_decode($response->getBody())->access_token;

        if (isset($accessToken)) {
            // Logged in!
            $accessToken=$_SESSION['facebook_access_token'] = (string) $accessToken;

            try {
                // Returns a `Facebook\FacebookResponse` object
                  $date=new \DateTime('NOW');
                  $date=$date->modify( '+15 minutes' );
                  $message='Nouveau titre sur Mimizik.com : ';
                  $message=$message.$video->getDescription();
                  $message=$message.' #mimizik';

                $params=[
                    'message' => $message,
                    'published'=>false,
                    'scheduled_publish_time'=>  $date->getTimestamp(),
                    'link'=>$this->generateUrl('spicy_site_video_slug',array('id'=>$video->getId(),'slug'=>$video->getSlug()),true)
                ];
                
                $response = $fb->post("/$pageId/feed", $params, $accessToken);
                
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
              echo 'Graph returned an error: ' . $e->getMessage();
              exit;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
              echo 'Facebook SDK returned an error: ' . $e->getMessage();
              exit;
            }

          $graphNode = $response->getGraphNode();
            return $this->redirect($this->generateUrl('spicy_admin_home_video'));
        }
        else
        {
            throw new \Exception ('Erreur de Token', 500);
        }
        
        
        //var_dump($graphNode);
        
        return $this->render('SpicyAppBundle:Facebook:token.html.twig',array(
            'accessToken'=>$accessToken
                
        ));
    }
}
