<?php

namespace Spicy\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Spicy\SiteBundle\Entity\Video;
use Spicy\SiteBundle\Entity\TypeVideo;
use Spicy\SiteBundle\Entity\GenreMusical;
use Spicy\SiteBundle\Form\TypeVideoType;

class SiteController extends Controller
{
    public function indexAction($page)
    {
        $videos=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Video')
                ->getAvecArtistes($this->container->getParameter('nbMainVideo'));
        
        if ($videos == null) {
            throw $this->createNotFoundException('Video inexistant');
        }
        
        return $this->render('SpicySiteBundle:Site:index.html.twig',array(
            'videos'=>$videos,
            'page'=>$page
        ));
    }
    
    public function indexSuiteAction($page)
    {
        $nbResultVideoIndex=$this->container->getParameter('nbResultVideoIndex');
        $nbMainVideo=$this->container->getParameter('nbMainVideo');
        
        $videos=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Video')
                ->getSuiteAvecArtistes(
                        $page,
                        $nbMainVideo,
                        $nbResultVideoIndex
                );
        
        if ($videos == null) {
            throw $this->createNotFoundException('Video inexistant');
        }
        
        return $this->render('SpicySiteBundle:Site:indexSuite.html.twig',array(
            'autresVideos'=>$videos,
            //'nombrePage'=>ceil(count($videos)/ $nbResultVideoIndex),
            'nombrePage'=>ceil((count($videos)-$nbMainVideo)/ $nbResultVideoIndex),
            'page'=>$page
        ));
    }
    
    public function showAction($id)
    {
        $video=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Video')
                ->getOneAvecArtistes($id);
                //->find($id);
        
         if ($video == null) {
            throw $this->createNotFoundException('Video inexistant');
        }
        
        return $this->render('SpicySiteBundle:Site:show.html.twig',array(
            'video'=>$video
                
        ));
    }
    
    public function indexArtisteAction($page)
    {
        $nbArtisteAffiche=$this->container->getParameter('nbArtisteAffiche');
        
        $artistes=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Artiste')
                ->getAll($page,$nbArtisteAffiche);
        
        //if($artistes == null) 
        if($artistes == null) {
            throw $this->createNotFoundException('Artiste inexistant');
        }
               
        return $this->render('SpicySiteBundle:Site:indexArtiste.html.twig',array(
            'artistes'=>$artistes,
            'nombrePage'=>ceil((count($artistes))/ $nbArtisteAffiche),
            'page'=>$page
        ));
    }
    
    public function showArtisteAction($id)
    {
        $nbSuggestion=$this->container->getParameter('nbSuggestion');
        
        $artiste=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Artiste')
                ->find($id);
        
        $videos=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Video')
                //->getOneAvecArtistes($id);
                ->getByArtiste($id,$nbSuggestion);
        
         if ($videos == null) {
            throw $this->createNotFoundException('Video inexistant');
        }
        
        return $this->render('SpicySiteBundle:Site:showArtiste.html.twig',array(
            'videos'=>$videos,
            'artiste'=>$artiste    
        ));
    }
    
    public function indexGenreAction($page)
    {
        $nbGenreAffiche=$this->container->getParameter('nbGenreAffiche');
        
        $genres=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:GenreMusical')
                ->getAll($page,$nbGenreAffiche);
        
        //if($artistes == null) 
        if($genres == null) {
            throw $this->createNotFoundException('Genre inexistant');
        }
               
        return $this->render('SpicySiteBundle:Site:indexGenre.html.twig',array(
            'genres'=>$genres,
            'nombrePage'=>ceil((count($genres))/ $nbGenreAffiche),
            'page'=>$page
        ));
    }
    
    public function showGenreAction(GenreMusical $genre)
    {
        $nbSuggestion=$this->container->getParameter('nbSuggestion');
        
        /*$artiste=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Genre')
                ->find($id);*/
        
        $videos=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Video')
                //->getOneAvecArtistes($id);
                ->getByGenre($genre->getId(),$nbSuggestion);
        
         if ($videos == null) {
            throw $this->createNotFoundException('Video inexistant');
        }
        
        return $this->render('SpicySiteBundle:Site:showGenre.html.twig',array(
            'videos'=>$videos,
            'genre'=>$genre    
        ));
    }
    
    public function newsAction()
    {
         $parseur = $this->container->get('spicy_site.parseurXML');
         $news=$parseur->parsage();
        
        return $this->render('SpicySiteBundle:Site:news.html.twig',array(
            'news'=>$news
                
        ));
    }
    
    public function creditsAction()
    {
        return $this->render('SpicySiteBundle:Site:credits.html.twig');
    }
    
    public function contactAction()
    {
        return $this->render('SpicySiteBundle:Site:contact.html.twig');
    }
    
    public function testAction()
    {
         $test=1;
        return $this->render('SpicySiteBundle:Site:test.html.twig',array(
            'test'=>$test
                
        ));
    }
    
    public function fluxVideosAction()
    {
        $videos=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Video')
                ->getAvecArtistes(20);
        
        if ($videos == null) {
            throw $this->createNotFoundException('Video inexistant');
        }
        
        return $this->render('SpicySiteBundle:Site:fluxVideos.html.twig',array(
            'videos'=>$videos
                
        ));
    }
}
