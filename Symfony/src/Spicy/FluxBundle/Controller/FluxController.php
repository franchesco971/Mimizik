<?php

namespace Spicy\FluxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FluxController extends Controller
{
    public function fluxArtistesAction()
    {
        $artistes=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Artiste')
                ->getFlux();
        
        if ($artistes == null) {
            throw $this->createNotFoundException('Artiste inexistant');
        }
        
        return $this->render('SpicyFluxBundle:Flux:fluxArtistes.html.twig',array(
            'artistes'=>$artistes,
            'selfLink'=>$this->generateUrl('spicy_site_flux_artistes')                
        ));
    }
    
    public function fluxIndexAction()
    {        
        return $this->render('SpicyFluxBundle:Flux:fluxIndex.html.twig');
    }
    
    public function fluxVideosAction()
    {
        $videos=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Video')
                ->getAvecArtistes(30);
        
        if ($videos == null) {
            throw $this->createNotFoundException('Video inexistant');
        }
                
        return $this->render('SpicyFluxBundle:Flux:fluxVideos.html.twig',array(
            'videos'=>$videos,
            'selfLink'=>$this->generateUrl('spicy_site_flux_videos')                
        ));
    }
    
    public function fluxVideosTwitterAction()
    {
        $videos=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Video')
                ->getAvecArtistes(30);
        
        if ($videos == null) {
            throw $this->createNotFoundException('Video inexistant');
        }
        
        $twitterService = $this->container->get('mimizik.twitter');
        $arrayDescriptions=$twitterService->twitterType($videos);
        
        
        return $this->render('SpicyFluxBundle:Flux:videosTwitter.html.twig',array(
            'videos'=>$videos,
            'descriptions'=>$arrayDescriptions,
            'selfLink'=>$this->generateUrl('spicy_site_flux_videos_twitter')
        ));
    }
    
    public function fluxRetroAction()
    {
        $videos=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Video')
                ->getAllRetro(50);
        
        if ($videos == null) {
            throw $this->createNotFoundException('Video inexistant');
        }
        
        return $this->render('SpicyFluxBundle:Flux:fluxVideos.html.twig',array(
            'videos'=>$videos,
            'selfLink'=>$this->generateUrl('spicy_site_flux_retro')               
        ));
    }
    
    public function fluxRetroTwitterAction()
    {
        $videos=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Video')
                ->getAllRetro(50);
        
        if ($videos == null) {
            throw $this->createNotFoundException('Video inexistant');
        }
        
        $twitterService = $this->container->get('mimizik.twitter');
        $arrayDescriptions=$twitterService->twitterType($videos);
        
        return $this->render('SpicyFluxBundle:Flux:videosTwitter.html.twig',array(
            'videos'=>$videos,
            'descriptions'=>$arrayDescriptions,
            'selfLink'=>$this->generateUrl('spicy_site_flux_retro_twitter')
        ));
    }
    
    public function fluxVideosTopAction()
    {
        $videos=$this->videosTop();
        
        //return $this->render('SpicySiteBundle:Site:test.html.twig',array(        
        return $this->render('SpicyFluxBundle:Flux:fluxVideos.html.twig',array(
            'videos'=>$videos,
            'selfLink'=>$this->generateUrl('mimizik_flux_videos_top')                
        ));
    }
    
    public function videosTop()
    {
        $em=$this->getDoctrine()->getManager();
        
        $videos=$em
                ->getRepository('SpicySiteBundle:Video')
                ->getFlux(7,true);

        if ($videos == null) {
            throw $this->createNotFoundException('Video inexistant');
        }
        
        shuffle($videos);
        
        return $videos;
    }
    
    public function fluxVideosTopTwitterAction()
    {
        $videos=$this->videosTop();
        $twitterService = $this->container->get('mimizik.twitter');
        $arrayDescriptions=$twitterService->twitterType($videos);
                
        return $this->render('SpicyFluxBundle:Flux:videosTwitter.html.twig',array(
            'videos'=>$videos,
            'descriptions'=>$arrayDescriptions,
            'selfLink'=>$this->generateUrl('mimizik_flux_videos_top_twitter')
        ));
    }
}


