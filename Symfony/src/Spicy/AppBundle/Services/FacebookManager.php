<?php

namespace Spicy\AppBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Spicy\SiteBundle\Entity\Video;

class FacebookManager
{
    private $container;
    
    private $fbManager;
    
    private $userAccessToken;
    private $pageAccessToken;
    private $pageId;

    public function __construct(Container $container) {
        $this->container=$container;
        $this->fbManager=  $this->getFacebookObject();
        $this->pageId=$this->container->getParameter('app_fb_page_id');
        
        
    }
    
    public function getFacebookObject() 
    {
        $params=[
            'app_id'                => $this->container->getParameter('app_fb_id'),
            'app_secret'            => $this->container->getParameter('app_fb_secret'),
            'default_graph_version' => $this->container->getParameter('app_fb_graph_version'),
          ];

        $fb = new Facebook($params);
        
        return $fb;
    }
    
    public function setFbUserAccessToken()
    {        
        if(!isset($_SESSION['FB_USER_ACCESS_TOKEN']) || $_SESSION['FB_USER_ACCESS_TOKEN']=='')
        {
            $helper = $this->fbManager->getRedirectLoginHelper();
            
            try {
                $userAccessToken = $helper->getAccessToken();
            } catch(FacebookResponseException $e) {
              // When Graph returns an error
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch(FacebookSDKException $e) {
              // When validation fails or other local issues
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            } 
                        
            $_SESSION['FB_USER_ACCESS_TOKEN']=$userAccessToken;            
        }
        else
        {
            $userAccessToken=$_SESSION['FB_USER_ACCESS_TOKEN'];
        }
        
        $this->userAccessToken=$userAccessToken;
    }
    
    public function setPageAccessToken() 
    {        
        if(!isset($_SESSION['FB_PAGE_ACCESS_TOKEN']) || $_SESSION['FB_PAGE_ACCESS_TOKEN']=='')
        {
            $response=$this->fbManager->get("/$this->pageId?fields=access_token",$this->userAccessToken);

            $pageAccessToken=json_decode($response->getBody())->access_token;
            
            $_SESSION['FB_PAGE_ACCESS_TOKEN']=$pageAccessToken;
        }
        else
        {
            $pageAccessToken=$_SESSION['FB_PAGE_ACCESS_TOKEN'];
        }
        
        $this->pageAccessToken=$pageAccessToken;
    }
    
    public function postFeed($params) 
    {
        try {
            // Returns a `Facebook\FacebookResponse` object                                  
            $response = $this->fbManager->post("/$this->pageId/feed", $params, $this->pageAccessToken);

        } catch(Facebook\Exceptions\FacebookResponseException $e) {
          echo 'Graph returned an error: ' . $e->getMessage();
          exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
          echo 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }
        
        return $response;
    }
    
    public function getPageAccessToken() {
        return $this->pageAccessToken;
    }
}
