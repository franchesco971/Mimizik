<?php

namespace Spicy\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Spicy\SiteBundle\Entity\Video;
use Spicy\SiteBundle\Entity\TypeVideo;
use Spicy\SiteBundle\Entity\GenreMusical;
use Spicy\SiteBundle\Form\TypeVideoType;
use Symfony\Component\HttpFoundation\Request;
use Facebook\Facebook;
use Facebook\Exceptions as FacebookExceptions;
use Spicy\LyricsBundle\Entity\Paragraph;

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
    
    public function indexSuiteAction($page=1,$topVideos)
    {
        $nbResultVideoIndex=$this->container->getParameter('nbResultVideoIndex');
        $nbMainVideo=$this->container->getParameter('nbMainVideo');
        $em=$this->getDoctrine()->getManager();
        
        $toolsManager = $this->container->get('mimizik.tools');
        $videoIdsList=$toolsManager->getListId($topVideos);
        
        /*$videos=$em->getRepository('SpicySiteBundle:Video')
                ->getSuiteAvecArtistes(
                        $page,
                        $nbMainVideo,
                        $nbResultVideoIndex,
                        $videoIdsList
                );*/
        
        $videos=$em->getRepository('SpicySiteBundle:Video')->getSuite($page,$nbResultVideoIndex,$videoIdsList);
        
        if ($videos == null) {
            throw $this->createNotFoundException('Video inexistant');
        }
        
        return $this->render('SpicySiteBundle:Site:indexSuite.html.twig',array(
            'autresVideos'=>$videos,
            'nombrePage'=>ceil((count($videos)-$nbMainVideo)/ $nbResultVideoIndex),
            'page'=>$page
        ));
    }
    
    public function indexSuiteAjaxAction($page=1)
    {
        $nbResultVideoIndex=$this->container->getParameter('nbResultVideoIndex');
        $nbMainVideo=$this->container->getParameter('nbMainVideo');
        $em=$this->getDoctrine()->getManager();
        $topVideos=$em->getRepository('SpicySiteBundle:Video')->getAvecArtistes($nbMainVideo,true);
        
        $toolsManager = $this->container->get('mimizik.tools');
        $videoIdsList=$toolsManager->getListId($topVideos);        
        
        $videos=$em->getRepository('SpicySiteBundle:Video')->getSuite($page,$nbResultVideoIndex,$videoIdsList);
        
        if ($videos == null) {
            throw $this->createNotFoundException('Video inexistant');
        }
        
        return $this->render('SpicySiteBundle:Site:indexSuiteAjax.html.twig',array(
            'autresVideos'=>$videos,
            'nombrePage'=>ceil((count($videos)-$nbMainVideo)/ $nbResultVideoIndex),
            'page'=>$page
        ));
    }
    
    public function showAction($id,$referrer='video')
    {
        $toolsManager = $this->container->get('mimizik.tools');
        $videoManager = $this->container->get('mimizik.videoService');
        $socialService = $this->container->get('mimizik.social');
        $em=$this->getDoctrine()->getManager();
        
        $video=$em->getRepository('SpicySiteBundle:Video')
                ->getOneAvecArtistes($id);
        
         if ($video == null) {
            throw $this->createNotFoundException('Video inexistant');
        }
        
        $genres=$video->getGenreMusicaux();
        $genreIdsList=$toolsManager->getListId($genres);
        
        $suggestions=$em->getRepository('SpicySiteBundle:Video')
                ->getSuggestions($genreIdsList,$video->getId());
        
        $tags=$socialService->getHashtags($video);
        $videoManager->increment($video);
        
        $nbVuTotal=$em->getRepository('SpicyRankingBundle:VideoRanking')->getCountForVideo($video);
        $currentVideoRanking=$em->getRepository('SpicyRankingBundle:VideoRanking')->getOneOfLastRanking($video);
        
        $paragraphType=[Paragraph::INTRO=>'Intro', Paragraph::COUPLET=>'Couplet',  Paragraph::REFRAIN=>'Refrain',  Paragraph::OUTRO=>'Outro'];
        
        return $this->render('SpicySiteBundle:Site:show.html.twig',array(
            'lavideo'=>$video,
            'suggestions'=>$suggestions,
            'tags'=>$tags,
            'nbVuTotal'=>$nbVuTotal['total'],
            'lyrics'=>$em->getRepository('SpicyLyricsBundle:Lyrics')->getOrdered($video),
            'paragraphType'=>$paragraphType,
            'referrer'=>$referrer,
            'nbVuMonth'=>$currentVideoRanking?$currentVideoRanking->getNbVu():0
        ));
    }
    
    public function indexArtisteAction($page)
    {
        
        $em=$this->getDoctrine()->getManager();
        $nbArtisteAffiche=$this->container->getParameter('nbArtisteAffiche');
        
        $artistes=$em
                ->getRepository('SpicySiteBundle:Artiste')
                ->getAll($page,$nbArtisteAffiche);
        
        //if($artistes == null) 
        if($artistes == null) {
            throw $this->createNotFoundException('Artiste inexistant');
        }
        
        $selectArtistes=$em->getRepository('SpicySiteBundle:Artiste')->findAll();
               
        return $this->render('SpicySiteBundle:Site:indexArtiste.html.twig',array(
            'artistes'=>$artistes,
            'nombrePage'=>ceil((count($artistes))/ $nbArtisteAffiche),
            'page'=>$page,
            'selectArtistes'=>$selectArtistes
        ));
    }
    
    public function showArtisteAction($id,$page=1)
    {
        $nbSuggestion=$this->container->getParameter('nbSuggestion');
        $socialService = $this->container->get('mimizik.social');
        $toolsService = $this->container->get('mimizik.tools');
        $videoManager=$this->getDoctrine()->getManager()->getRepository('SpicySiteBundle:Video');
        
        $artiste=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Artiste')
                ->getWithTags($id);
        
        if($artiste == null) {
            throw $this->createNotFoundException('Artiste inexistant');
        }
        
        $videos=$videoManager
                ->getByArtiste($id);
        
        $nbVideos=count($videos);
        
        if($page==1)
        {
            $tabVideos=array_slice($videos,0,10);
        }
        elseif ($page>1) {
            $nbVids=($page*10)-10;
            $tabVideos=array_slice($videos,$nbVids,10);
        }
                
        $tabGenres=$toolsService->getAllGenresforVideoCol($videos);
        $tabGenresId=$toolsService->getAllGenresforVideoCol($videos,true);
        
        $suggestions=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Video')
                ->getSuggestionsArtistes($tabGenresId);        

        $tabArtistes=$toolsService->getArtistesBySuggestions($suggestions,$id);
        
        $fbLink=$socialService->getFacebookLink($artiste);
        $instaLink=$socialService->getInstagramLink($artiste);
        $twitterLinks=$socialService->getArrayTwitterLink($artiste);
        
        return $this->render('SpicySiteBundle:Site:showArtiste.html.twig',array(
            'fbLink'=>$fbLink,
            'twitterLinks'=>$twitterLinks,
            'instaLink'=>$instaLink,
            'videos'=>$tabVideos,
            'artiste'=>$artiste,
            'genres'=>$tabGenres,
            'suggestions'=>$tabArtistes,
            'nbVideos'=>$nbVideos,
            'page'=>$page
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
        $test=[];
        $em=$this->getDoctrine()->getManager();
        /*$videoManager = $this->container->get('mimizik.videoService');
        $randTopVideos=$videoManager->videosTop(10,10);
        $dateDemain=new \DateTime('TOMORROW');
        var_dump($dateDemain);
        foreach ($randTopVideos as $randTopVideo) {
            $date=$randTopVideo->getNextPublishDate();
            
            if($date)
            {
                var_dump($date);
                $date=$date->format('Y-m-d');
                var_dump('$date');
                $date=new \DateTime($date);
                if($date==$dateDemain)
                {
                    
                }    
                var_dump($date->modify('+16 hours'));
                var_dump('**');
            }
        }
        $test=$randTopVideo;*/
        $ranking=$em->getRepository('SpicyRankingBundle:Ranking')->getLastRanking();
        
        $previousRanking=$em->getRepository('SpicyRankingBundle:Ranking')->getPreviousRanking($ranking);
        var_dump($ranking);
        var_dump($previousRanking);
        
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
    
    public function redirectFeedbackAction(Request $request)
    {
        $href =$request->query->get('href');
        $href='http://'.$href;

        return $this->redirect($href);
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
    
    public function selectArtistesAction() {
        $em=$this->getDoctrine()->getManager();
        $selectArtistes=$em->getRepository('SpicySiteBundle:Artiste')->findAll();
        
        return $this->render('SpicySiteBundle:Site:Artiste\\_search.html.twig',array(
            'selectArtistes'=>$selectArtistes
        ));
    }
    
    public function selectArtistesForwardAction() {
        return $this->forward('SpicySiteBundle:Site:selectArtistes');
    }
}
