<?php

namespace Spicy\AppBundle\Services;

use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

class FacebookManager
{
    private $fbManager;
    private $userAccessToken;
    private $pageAccessToken;
    private $pageId;

    public function __construct($fbParams, $fbPageID)
    {
        $this->fbManager = new Facebook($fbParams);
        $this->pageId = $fbPageID;
    }

    public function getFacebookObject()
    {
        return $this->fbManager;
    }

    public function setFbUserAccessToken()
    {
        if (!isset($_SESSION['FB_USER_ACCESS_TOKEN']) || $_SESSION['FB_USER_ACCESS_TOKEN'] == '') {
            $helper = $this->fbManager->getRedirectLoginHelper();

            try {
                $userAccessToken = $helper->getAccessToken("https://www.mimizik.com/apps/facebook/token");
            } catch (FacebookResponseException $e) {
                // When Graph returns an error
                throw new \Exception("FacebookResponseException :: " . $e->getMessage());
            } catch (FacebookSDKException $e) {
                // When validation fails or other local issues
                throw new \Exception("FacebookSDKException :: " . $e->getMessage());
            }

            $_SESSION['FB_USER_ACCESS_TOKEN'] = $userAccessToken;
        } else //déjà connecté
        {
            $userAccessToken = $_SESSION['FB_USER_ACCESS_TOKEN'];
        }

        $this->userAccessToken = $userAccessToken;
    }

    public function setPageAccessToken()
    {
        if (!isset($_SESSION['FB_PAGE_ACCESS_TOKEN']) || $_SESSION['FB_PAGE_ACCESS_TOKEN'] == '') {
            $response = $this->fbManager->get("/$this->pageId?fields=access_token", $this->userAccessToken);

            $pageAccessToken = json_decode($response->getBody())->access_token;

            $_SESSION['FB_PAGE_ACCESS_TOKEN'] = $pageAccessToken;
        } else //déjà connecté
        {
            $pageAccessToken = $_SESSION['FB_PAGE_ACCESS_TOKEN'];
        }

        $this->pageAccessToken = $pageAccessToken;
    }

    public function postFeed($params)
    {
        try {
            // Returns a `Facebook\FacebookResponse` object                                  
            $response = $this->fbManager->post("/$this->pageId/feed", $params, $this->pageAccessToken);
        } catch (FacebookResponseException $e) {
            throw new \Exception("FacebookResponseException :: " . $e->getMessage());
        } catch (FacebookSDKException $e) {
            throw new \Exception("FacebookSDKException :: " . $e->getMessage());
        }

        return $response;
    }

    public function getPageAccessToken()
    {
        return $this->pageAccessToken;
    }

    public function setTokens()
    {
        $this->setFbUserAccessToken();
        $this->setPageAccessToken();
    }
}
