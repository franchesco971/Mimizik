<?php

namespace Spicy\AppBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Exceptions\FacebookResponseException;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
use Spicy\AppBundle\Services\FacebookManager;
use Spicy\AppBundle\Services\PublishService;

class FacebookController extends Controller
{    
    public function testAction(Request $request, LoggerInterface $logger, FacebookManager $facebookManager)
    {          
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY', null, 'Pas autorisé');
        
        try {
            if(!isset($_SESSION['FB_USER_ACCESS_TOKEN']) || $_SESSION['FB_USER_ACCESS_TOKEN']=='')
            {                        
                $fb=$facebookManager->getFacebookObject();

                $helper = $fb->getRedirectLoginHelper();
                $permissions = ['manage_pages', 'publish_pages']; // optional
                $loginUrl = $helper->getLoginUrl($this->generateUrl('mimizik_app_fb_token',[],true), $permissions);
                //\Symfony\Component\VarDumper\VarDumper::dump($loginUrl);

                return $this->redirect($loginUrl);        
            }
            else // Déjà logger
            {
//                return $this->redirect($this->generateUrl('mimizik_app_fb_token'));
            }
        } catch(\Exception $e) {
            $message = 'Error: '. $e->getMessage();
            $logger->error($message);
            $this->get('session')->getFlashBag()->add('info', $message);
        }
        
//        $facebookManager = $this->container->get('mimizik.app.facebook.manager');
        $publishService = $this->container->get('mimizik.publish.service');
//        $logger = $this->get('logger');
//        $em = $this->getDoctrine()->getManager();
        $videoId = (isset($_SESSION['id_video_publish']))?$_SESSION['id_video_publish']:0;
//        $video = $em->getRepository('SpicySiteBundle:Video')->find($videoId);
        $uri = $request->getUri();
        \Symfony\Component\VarDumper\VarDumper::dump($uri);
        
//        if ($video == null) {
//            throw $this->createNotFoundException('Video inexistant');
//        }   
        
        try {
            //vérifie si on peut récupérer les tokens  
            $facebookManager->setTokens();
            
            // Si la video est active
//            if($video->getEtat()) {
//                $publishService->instantPublish($video);         
//
//                //publication de videos du top
//                $publishService->topRamdomPublish();
//            }
            
            return $this->redirect($this->generateUrl('spicy_admin_home_video'));
        
        } catch(FacebookResponseException $e) {
            $message = 'Graph returned an error: ' . $e->getMessage();
            $logger->error($message);
            $this->get('session')->getFlashBag()->add('info', $message);
        } catch(FacebookSDKException $e) {
            $message = 'Facebook SDK returned an error: ' . $e->getMessage();
            $logger->error($message);
            $this->get('session')->getFlashBag()->add('info', $message);
        } catch(\Exception $e) {
            $message = 'Error: '. $e->getMessage();
            $logger->error($message);
            $this->get('session')->getFlashBag()->add('info', $message);
        }
        
        return $this->render('SpicyAppBundle:Facebook:test.html.twig');
    }
    
    /**
     * 
     * @return type
     * @throws \Exception
     */
    public function loginAction(FacebookManager $facebookManager, LoggerInterface $logger)
    {
        $accessToken = "";
        
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY', null, 'Pas autorisé');

        try {
            if (!isset($_SESSION['FB_USER_ACCESS_TOKEN']) || $_SESSION['FB_USER_ACCESS_TOKEN'] == '') {
                $fb = $facebookManager->getFacebookObject();

                $helper = $fb->getRedirectLoginHelper();
                $permissions = ['manage_pages', 'publish_pages']; // optional
                $tokenUrl = $this->generateUrl('mimizik_app_fb_token', [], true);
                $loginUrl = $helper->getLoginUrl($tokenUrl, $permissions);

                return $this->redirect($loginUrl);
            } else { // Déjà logger
                return $this->redirect($this->generateUrl('mimizik_app_fb_token'));
            }
        } catch (\Exception $e) {
            $message = 'Error: ' . $e->getMessage();
            $logger->error($message);
            $this->get('session')->getFlashBag()->add('info', $message);
        }

        return $this->render('SpicyAppBundle:Facebook:login.html.twig', [
            'accessToken' => $accessToken
        ]);
    }
    
    /**
     * 
     * @return type
     * @throws \Exception
     * @throws type
     */
    public function tokenAction(FacebookManager $facebookManager, LoggerInterface $logger, EntityManagerInterface $em, PublishService $publishService)
    {
        $accessToken = "";

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY', null, 'Pas autorisé');

        $videoId = (isset($_SESSION['id_video_publish'])) ? $_SESSION['id_video_publish'] : 0;
        $video = $em->getRepository('SpicySiteBundle:Video')->find($videoId);

        if ($video == null) {
            throw $this->createNotFoundException('Video inexistant');
        }

        try {
            //vérifie si on peut récupérer les tokens  
            $facebookManager->setTokens();

            // Si la video est active
            if ($video->getEtat()) {
                $publishService->instantPublish($video);

                //publication de videos du top
                $publishService->topRamdomPublish();
            }

            return $this->redirect($this->generateUrl('spicy_admin_home_video'));
        } catch (FacebookResponseException $e) {
            $message = 'Graph returned an error: ' . $e->getMessage();
            $logger->error($message);
            $this->get('session')->getFlashBag()->add('info', $message);
        } catch (FacebookSDKException $e) {
            $message = 'Facebook SDK returned an error: ' . $e->getMessage();
            $logger->error($message);
            $this->get('session')->getFlashBag()->add('info', $message);
        } catch (\Exception $e) {
            $message = 'Error: ' . $e->getMessage();
            $logger->error($message);
            $this->get('session')->getFlashBag()->add('info', $message);
        }

        return $this->render('SpicyAppBundle:Facebook:token.html.twig', array(
            'accessToken' => $accessToken
        ));
    }
}
