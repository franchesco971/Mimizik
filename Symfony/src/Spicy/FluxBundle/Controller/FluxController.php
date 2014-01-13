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
            'artistes'=>$artistes                
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
            'videos'=>$videos                
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
            'descriptions'=>$arrayDescriptions
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
            'videos'=>$videos                
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
            'descriptions'=>$arrayDescriptions
        ));
    }
}
