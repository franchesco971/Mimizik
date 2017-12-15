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
            'selfLink'=>$this->generateUrl('spicy_site_flux_videos'),
            'pubDates'=>  $this->getTabData($videos)                
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
            'selfLink'=>$this->generateUrl('spicy_site_flux_videos_twitter'),
            'pubDates'=>  $this->getTabData($videos)
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
            'selfLink'=>$this->generateUrl('spicy_site_flux_retro')  ,
            'pubDates'=>  $this->getTabData($videos)             
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
            'selfLink'=>$this->generateUrl('spicy_site_flux_retro_twitter'),
            'pubDates'=>  $this->getTabData($videos)
        ));
    }
    
    public function fluxVideosTopAction()
    {
        $videoService = $this->container->get('mimizik.videoService');
        $videos=$videoService->videosTop(5,2);
        
        //return $this->render('SpicySiteBundle:Site:test.html.twig',array(        
        return $this->render('SpicyFluxBundle:Flux:fluxVideos.html.twig',array(
            'videos'=>$videos,
            'selfLink'=>$this->generateUrl('mimizik_flux_videos_top'),
            'pubDates'=>  $this->getTabData($videos,true)
        ));
    }
    
    public function fluxVideosTopTwitterAction()
    {
        $videos=$this->videosTop();
        $twitterService = $this->container->get('mimizik.twitter');
        $arrayDescriptions=$twitterService->twitterType($videos);
                
        return $this->render('SpicyFluxBundle:Flux:videosTwitter.html.twig',array(
            'videos'=>$videos,
            'descriptions'=>$arrayDescriptions,
            'selfLink'=>$this->generateUrl('mimizik_flux_videos_top_twitter'),
            'pubDates'=>  $this->getTabData($videos,true)
        ));
    }
    
    public function getTabData($videos,$top=false) 
    {
        $datas=array();
        foreach ($videos as $video) {
            if($top)
            {
                $pubDate=new \DateTime('NOW');
            }
            else
            {
                $pubDate=$video->getDateVideo();
            }
            $datas[$video->getId()]=array('pubDate'=>$pubDate);
        }
        
        return $datas;
    }
    
    /**
     * 
     * @return type
     */
    public function fluxLyricsAction()
    {
        $lyrics = $this->container->get('mimizik.repository.paroles')->getAll(10);
        
        return $this->render('SpicyFluxBundle:Flux:fluxLyrics.html.twig',array(
            'lyrics' => $lyrics,
            'selfLink' => $this->generateUrl('mimizik_flux_lyrics')
        ));
    }
    
    /**
     * 
     * @return type
     */
    public function fluxITWAction()
    {
        $interviews = $this->container->get('mimizik.repository.itw')->getAll(10);
        
        return $this->render('SpicyFluxBundle:Flux:fluxITW.html.twig',array(
            'interviews' => $interviews,
            'selfLink' => $this->generateUrl('spicy_site_artistes')
        ));
    }
}


