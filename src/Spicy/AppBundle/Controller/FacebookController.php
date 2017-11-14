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
        
        if(!isset($_SESSION['FB_USER_ACCESS_TOKEN']) || $_SESSION['FB_USER_ACCESS_TOKEN']=='')
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
        $videoManager = $this->container->get('mimizik.videoService');
        $em=$this->getDoctrine()->getManager();
        $videoId=(isset($_SESSION['id_video_publish']))?$_SESSION['id_video_publish']:0;
        $video=$em->getRepository('SpicySiteBundle:Video')->find($videoId);
        
        if ($video == null) {
            throw $this->createNotFoundException('Video inexistant');
        }   
        
        $facebookManager->setFbUserAccessToken();
        $facebookManager->setPageAccessToken();
        
        $pageAccessToken=$facebookManager->getPageAccessToken();
        if (isset($pageAccessToken)) {
            // Si la video est active
            if($video->getEtat()){
                $date=new \DateTime('NOW');
                $publishDate=$date->modify( '+15 minutes' );
                $link=$this->generateUrl('spicy_site_video_slug',array('id'=>$video->getId(),'slug'=>$video->getSlug()),true);
                $link = $videoManager->getCleanLink($link);

                $params=[
                    'message' => $videoManager->getMessage($video),
                    'published'=>false,
                    'scheduled_publish_time'=>  $publishDate->getTimestamp(),
                    'link'=>$link
                ];

                $response=  $facebookManager->postFeed($params);
            
            
                //publication de videos du top
                $randTopVideos = $videoManager->videosTop(10,1);
                if($randTopVideos) {
                    $dateDemain=new \DateTime('TOMORROW');
                    foreach ($randTopVideos as $randTopVideo) {
                        $date=$randTopVideo->getNextPublishDate();
                        $publishDate=$publishDate->modify('+16 hours');
                        $link=$this->generateUrl('spicy_site_video_slug',array('id'=>$randTopVideo->getId(),'slug'=>$randTopVideo->getSlug()),true);
                        $link = $videoManager->getCleanLink($link);

                        $paramsTop=[
                            'message' => $videoManager->getMessage($randTopVideo,$videoManager::TOP_VIDEO),
                            'published'=>false,
                            'scheduled_publish_time'=>  $publishDate->getTimestamp(),
                            'link'=>$link
                        ];

                        if($date)
                        {
                            $date=$date->format('Y-m-d');
                            $date=new \DateTime($date);
                            if($date!=$dateDemain)
                            {
                                $response=  $facebookManager->postFeed($paramsTop);
                                $randTopVideo->setNextPublishDate($publishDate);
                                break;
                            }
                        }
                        else
                        {
                            $response=  $facebookManager->postFeed($paramsTop);
                            $randTopVideo->setNextPublishDate($publishDate);
                            break;
                        }
                        $this->get('session')->getFlashBag()->add('info','La vidéo sera publié dans 15 min');
                    }

                    $em->flush();
                }
            }

          //$graphNode = $response->getGraphNode();
            return $this->redirect($this->generateUrl('spicy_admin_home_video'));
        }
        else
        {
            throw new \Exception ('Erreur de Token', 500);
        }
        
        return $this->render('SpicyAppBundle:Facebook:token.html.twig',array(
            'accessToken'=>$accessToken
                
        ));
    }
}
