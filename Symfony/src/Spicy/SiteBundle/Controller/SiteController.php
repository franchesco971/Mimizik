<?php

namespace Spicy\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Spicy\SiteBundle\Entity\Video;
use Spicy\SiteBundle\Entity\TypeVideo;
use Spicy\SiteBundle\Entity\GenreMusical;
use Spicy\SiteBundle\Form\TypeVideoType;
use Symfony\Component\HttpFoundation\Request;

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
        $txtGenre='';
        
        $video=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Video')
                ->getOneAvecArtistes($id);
        
         if ($video == null) {
            throw $this->createNotFoundException('Video inexistant');
        }
        
        $genres=$video->getGenreMusicaux();
        $nbGenres=  count($genres);
        $i=0;
        foreach ($genres as $genre) {
            $txtGenre=$txtGenre.$genre->getId();
            if($i+1<$nbGenres){$txtGenre=$txtGenre.',';}
            $i++;
        }
        
        $suggestions=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Video')
                ->getSuggestions($txtGenre,$video->getId());
        
        $socialService = $this->container->get('mimizik.social');
        $tags=$socialService->getHashtags($video);
        
        return $this->render('SpicySiteBundle:Site:show.html.twig',array(
            'lavideo'=>$video,
            'suggestions'=>$suggestions,
            'tags'=>$tags
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
        
        if($artiste == null) {
            throw $this->createNotFoundException('Artiste inexistant');
        }
        
        $videos=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Video')
                ->getByArtiste($id,$nbSuggestion);
        
         if ($videos == null) {
            throw $this->createNotFoundException('Video inexistant');
        }
        
        $tabGenres=array();
        $tabIdGenres=array();
        foreach ($videos as $video) {
            $genres=  $video->getGenreMusicaux();
            foreach ($genres as $genre) 
            {
                if(!in_array($genre, $tabGenres))
                {
                    $tabGenres[]=$genre;
                    $tabIdGenres[]=$genre->getId();
                }
            }
        }
        
        $suggestions=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Video')
                ->getSuggestionsArtistes($tabIdGenres);
        
        if($suggestions == null) {
            throw $this->createNotFoundException('Suggestion inexistant');
        }
        
        $tabArtistes=array();
        
        foreach ($suggestions as $suggestion)
        {
            foreach ($suggestion->getArtistes() as $sArtiste)
            {
                if(!in_array($sArtiste, $tabArtistes) && $sArtiste->getId()!=$id)
                {
                    $tabArtistes[]=$sArtiste;
                    
                }            
            }
        }
        
        $socialService = $this->container->get('mimizik.social');
        $fbLink=$socialService->getFacebookLink($artiste);
        $twitterLinks=$socialService->getArrayTwitterLink($artiste);
        
        return $this->render('SpicySiteBundle:Site:showArtiste.html.twig',array(
            'fbLink'=>$fbLink,
            'twitterLinks'=>$twitterLinks,
            'videos'=>$videos,
            'artiste'=>$artiste,
            'genres'=>$tabGenres,
            'suggestions'=>$tabArtistes
        ));
    }
    
    public function indexGenreAction($page)
    {
        $nbGenreAffiche=$this->container->getParameter('nbGenreAffiche');
        
        $genres=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:GenreMusical')
                ->getAll($page,$nbGenreAffiche);
        
        if($genres == null) {
            throw $this->createNotFoundException('Genre inexistant');
        }
               
        return $this->render('SpicySiteBundle:Site:indexGenre.html.twig',array(
            'genres'=>$genres,
            'nombrePage'=>ceil((count($genres))/ $nbGenreAffiche),
            'page'=>$page
        ));
    }
    
    public function showGenreAction(GenreMusical $genre,$page)
    {
        if($page=='__id__')
        {
            return $this->redirect($this->generateUrl('spicy_site_genre',array('id'=>$genre->getId(),'page'=>1,'slug'=>$genre->getSlug())));
        }
        
        $nbSuggestion=$this->container->getParameter('nbVideosGenreAffiche');
        
        $videos=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Video')
                ->getByGenre($genre->getId(),$nbSuggestion,$page);
        
         if ($videos == null) {
            throw $this->createNotFoundException('Video inexistant');
        }
        
        return $this->render('SpicySiteBundle:Site:showGenre.html.twig',array(
            'videos'=>$videos,
            'genre'=>$genre,
            'nombrePage'=>ceil((count($videos))/ $nbSuggestion),
            'page'=>$page
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
    
    public function retroAction()
    {
        $video=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Video')
                ->getRetro();
        
        if ($video == null) {
            throw $this->createNotFoundException('Video inexistant');
        }
        
        return $this->render('SpicySiteBundle:Site:retro.html.twig',array(
            'video'=>$video
                
        ));
    }       
    
    public function listArtisteAction()
    {
        $request = $this->container->get('request');
        $page=1;
        
        if($request->isXmlHttpRequest())
        {   
            $page=$request->request->get("page");
            
            $nbArtisteAffiche=$this->container->getParameter('nbArtisteAffiche');

            $artistes=$this->getDoctrine()
                    ->getManager()
                    ->getRepository('SpicySiteBundle:Artiste')
                    ->getAll($page,$nbArtisteAffiche);

            if($artistes == null) {
                throw $this->createNotFoundException('Artiste inexistant');
            }
            
            return $this->render('SpicySiteBundle:Site:listArtiste.html.twig',array(
                'artistes'=>$artistes                
            ));
        }
        else {
            return $this->render('SpicySiteBundle:Site:listArtiste.html.twig');
        }
    }
    
    public function listAlphaAction()
    {
        $request = $this->container->get('request');
        $page=1;
        
        if($request->isXmlHttpRequest())
        {   
            $lettre=$request->request->get("lettre");

            $artistes=$this->getDoctrine()
                    ->getManager()
                    ->getRepository('SpicySiteBundle:Artiste')
                    ->getAlpha($lettre);
  
            return $this->render('SpicySiteBundle:Site:listArtiste.html.twig',array(
                'artistes'=>$artistes                
            ));
        }
        else {
            return $this->render('SpicySiteBundle:Site:listArtiste.html.twig');
        }
    }
    
    public function alphabetAction()
    {
        
        return $this->render('SpicySiteBundle:Site:alphabet.html.twig');
    }
    
    public function TopMoisAction()
    {
        $row = 1;
        $tab=array();
        
        if (($handle = fopen("csv/test4.csv", "r")) !== FALSE) 
        {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
            {
                $num = count($data);
                
                for ($c=0; $c < $num; $c++) 
                {   
                    if(preg_match('/^\/video/', $data[$c]))
                    {
                        list($rien,$debut,$id,$reste)=  explode('/',$data[$c] );
                        $video=$this->getDoctrine()
                            ->getManager()
                            ->getRepository('SpicySiteBundle:Video')
                            ->getOneAvecArtistes($id);
                        
                        $nbVues=$data[$c+1];
                        $txt[]=array($video,$nbVues);
                    }
                }
            }
            fclose($handle);
        }
        
        return $this->render('SpicySiteBundle:Site:topMois.html.twig',array(
            'txt'=>$txt
        ));
    }
    
    public function genresAction() 
    {
        $genres=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:GenreMusical')
                ->getAllGenres();
        
        if ($genres == null) {
            throw $this->createNotFoundException('Genres inexistant');
        }
        
        return $this->render('SpicySiteBundle:Site:genresMenu.html.twig',array(
            'genres'=>$genres                
        ));
    }
    
    public function redirectYoutubeAction()
    {
       return $this->redirect('http://www.youtube.com/user/mimizikcom');
    }
}
