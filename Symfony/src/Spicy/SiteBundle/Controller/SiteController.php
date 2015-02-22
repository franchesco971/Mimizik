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
                ->getAvecArtistes($this->container->getParameter('nbMainVideo'),true);
                
        if ($videos == null) {
            throw $this->createNotFoundException('Video inexistant');
        }
        
        return $this->render('SpicySiteBundle:Site:index.html.twig',array(
            'videos'=>$videos,
            'page'=>$page
        ));
    }
    
    public function indexSuiteAction($page,$topVideos)
    {
        $nbResultVideoIndex=$this->container->getParameter('nbResultVideoIndex');
        $nbMainVideo=$this->container->getParameter('nbMainVideo');
        
        $toolsManager = $this->container->get('mimizik.tools');
        $videoIdsList=$toolsManager->getListId($topVideos);
        
        $videos=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Video')
                ->getSuiteAvecArtistes(
                        $page,
                        $nbMainVideo,
                        $nbResultVideoIndex,
                        $videoIdsList
                );
        
        if ($videos == null) {
            throw $this->createNotFoundException('Video inexistant');
        }
        
        return $this->render('SpicySiteBundle:Site:indexSuite.html.twig',array(
            'autresVideos'=>$videos,
            'nombrePage'=>ceil((count($videos)-$nbMainVideo)/ $nbResultVideoIndex),
            'page'=>$page
        ));
    }
    
    public function showAction($id)
    {
        $toolsManager = $this->container->get('mimizik.tools');
        $videoManager = $this->container->get('mimizik.videoService');
        $socialService = $this->container->get('mimizik.social');
        
        $video=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Video')
                ->getOneAvecArtistes($id);
        
         if ($video == null) {
            throw $this->createNotFoundException('Video inexistant');
        }
        
        $genres=$video->getGenreMusicaux();
        $genreIdsList=$toolsManager->getListId($genres);
        
        $suggestions=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Video')
                ->getSuggestions($genreIdsList,$video->getId());
        
        $tags=$socialService->getHashtags($video);
        $videoManager->increment($video);
        
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
        $socialService = $this->container->get('mimizik.social');
        $toolsService = $this->container->get('mimizik.tools');
        
        $artiste=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Artiste')
                ->getWithTags($id);
        
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
        
        $tabGenres=$toolsService->getAllGenresforVideoCol($videos);
        $tabGenresId=$toolsService->getAllGenresforVideoCol($videos,true);
        
        $suggestions=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Video')
                ->getSuggestionsArtistes($tabGenresId);
        
        if($suggestions == null) {
            throw $this->createNotFoundException('Suggestion inexistant');
        }

        $tabArtistes=$toolsService->getArtistesBySuggestions($suggestions,$id);
        
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
        $em=$this->getDoctrine()->getManager();
        
        $ranking=$em->getRepository('SpicyRankingBundle:Ranking')->getLastRanking();
        
        $ranking=$em->getRepository('SpicyRankingBundle:Ranking')->getPreviousRanking($ranking);
        //var_dump($ranking);
        //exit;
        
        $videos=$em->getRepository('SpicySiteBundle:Video')->getTop10byMonth($ranking);
        
        $position=1;
        foreach ($videos as $video) {
            var_dump($video->getTitre());
            
            foreach ($video->getVideoRankings() as $videoRanking) {
                var_dump($videoRanking->getNbVu());
                $videoRanking->setPosition($position);
                $em->persist($video);                            
            }
            $position++;
        }
        
        $em->flush();
        //exit;
        
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
    
    public function redirectYoutubeAction($url=null,$plus=null)
    {
        if($url==null)
        {
            $url='https://www.youtube.com/user/mimizikcom';
        }
        else
        {
            $url='https://www.youtube.com/channel/'.$url.'/'.$plus;
        }
       
        return $this->redirect($url);
    }
    
    public function showTopsAction($page)
    {
        $nbSuggestion=$this->container->getParameter('nbSuggestion');
        
        $tops=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Video')
                ->getTops($page,$nbSuggestion);
        
        return $this->render('SpicySiteBundle:Site:tops.html.twig',array(
            'videos'=>$tops,
            'nombrePage'=>ceil((count($tops))/ $nbSuggestion),
            'page'=>$page    
        ));
    }
}
